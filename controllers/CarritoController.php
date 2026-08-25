<?php

require_once __DIR__ . '/../models/Carrito.php';
require_once __DIR__ . '/../models/Transaccion.php';

class CarritoController
{
    public static function vista()
    {
        if (empty($_SESSION['id'])) {
            header("Location: login");
            exit;
        }

        $items = Carrito::listar($_SESSION['id']);
        $total = Carrito::total($_SESSION['id']);

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

        try {

            // 🔍 ver si ya existe en carrito
            $items = Carrito::listar($idUsuario);
            $existe = false;

            foreach ($items as $it) {
                if ($it['ID_PELICULA'] == $idPelicula) {
                    $existe = true;
                    break;
                }
            }

            if ($existe) {
                Carrito::eliminar($idUsuario, $idPelicula);
            } else {
                Carrito::agregar($idUsuario, $idPelicula);
            }

            $itemsActualizados = Carrito::listar($idUsuario);
            $cantidad = count($itemsActualizados);
            $total = Carrito::total($idUsuario);

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

        $items = Carrito::listar($_SESSION['id']);

        echo json_encode([
            'ok'=>true,
            'cantidad'=>count($items)
        ]);

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

        try {

            $items = Carrito::listar($idUsuario);

            if (empty($items)) {
                echo json_encode(['ok'=>false,'msg'=>'Carrito vacío']);
                exit;
            }

            foreach ($items as $p) {

                if (Transaccion::tieneAlquilerActivo($idUsuario, $p['ID_PELICULA'])) {
                    continue;
                }

                Transaccion::crearAlquiler(
                    $idUsuario,
                    $p['ID_PELICULA'],
                    $p['PRECIO'],
                    $metodo ?: null
                );
            }

            Carrito::vaciar($idUsuario);
            Carrito::cerrar($idUsuario);

            echo json_encode(['ok'=>true]);

        } catch (Exception $e) {

            echo json_encode([
                'ok'=>false,
                'msg'=>$e->getMessage()
            ]);
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