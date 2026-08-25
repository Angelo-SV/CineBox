<?php
require_once __DIR__ . '/config/env.php';

// Conexión a Oracle Autonomous Database usando Wallet
function conectaOracle() {
    $username = env('DB_USERNAME');
    $password = env('DB_PASSWORD');

    // Alias TNS definido en el tnsnames.ora del Wallet
    $database = env('DB_DATABASE');

    $c = oci_connect($username, $password, $database);
    if (!$c) {
        $m = oci_error();
        trigger_error('No se pudo conectar a la base de datos: ' . $m['message'], E_USER_ERROR);
    }
    return $c;
}

// Ejecutar consultas SELECT
function consultaOracle($query) {
    $conexion = conectaOracle();
    $s = oci_parse($conexion, $query);
    if (!$s) {
        $e = oci_error($conexion);
        trigger_error(htmlentities($e['message'], ENT_QUOTES), E_USER_ERROR);
    }
    $r = oci_execute($s);
    if (!$r) {
        $e = oci_error($s);
        trigger_error(htmlentities($e['message'], ENT_QUOTES), E_USER_ERROR);
    }
    return $s; // retornamos el statement para poder fetch
}

// Ejecutar INSERT, UPDATE o DELETE
function insertaOracle($iquery) {
    $conexion = conectaOracle();
    $s = oci_parse($conexion, $iquery);
    if (!$s) {
        $e = oci_error($conexion);
        trigger_error(htmlentities($e['message'], ENT_QUOTES), E_USER_ERROR);
    }
    $r = oci_execute($s, OCI_NO_AUTO_COMMIT);
    if (!$r) {
        $e = oci_error($s);
        trigger_error(htmlentities($e['message'], ENT_QUOTES), E_USER_ERROR);
    }
    oci_commit($conexion);
    oci_free_statement($s);
    oci_close($conexion);
}
?>