<?php
require_once __DIR__ . '/../config/Database.php';

class Estudio
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

        $sql = "BEGIN CONSULTAR_ESTUDIOS(:cursor); END;";
        $stmt = oci_parse($c, $sql);
        $cursor = oci_new_cursor($c);

        oci_bind_by_name($stmt, ":cursor", $cursor, -1, OCI_B_CURSOR);
        oci_execute($stmt);
        oci_execute($cursor);

        $estudios = [];
        while ($row = oci_fetch_assoc($cursor)) {
            $estudios[] = $row;
        }

        oci_free_statement($stmt);
        if (!$conexion) oci_close($c);

        return $estudios;
    }

    /* ===============================
       INSERTAR
       =============================== */
    public static function crear($nombre)
    {
        $c = self::conexion();

        $sql = "BEGIN INSERTAR_ESTUDIO(:nom); END;";
        $stmt = oci_parse($c, $sql);
        oci_bind_by_name($stmt, ":nom", $nombre);

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
    public static function actualizar($id, $nombre)
    {
        $c = self::conexion();

        $sql = "BEGIN ACTUALIZAR_ESTUDIO(:idp, :nom); END;";
        $stmt = oci_parse($c, $sql);
        oci_bind_by_name($stmt, ":idp", $id);
        oci_bind_by_name($stmt, ":nom", $nombre);

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

        $sql = "BEGIN ELIMINAR_ESTUDIO(:idp); END;";
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