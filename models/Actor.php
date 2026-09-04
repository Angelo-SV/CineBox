<?php
require_once __DIR__ . '/../config/Database.php';

class Actor {

    /* ===============================
       OBTENER TODOS
       =============================== */
    public static function obtenerTodos($conexion = null) {
        $c = $conexion ?? conectaOracle();

        $sql = "BEGIN CONSULTAR_ACTORES(:cursor); END;";
        $stmt = oci_parse($c, $sql);

        $cursor = oci_new_cursor($c);
        oci_bind_by_name($stmt, ":cursor", $cursor, -1, OCI_B_CURSOR);

        oci_execute($stmt);
        oci_execute($cursor);

        $actores = [];
        while (($row = oci_fetch_assoc($cursor)) !== false) {
            $actores[] = $row;
        }

        oci_free_statement($stmt);
        if (!$conexion) oci_close($c);

        return $actores;
    }

    /* ===============================
       INSERTAR
       =============================== */
    public static function insertar($nombre) {
        $c = conectaOracle();

        $sql = "BEGIN INSERTAR_ACTOR(:nom); END;";
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

        $sql = "BEGIN ACTUALIZAR_ACTOR(:idp, :nom); END;";
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

        $sql = "BEGIN ELIMINAR_ACTOR(:idp); END;";
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