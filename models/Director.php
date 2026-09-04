<?php
require_once __DIR__ . '/../config/Database.php';

class Director {

    /* ===============================
       OBTENER TODOS
       =============================== */
    public static function obtenerTodos($conexion = null) {
        $c = $conexion ?? conectaOracle();

        $sql = "BEGIN CONSULTAR_DIRECTORES(:cursor); END;";
        $stmt = oci_parse($c, $sql);

        $cursor = oci_new_cursor($c);
        oci_bind_by_name($stmt, ":cursor", $cursor, -1, OCI_B_CURSOR);

        oci_execute($stmt);
        oci_execute($cursor);

        $directores = [];
        while (($row = oci_fetch_assoc($cursor)) !== false) {
            $directores[] = $row;
        }

        oci_free_statement($stmt);
        if (!$conexion) oci_close($c);

        return $directores;
    }

    /* ===============================
       INSERTAR
       =============================== */
    public static function insertar($nombre) {
        $c = conectaOracle();

        $sql = "BEGIN INSERTAR_DIRECTOR(:nom); END;";
        $stmt = oci_parse($c, $sql);
        oci_bind_by_name($stmt, ":nom", $nombre);

        if (!oci_execute($stmt)) {
            $e = oci_error($stmt);
            oci_close($c);
            throw new Exception($e['message']);
        }

        oci_free_statement($stmt);
        oci_close($c);
    }

    /* ===============================
       ACTUALIZAR
       =============================== */
    public static function actualizar($id, $nombre) {
        $c = conectaOracle();

        $sql = "BEGIN ACTUALIZAR_DIRECTOR(:idp, :nom); END;";
        $stmt = oci_parse($c, $sql);
        oci_bind_by_name($stmt, ":idp", $id);
        oci_bind_by_name($stmt, ":nom", $nombre);

        if (!oci_execute($stmt)) {
            $e = oci_error($stmt);
            oci_close($c);
            throw new Exception($e['message']);
        }

        oci_free_statement($stmt);
        oci_close($c);
    }

    /* ===============================
       ELIMINAR
       =============================== */
    public static function eliminar($id) {
        $c = conectaOracle();

        $sql = "BEGIN ELIMINAR_DIRECTOR(:idp); END;";
        $stmt = oci_parse($c, $sql);
        oci_bind_by_name($stmt, ":idp", $id);

        if (!oci_execute($stmt)) {
            $e = oci_error($stmt);
            oci_close($c);
            throw new Exception($e['message']);
        }

        oci_free_statement($stmt);
        oci_close($c);
    }
}