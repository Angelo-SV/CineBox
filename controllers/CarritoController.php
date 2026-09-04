<?php

require_once __DIR__ . '/../models/Carrito.php';
require_once __DIR__ . '/../models/Transaccion.php';
require_once __DIR__ . '/../config/Database.php';

class CarritoController
{
    public static function vista()
    {
        if (empty($_SESSION['id'])) {
            header("Location: " . BASE_PATH . "/login");
            exit;
        }

        $conn = conectaOracle();
        $items = Carrito::listar($_SESSION['id'], $conn);
        $total = Carrito::total($_SESSION['id'], $conn);
        oci_close($conn);

        require __DIR__ . '/../views/perfil/carrito.php';
    }

    /* ===============================
       TOGGLE ITEM (ADD/REMOVE)
       =============================== */
    public static function toggle()
    {
        header('Content-Type: application/json');

        if (empty($_SESSION['id'])) {
            echo json_encode([
                'ok' => false,
                'msg' => 'No autenticado'
            ]);
            exit;
        }

        $idUsuario  = $_SESSION['id'];
        $idPelicula = intval($_GET['id'] ?? 0);

        if ($idPelicula <= 0) {
            echo json_encode(['ok'=>false]);
            exit;
        }

        /* Una sola conexión compartida para las 4 operaciones de este
           request (antes cada Carrito::listar/agregar/eliminar/total abría
           y cerraba la suya propia — 4 handshakes contra el Wallet en una
           sola petición, lo que se sentía como 1-2 segundos de retraso al
           agregar una película al carrito). */
        $conn = null;
        try {
            $conn = conectaOracle();

            // 🔍 ver si ya existe en carrito
            $items = Carrito::listar($idUsuario, $conn);
            $existe = false;

            foreach ($items as $it) {
                if ($it['ID_PELICULA'] == $idPelicula) {
                    $existe = true;
                    break;
                }
            }

            if ($existe) {
                Carrito::eliminar($idUsuario, $idPelicula, $conn);
            } else {
                Carrito::agregar($idUsuario, $idPelicula, $conn);
            }

            $itemsActualizados = Carrito::listar($idUsuario, $conn);
            $cantidad = count($itemsActualizados);
            $total = Carrito::total($idUsuario, $conn);

            echo json_encode([
                'ok' => true,
                'en_carrito' => !$existe,
                'cantidad' => $cantidad,
                'total' => $total
            ]);

        } catch (Exception $e) {

            http_response_code(500);
            echo json_encode([
                'ok' => false,
                'msg' => 'Error carrito'
            ]);
        } finally {
            if ($conn) oci_close($conn);
        }
        exit;
    }

    /* ===============================
       LISTAR
       =============================== */
    public static function listar()
    {
        header('Content-Type: application/json');

        if (empty($_SESSION['id'])) {
            echo json_encode(['ok'=>false]);
            exit;
        }

        $idUsuario = $_SESSION['id'];

        $items = Carrito::listar($idUsuario);
        $total = Carrito::total($idUsuario);

        echo json_encode([
            'ok' => true,
            'items' => $items,
            'total' => $total
        ]);

        exit;
    }

    /* ===============================
       VACIAR
       =============================== */
    public static function vaciar()
    {
        header('Content-Type: application/json');

        if (empty($_SESSION['id'])) {
            echo json_encode(['ok'=>false]);
            exit;
        }

        Carrito::vaciar($_SESSION['id']);

        echo json_encode(['ok'=>true]);
        exit;
    }

    public static function carritoCantidad()
    {
        header('Content-Type: application/json');

        if (empty($_SESSION['id'])) {
            echo json_encode(['ok'=>false]);
            exit;
        }

        try {
            $items = Carrito::listar($_SESSION['id']);

            echo json_encode([
                'ok'=>true,
                'cantidad'=>count($items)
            ]);
        } catch (Exception $e) {
            http_response_code(500);
            echo json_encode(['ok'=>false]);
        }

        exit;
    }

    public static function alquilarTodo()
    {
        header('Content-Type: application/json');

        if (empty($_SESSION['id'])) {
            echo json_encode(['ok'=>false,'msg'=>'No autenticado']);
            exit;
        }

        $input = json_decode(file_get_contents("php://input"), true);

        $idUsuario = $_SESSION['id'];
        $metodo = intval($input['metodo_pago'] ?? 0);

        /* Una sola conexión compartida para todo el checkout: antes cada
           Carrito::listar/vaciar/cerrar y cada tieneAlquilerActivo/
           crearAlquiler DENTRO del ciclo (2 conexiones por película en el
           carrito) abrían la suya propia — con varios artículos en el
           carrito esto podía ser fácilmente 6, 8 o más conexiones seguidas
           para un solo "Alquilar todo". */
        $conn = null;
        try {
            $conn = conectaOracle();

            $items = Carrito::listar($idUsuario, $conn);

            if (empty($items)) {
                echo json_encode(['ok'=>false,'msg'=>'Carrito vacío']);
                exit;
            }

            foreach ($items as $p) {

                if (Transaccion::tieneAlquilerActivo($idUsuario, $p['ID_PELICULA'], $conn)) {
                    continue;
                }

                Transaccion::crearAlquiler(
                    $idUsuario,
                    $p['ID_PELICULA'],
                    $p['PRECIO'],
                    $metodo ?: null,
                    $conn
                );
            }

            Carrito::vaciar($idUsuario, $conn);
            Carrito::cerrar($idUsuario, $conn);

            echo json_encode(['ok'=>true]);

        } catch (Exception $e) {

            echo json_encode([
                'ok'=>false,
                'msg'=>$e->getMessage()
            ]);
        } finally {
            if ($conn) oci_close($conn);
        }

        exit;
    }

    public static function eliminarItem()
    {
        header('Content-Type: application/json');

        if (empty($_SESSION['id'])) {
            echo json_encode(['ok'=>false]);
            exit;
        }

        $input = json_decode(file_get_contents("php://input"), true);
        $idPelicula = intval($input['id_pelicula']);

        try {

            Carrito::eliminar($_SESSION['id'], $idPelicula);

            echo json_encode(['ok'=>true]);

        } catch (Exception $e) {

            echo json_encode(['ok'=>false]);
        }

        exit;
    }
}