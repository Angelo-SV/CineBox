<?php
require_once __DIR__ . '/../config/Database.php';

class Genero
{
    private static function conexion()
    {
        return conectaOracle();
    }

    /* ===============================
       LISTAR
       =============================== */
    public static function all()
    {
        $c = self::conexion();

        $sql = "BEGIN CONSULTAR_GENEROS(:cursor); END;";
        $stmt = oci_parse($c, $sql);
        $cursor = oci_new_cursor($c);

        oci_bind_by_name($stmt, ":cursor", $cursor, -1, OCI_B_CURSOR);
        oci_execute($stmt);
        oci_execute($cursor);

        $generos = [];
        while ($row = oci_fetch_assoc($cursor)) {
            $generos[] = $row;
        }

        oci_free_statement($stmt);
        oci_close($c);

        return $generos;
    }

    /* ===============================
       INSERTAR
       =============================== */
    public static function crear($descripcion)
    {
        $c = self::conexion();

        $sql = "BEGIN INSERTAR_GENERO(:desc); END;";
        $stmt = oci_parse($c, $sql);
        oci_bind_by_name($stmt, ":desc", $descripcion);

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
    public static function actualizar($id, $descripcion)
    {
        $c = self::conexion();

        $sql = "BEGIN ACTUALIZAR_GENERO(:idg, :desc); END;";
        $stmt = oci_parse($c, $sql);
        oci_bind_by_name($stmt, ":idg", $id);
        oci_bind_by_name($stmt, ":desc", $descripcion);

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

        $sql = "BEGIN ELIMINAR_GENERO(:idg); END;";
        $stmt = oci_parse($c, $sql);
        oci_bind_by_name($stmt, ":idg", $id);

        $ok = oci_execute($stmt);
        if (!$ok) {
            $e = oci_error($stmt);
            throw new Exception($e['message']);
        }

        oci_free_statement($stmt);
        oci_close($c);
    }
}