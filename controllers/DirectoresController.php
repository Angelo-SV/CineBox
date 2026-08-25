<?php

require_once __DIR__ . '/../models/Director.php';

class DirectoresController {

    /* ===============================
       LISTADO
       =============================== */
    public static function index()
    {
        try {
            $directores = Director::obtenerTodos();
            require __DIR__ . '/../views/admin/directores.php';

        } catch (Exception $e) {
            error_log($e->getMessage());
            header('Location: /Videoteca_ElResplandor/directores?msg=error_bd');
            exit;
        }
    }

    /* ===============================
       ACCIONES
       =============================== */
    public static function accion()
    {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            header('Location: /Videoteca_ElResplandor/directores');
            exit;
        }

        $action = $_POST['action'] ?? '';

        try {
            switch ($action) {

                /* INSERTAR */
                case 'insert':
                    $data = [
                        'nombre' => $_POST['nombre'] ?? ''
                    ];

                    self::validarDatos($data, 'insert');
                    Director::insertar($data['nombre']);

                    header('Location: /Videoteca_ElResplandor/directores?msg=insertado');
                    break;

                /* ACTUALIZAR */
                case 'update':
                    $data = [
                        'id'     => (int)($_POST['id'] ?? 0),
                        'nombre' => $_POST['nombre'] ?? ''
                    ];

                    self::validarDatos($data, 'update');
                    Director::actualizar($data['id'], $data['nombre']);

                    header('Location: /Videoteca_ElResplandor/directores?msg=actualizado');
                    break;

                /* ELIMINAR */
                case 'delete':
                    $data = [
                        'id' => (int)($_POST['id'] ?? 0)
                    ];

                    self::validarDatos($data, 'delete');
                    Director::eliminar($data['id']);

                    header('Location: /Videoteca_ElResplandor/directores?msg=eliminado');
                    break;

                default:
                    throw new Exception("Acción no válida.");
            }

        } catch (Exception $e) {
            error_log($e->getMessage());
            header(
                'Location: /Videoteca_ElResplandor/directores?msg=error_campos&detalle=' .
                urlencode($e->getMessage())
            );
        }

        exit;
    }

    /* ===============================
       VALIDACIÓN CENTRALIZADA
       =============================== */
    private static function validarDatos(array $data, string $accion)
    {
        if (in_array($accion, ['update', 'delete'])) {
            if (empty($data['id']) || $data['id'] <= 0) {
                throw new Exception("ID de director inválido.");
            }
        }

        if (in_array($accion, ['insert', 'update'])) {
            if (trim($data['nombre']) === '') {
                throw new Exception("El nombre del director es obligatorio.");
            }

            if (mb_strlen($data['nombre']) < 2) {
                throw new Exception("El nombre del director es demasiado corto.");
            }
        }
    }
}