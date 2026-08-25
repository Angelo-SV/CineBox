<?php
require_once __DIR__ . '/../config/Database.php';
class Reproductor
{
    public static function validarTransaccion($idTransaccion, $idUsuario)
    {
        $c = conectaOracle();

        $stmt = oci_parse($c, "BEGIN VALIDAR_TRANSACCION_ACTIVA(:transaccion, :usuario, :cursor); END;");

        $cursor = oci_new_cursor($c);

        oci_bind_by_name($stmt, ":transaccion", $idTransaccion);
        oci_bind_by_name($stmt, ":usuario", $idUsuario);
        oci_bind_by_name($stmt, ":cursor", $cursor, -1, OCI_B_CURSOR);

        oci_execute($stmt);
        oci_execute($cursor);

        $data = oci_fetch_assoc($cursor);

        oci_free_statement($stmt);
        oci_free_statement($cursor);

        return $data;
    }
}