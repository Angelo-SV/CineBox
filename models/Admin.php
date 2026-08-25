<?php
require_once __DIR__ . '/../config/Database.php';

class Admin
{
    public static function obtenerResumenAdmin()
    {
        $c = conectaOracle();

        $stmt = oci_parse($c,"BEGIN RESUMEN_ADMIN(:cursor); END;");

        $cursor = oci_new_cursor($c);

        oci_bind_by_name($stmt, ":cursor", $cursor, -1, OCI_B_CURSOR);

        oci_execute($stmt);
        oci_execute($cursor);

        $resumen = oci_fetch_assoc($cursor);

        oci_free_statement($stmt);
        oci_free_statement($cursor);

        return $resumen;
    }
}