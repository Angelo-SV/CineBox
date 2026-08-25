<?php

require_once __DIR__ . '/../models/Transaccion.php';
require_once __DIR__ . '/../models/Pelicula.php';

class AlquilerController
{

    /* ===============================
       CREAR ALQUILER
       POST
       =============================== */
       public static function crear()
       {
           header('Content-Type: application/json');
       
           if (empty($_SESSION['id'])) {
               http_response_code(401);
               echo json_encode(["ok"=>false,"msg"=>"Debe iniciar sesión"]);
               return;
           }
       
           $input = json_decode(file_get_contents("php://input"), true);
       
           $idUsuario  = $_SESSION['id'];
           $idPelicula = intval($input['pelicula_id'] ?? 0);
           $metodoPago = intval($input['metodo_pago'] ?? 0);       

        if ($idPelicula <= 0) {
            echo json_encode(["ok" => false, "msg" => "Película inválida"]);
            return;
        }

        try {

            /* 🔍 Verificar si ya tiene alquiler activo */
            if (Transaccion::tieneAlquilerActivo($idUsuario, $idPelicula)) {
                echo json_encode([
                    "ok" => false,
                    "msg" => "Ya tienes esta película alquilada"
                ]);
                return;
            }

            /* 🎬 Obtener precio actual */
            $pelicula = Pelicula::obtenerPorId($idPelicula);

            if (!$pelicula) {
                echo json_encode(["ok" => false, "msg" => "Película no existe"]);
                return;
            }

            $precio = $pelicula['PRECIO'];

            /* 💳 Crear alquiler */
            $idTransaccion = Transaccion::crearAlquiler(
                $idUsuario,
                $idPelicula,
                $precio,
                $metodoPago ?: null
            );
            
            if ($idTransaccion == -1) {
                echo json_encode([
                    "ok" => false,
                    "msg" => "Ya existe alquiler activo"
                ]);
                return;
            }            

            echo json_encode([
                "ok" => true,
                "msg" => "Alquiler creado correctamente"
            ]);

        } catch (Exception $e) {

            http_response_code(500);
            echo json_encode([
                "ok" => false,
                "msg" => $e->getMessage()
            ]);
        }
    }


    /* ===============================
       VALIDAR ACCESO A PELÍCULA
       GET
       =============================== */
    public static function validar()
    {
        if (empty($_SESSION['id'])) {
            echo json_encode(["ok" => false]);
            return;
        }

        $idUsuario  = $_SESSION['id'];
        $idPelicula = intval($_GET['pelicula_id'] ?? 0);

        if ($idPelicula <= 0) {
            echo json_encode(["ok" => false]);
            return;
        }

        $activo = Transaccion::tieneAlquilerActivo($idUsuario, $idPelicula);

        echo json_encode([
            "ok" => true,
            "activo" => $activo
        ]);
    }


    /* ===============================
       OBTENER ALQUILER ACTIVO
       =============================== */
    public static function activo()
    {
        if (empty($_SESSION['id'])) {
            echo json_encode(null);
            return;
        }

        $idUsuario  = $_SESSION['id'];
        $idPelicula = intval($_GET['pelicula_id'] ?? 0);

        $data = Transaccion::obtenerAlquilerActivo(
            $idUsuario,
            $idPelicula
        );

        echo json_encode($data);
    }


    /* ===============================
       EXPIRAR ALQUILERES
       (admin o cron)
       =============================== */
    public static function expirar()
    {
        try {
            Transaccion::expirarAlquileres();

            echo json_encode([
                "ok" => true,
                "msg" => "Alquileres expirados"
            ]);

        } catch (Exception $e) {
            echo json_encode([
                "ok" => false,
                "msg" => $e->getMessage()
            ]);
        }
    }

}