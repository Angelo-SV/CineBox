<?php

require_once __DIR__ . '/../models/Actor.php';

class ActoresController {

    /* ===============================
       LISTADO
       =============================== */
    public static function index()
    {
        try {
            $actores = Actor::obtenerTodos();
            require __DIR__ . '/../views/admin/actores.php';

        } catch (Exception $e) {
            error_log($e->getMessage());
            header('Location: /Videoteca_ElResplandor/actores?msg=error_bd');
            exit;
        }
    }

    /* ===============================
       ACCIONES
       =============================== */
    public static function accion()
    {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            header('Location: /Videoteca_ElResplandor/actores');
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
                    Actor::insertar($data['nombre']);

                    header('Location: /Videoteca_ElResplandor/actores?msg=insertado');
                    break;

                /* ACTUALIZAR */
                case 'update':
                    $data = [
                        'id'     => (int)($_POST['id'] ?? 0),
                        'nombre' => $_POST['nombre'] ?? ''
                    ];

                    self::validarDatos($data, 'update');
                    Actor::actualizar($data['id'], $data['nombre']);

                    header('Location: /Videoteca_ElResplandor/actores?msg=actualizado');
                    break;

                /* ELIMINAR */
                case 'delete':
                    $data = [
                        'id' => (int)($_POST['id'] ?? 0)
                    ];

                    self::validarDatos($data, 'delete');
                    Actor::eliminar($data['id']);

                    header('Location: /Videoteca_ElResplandor/actores?msg=eliminado');
                    break;

                default:
                    throw new Exception("Acción no válida.");
            }

        } catch (Exception $e) {
            error_log($e->getMessage());
            header(
                'Location: /Videoteca_ElResplandor/actores?msg=error_campos&detalle=' .
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
                throw new Exception("ID de actor inválido.");
            }
        }

        if (in_array($accion, ['insert', 'update'])) {
            if (trim($data['nombre']) === '') {
                throw new Exception("El nombre del actor es obligatorio.");
            }

            if (mb_strlen($data['nombre']) < 2) {
                throw new Exception("El nombre del actor es demasiado corto.");
            }
        }
    }
}