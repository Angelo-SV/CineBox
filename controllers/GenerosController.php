<?php

require_once __DIR__ . '/../models/Genero.php';

class GenerosController
{
    /* ===============================
       LISTADO
       =============================== */
    public static function index()
    {
        try {
            $generos = Genero::all();
            require __DIR__ . '/../views/admin/generos.php';

        } catch (Exception $e) {
            error_log($e->getMessage());
            header('Location: /Videoteca_ElResplandor/generos?msg=error_bd');
            exit;
        }
    }

    /* ===============================
       ACCIONES
       =============================== */
    public static function accion()
    {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            header('Location: /Videoteca_ElResplandor/generos');
            exit;
        }

        $action = $_POST['action'] ?? '';

        try {
            switch ($action) {

                case 'insert':
                    $data = [
                        'descripcion' => $_POST['descripcion'] ?? ''
                    ];

                    self::validarDatos($data, 'insert');
                    Genero::crear($data['descripcion']);

                    header('Location: /Videoteca_ElResplandor/generos?msg=insertado');
                    break;

                case 'update':
                    $data = [
                        'id'          => (int)($_POST['id'] ?? 0),
                        'descripcion' => $_POST['descripcion'] ?? ''
                    ];

                    self::validarDatos($data, 'update');
                    Genero::actualizar($data['id'], $data['descripcion']);

                    header('Location: /Videoteca_ElResplandor/generos?msg=actualizado');
                    break;

                case 'delete':
                    $data = [
                        'id' => (int)($_POST['id'] ?? 0)
                    ];

                    self::validarDatos($data, 'delete');
                    Genero::eliminar($data['id']);

                    header('Location: /Videoteca_ElResplandor/generos?msg=eliminado');
                    break;

                default:
                    throw new Exception("Acción no válida.");
            }

        } catch (Exception $e) {
            error_log($e->getMessage());
            header(
                'Location: /Videoteca_ElResplandor/generos?msg=error_campos&detalle=' .
                urlencode($e->getMessage())
            );
        }

        exit;
    }

    /* ===============================
       VALIDACIÓN
       =============================== */
    private static function validarDatos(array $data, string $accion)
    {
        if (in_array($accion, ['update', 'delete'])) {
            if (empty($data['id']) || $data['id'] <= 0) {
                throw new Exception("ID de género inválido.");
            }
        }

        if (in_array($accion, ['insert', 'update'])) {
            if (trim($data['descripcion']) === '') {
                throw new Exception("La descripción del género es obligatoria.");
            }
        }
    }
}