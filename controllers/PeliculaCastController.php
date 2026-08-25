<?php
require_once __DIR__ . '/../models/PeliculaCast.php';

class PeliculaCastController {

    /* ===============================
       LISTAR CAST (GET - AJAX)
       =============================== */
    public static function listar() {
        header('Content-Type: application/json');

        try {
            $peliculaId = intval($_GET['id'] ?? 0);

            if ($peliculaId <= 0) {
                throw new Exception("Película inválida");
            }

            echo json_encode(
                PeliculaCast::obtenerPorPelicula($peliculaId)
            );

        } catch (Exception $e) {
            echo json_encode([]);
        }

        exit;
    }

    /* ===============================
       GUARDAR CAST (POST - AJAX)
       =============================== */
    public static function guardar() {
        header('Content-Type: application/json');

        try {
            $pelicula = intval($_POST['pelicula'] ?? 0);
            $actores = $_POST['actores'] ?? [];
            $protagonistas = $_POST['protagonistas'] ?? [];

            self::validarDatos($pelicula, $actores, $protagonistas);

            PeliculaCast::guardarCast($pelicula, $actores, $protagonistas);

            echo json_encode([
                'ok' => true,
                'tipo' => 'success',
                'msg' => '🎬 Cast actualizado correctamente'
            ]);

        } catch (Exception $e) {

            echo json_encode([
                'ok' => false,
                'tipo' => 'danger',
                'msg' => '❌ ' . $e->getMessage()
            ]);
        }

        exit;
    }

    /* ===============================
       VALIDACIÓN
       =============================== */
       private static function validarDatos(int $pelicula, array $actores, array $protagonistas): void
       {
           if ($pelicula <= 0) {
               throw new Exception('Película inválida');
           }
       
           if (empty($actores)) {
               throw new Exception('Debe seleccionar al menos un actor');
           }
       
           // 🔥 EXTRAER IDS DE ACTORES
            $actorIds = [];

            foreach ($actores as $actor) {
                if (!isset($actor['id'])) {
                    throw new Exception('Actor inválido detectado');
                }

                $id = intval($actor['id']);

                if ($id <= 0) {
                    throw new Exception('Actor inválido detectado');
                }

                $actorIds[] = $id;
            }

           if (!is_array($protagonistas)) {
               throw new Exception('Formato de protagonistas inválido');
           }
       
           // 🔥 NORMALIZAR PROTAGONISTAS
           $protagonistas = array_map('intval', $protagonistas);
       
           foreach ($protagonistas as $actorId) {
                if (!in_array($actorId, $actorIds, true)) {
                    throw new Exception('Protagonista inválido');
                }
            }
       }       
}