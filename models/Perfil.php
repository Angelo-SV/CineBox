<?php
require_once __DIR__ . '/../config/Database.php';

class Perfil
{

    public static function obtenerResumen($idUsuario)
    {
        $c = conectaOracle();
        $sql = "BEGIN RESUMEN_PERFIL_USUARIO(:usuario, :cursor); END;";

        $stmt = oci_parse($c, $sql);

        $cursor = oci_new_cursor($c);

        oci_bind_by_name($stmt, ":usuario", $idUsuario);
        oci_bind_by_name($stmt, ":cursor", $cursor, -1, OCI_B_CURSOR);

        oci_execute($stmt);
        oci_execute($cursor);

        $resumen = oci_fetch_assoc($cursor);

        oci_free_statement($stmt);
        oci_free_statement($cursor);

        return $resumen;
    }

    public static function obtenerBiblioteca($idUsuario)
    {
        $c = conectaOracle();
        $stmt = oci_parse($c, "BEGIN OBTENER_ALQUILER_ACTIVO(:usuario, :cursor); END;");
        $cursor = oci_new_cursor($c);
        oci_bind_by_name($stmt, ":usuario", $idUsuario);
        oci_bind_by_name($stmt, ":cursor", $cursor, -1, OCI_B_CURSOR);

        oci_execute($stmt);
        oci_execute($cursor);

        $peliculas = [];

        while ($row = oci_fetch_assoc($cursor)) {
            $peliculas[] = $row;
        }

        oci_free_statement($stmt);
        oci_free_statement($cursor);

        return $peliculas;
    }

    public static function obtenerFavoritos($idUsuario)
    {
        $c = conectaOracle();

        $sql = "BEGIN CONSULTAR_FAVORITOS_USUARIO(:usuario, :cursor); END;";

        $stmt = oci_parse($c, $sql);

        $cursor = oci_new_cursor($c);

        oci_bind_by_name($stmt, ":usuario", $idUsuario);
        oci_bind_by_name($stmt, ":cursor", $cursor, -1, OCI_B_CURSOR);

        oci_execute($stmt);
        oci_execute($cursor);

        $favoritos = [];

        while ($row = oci_fetch_assoc($cursor)) {
            $favoritos[] = $row;
        }

        oci_free_statement($stmt);
        oci_free_statement($cursor);

        return $favoritos;
    }

    public static function obtenerHistorial($idUsuario)
    {
        $c = conectaOracle();

        $stmt = oci_parse(
            $c,
            "BEGIN CONSULTAR_HISTORIAL_USUARIO(:usuario, :cursor); END;"
        );

        $cursor = oci_new_cursor($c);

        oci_bind_by_name($stmt, ":usuario", $idUsuario);
        oci_bind_by_name($stmt, ":cursor", $cursor, -1, OCI_B_CURSOR);

        oci_execute($stmt);
        oci_execute($cursor);

        $historial = [];

        while ($row = oci_fetch_assoc($cursor)) {
            $historial[] = $row;
        }

        oci_free_statement($stmt);
        oci_free_statement($cursor);

        return $historial;
    }
}