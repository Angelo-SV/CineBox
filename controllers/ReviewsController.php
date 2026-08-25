<?php
require_once __DIR__ . '/../models/Review.php';

class ReviewsController {

    public static function guardar() {

        header('Content-Type: application/json'); // 👈 AÑADIR

        if (empty($_SESSION['id'])) {
            http_response_code(401);
            echo json_encode(['ok'=>false,'msg'=>'Debe iniciar sesión']);
            exit;
        }      

        try {
            $usuario  = $_SESSION['id'];
            $pelicula = intval($_POST['pelicula']);
            $calif    = intval($_POST['calificacion']);
            $coment   = trim($_POST['comentario']);

            if ($calif < 1 || $calif > 10) {
                throw new Exception("Calificación inválida");
            }

            if (Review::usuarioYaComento($_SESSION['id'],$pelicula)) {
                echo json_encode([
                    'ok'=>false,
                    'msg'=>'Ya enviaste un review para esta película'
                ]);
                exit;
            }  

            Review::insertar($usuario, $pelicula, $calif, $coment);

            echo json_encode(['ok'=>true]);

        } catch (Exception $e) {
            echo json_encode([
                'ok'=>false,
                'msg'=>$e->getMessage()
            ]);
        }

        exit;
    }
}