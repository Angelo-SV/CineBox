<?php

require_once __DIR__ . '/../models/Estudio.php';

class EstudiosController
{
    /* ===============================
       LISTADO
       =============================== */
    public static function index()
    {
        try {
            $estudios = Estudio::all();
            require __DIR__ . '/../views/admin/estudios.php';

        } catch (Exception $e) {
            error_log($e->getMessage());
            header('Location: /Videoteca_ElResplandor/estudios?msg=error_bd');
            exit;
        }
    }

    /* ===============================
       ACCIONES
       =============================== */
    public static function accion()
    {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            header('Location: /Videoteca_ElResplandor/estudios');
            exit;
        }

        $action = $_POST['action'] ?? '';

        try {
            switch ($action) {

                case 'insert':
                    $data = [
                        'nombre' => $_POST['nombre'] ?? ''
                    ];

                    self::validarDatos($data, 'insert');
                    Estudio::crear($data['nombre']);

                    header('Location: /Videoteca_ElResplandor/estudios?msg=insertado');
                    break;

                case 'update':
                    $data = [
                        'id'     => (int)($_POST['id'] ?? 0),
                        'nombre' => $_POST['nombre'] ?? ''
                    ];

                    self::validarDatos($data, 'update');
                    Estudio::actualizar($data['id'], $data['nombre']);

                    header('Location: /Videoteca_ElResplandor/estudios?msg=actualizado');
                    break;

                case 'delete':
                    $data = [
                        'id' => (int)($_POST['id'] ?? 0)
                    ];

                    self::validarDatos($data, 'delete');
                    Estudio::eliminar($data['id']);

                    header('Location: /Videoteca_ElResplandor/estudios?msg=eliminado');
                    break;

                default:
                    throw new Exception("Acción no válida.");
            }

        } catch (Exception $e) {
            error_log($e->getMessage());
            header(
                'Location: /Videoteca_ElResplandor/estudios?msg=error_campos&detalle=' .
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
                throw new Exception("ID de estudio inválido.");
            }
        }

        if (in_array($accion, ['insert', 'update'])) {
            if (trim($data['nombre']) === '') {
                throw new Exception("El nombre del estudio es obligatorio.");
            }
        }
    }
}