<?php
require_once __DIR__ . '/../models/Proveedor.php';

class ProveedoresController
{
    /* ===============================
       LISTADO
       =============================== */
    public static function index()
    {
        $proveedores = Proveedor::all();
        require __DIR__ . '/../views/admin/proveedores.php';
    }

    /* ===============================
       VALIDACIÓN
       =============================== */
    private static function validarDatos(array $data, bool $validarId = false): array
    {
        $errores = [];

        if ($validarId) {
            $id = (int)($data['id'] ?? 0);
            if ($id <= 0) {
                $errores[] = 'ID inválido';
            }
        }

        $nombre = trim($data['nombre'] ?? '');
        $contacto = trim($data['contacto'] ?? '');
        $correo = trim($data['correo'] ?? '');
        $telefono = trim($data['telefono'] ?? '');
        $direccion = trim($data['direccion'] ?? '');

        if ($nombre === '') {
            $errores[] = 'El nombre es obligatorio';
        }

        if ($contacto === '') {
            $errores[] = 'El contacto es obligatorio';
        }

        if ($correo === '' || !filter_var($correo, FILTER_VALIDATE_EMAIL)) {
            $errores[] = 'Correo electrónico no válido';
        }

        if ($telefono === '' || !preg_match('/^[0-9+\-\s]{7,20}$/', $telefono)) {
            $errores[] = 'Teléfono no válido';
        }

        if ($direccion === '') {
            $errores[] = 'La dirección es obligatoria';
        }

        return $errores;
    }

    /* ===============================
       ACCIONES
       =============================== */
    public static function accion()
    {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            header('Location: ' . BASE_PATH . '/proveedores');
            exit;
        }

        $action = $_POST['action'] ?? '';

        try {
            switch ($action) {

                case 'insert':
                    $errores = self::validarDatos($_POST);
                    if (!empty($errores)) {
                        header('Location: ' . BASE_PATH . '/proveedores?msg=error_campos');
                        exit;
                    }

                    Proveedor::crear($_POST);
                    header('Location: ' . BASE_PATH . '/proveedores?msg=insertado');
                    break;

                case 'update':
                    $errores = self::validarDatos($_POST, true);
                    if (!empty($errores)) {
                        header('Location: ' . BASE_PATH . '/proveedores?msg=error_campos');
                        exit;
                    }

                    Proveedor::actualizar((int)$_POST['id'], $_POST);
                    header('Location: ' . BASE_PATH . '/proveedores?msg=actualizado');
                    break;

                case 'delete':
                    $id = (int)($_POST['id'] ?? 0);
                    if ($id <= 0) {
                        header('Location: ' . BASE_PATH . '/proveedores?msg=error_campos');
                        exit;
                    }

                    Proveedor::eliminar($id);
                    header('Location: ' . BASE_PATH . '/proveedores?msg=eliminado');
                    break;

                default:
                    header('Location: ' . BASE_PATH . '/proveedores?msg=error_bd');
            }
        } catch (Exception $e) {
            header(
                'Location: ' . BASE_PATH . '/proveedores?msg=error_bd&detalle=' .
                urlencode($e->getMessage())
            );
        }
        exit;
    }
}