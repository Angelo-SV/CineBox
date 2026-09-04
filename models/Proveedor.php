<?php
require_once __DIR__ . '/../config/Database.php';

class Proveedor
{
    private static function conexion()
    {
        return conectaOracle();
    }

    /* ===============================
       LISTAR
       =============================== */
    public static function all($conexion = null)
    {
        $c = $conexion ?? self::conexion();

        $sql = "BEGIN CONSULTAR_PROVEEDORES(:cursor); END;";
        $stmt = oci_parse($c, $sql);
        $cursor = oci_new_cursor($c);

        oci_bind_by_name($stmt, ":cursor", $cursor, -1, OCI_B_CURSOR);
        oci_execute($stmt);
        oci_execute($cursor);

        $proveedores = [];
        while ($row = oci_fetch_assoc($cursor)) {
            $proveedores[] = $row;
        }

        oci_free_statement($stmt);
        if (!$conexion) oci_close($c);

        return $proveedores;
    }

    /* ===============================
       INSERTAR
       =============================== */
    public static function crear($data)
    {
        $c = self::conexion();

        $sql = "BEGIN INSERTAR_PROVEEDOR(
            :nom, :con, :cor, :tel, :dir
        ); END;";

        $stmt = oci_parse($c, $sql);
        oci_bind_by_name($stmt, ":nom", $data['nombre']);
        oci_bind_by_name($stmt, ":con", $data['contacto']);
        oci_bind_by_name($stmt, ":cor", $data['correo']);
        oci_bind_by_name($stmt, ":tel", $data['telefono']);
        oci_bind_by_name($stmt, ":dir", $data['direccion']);

        $ok = oci_execute($stmt);

        if (!$ok) {
            $e = oci_error($stmt);
            throw new Exception($e['message']);
        }

        oci_free_statement($stmt);
        oci_close($c);
    }

    /* ===============================
       ACTUALIZAR
       =============================== */
    public static function actualizar($id, $data)
    {
        $c = self::conexion();

        $sql = "BEGIN ACTUALIZAR_PROVEEDOR(
            :idp, :nom, :con, :cor, :tel, :dir
        ); END;";

        $stmt = oci_parse($c, $sql);
        oci_bind_by_name($stmt, ":idp", $id);
        oci_bind_by_name($stmt, ":nom", $data['nombre']);
        oci_bind_by_name($stmt, ":con", $data['contacto']);
        oci_bind_by_name($stmt, ":cor", $data['correo']);
        oci_bind_by_name($stmt, ":tel", $data['telefono']);
        oci_bind_by_name($stmt, ":dir", $data['direccion']);

        $ok = oci_execute($stmt);

        if (!$ok) {
            $e = oci_error($stmt);
            throw new Exception($e['message']);
        }

        oci_free_statement($stmt);
        oci_close($c);
    }

    /* ===============================
       ELIMINAR
       =============================== */
    public static function eliminar($id)
    {
        $c = self::conexion();

        $sql = "BEGIN ELIMINAR_PROVEEDOR(:idp); END;";
        $stmt = oci_parse($c, $sql);
        oci_bind_by_name($stmt, ":idp", $id);

        $ok = oci_execute($stmt);

        if (!$ok) {
            $e = oci_error($stmt);
            throw new Exception($e['message']);
        }

        oci_free_statement($stmt);
        oci_close($c);
    }
}