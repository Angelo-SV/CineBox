<?php
require_once __DIR__ . '/config/env.php';

// Conexión a Oracle Autonomous Database usando Wallet
function conectaOracle() {
    $username = env('DB_USERNAME');
    $password = env('DB_PASSWORD');

    // Alias TNS definido en el tnsnames.ora del Wallet
    $database = env('DB_DATABASE');

    /* AL32UTF8 explícito: sin esto, OCI8 usa el NLS_LANG del entorno del
       servidor (a menudo ASCII), y cualquier acento o ñ llega convertido en
       "?" en vez de mostrarse correctamente. */
    $c = oci_connect($username, $password, $database, 'AL32UTF8');
    if (!$c) {
        $m = oci_error();
        /* Excepción real (no trigger_error con E_USER_ERROR): ese nivel de
           error es fatal y NO es capturable con try/catch, así que cualquier
           endpoint que responde JSON (ej. peliculas-publicas) terminaba
           devolviendo un volcado HTML de error en vez de JSON válido, y
           fetch().then(r => r.json()) truena en el navegador. Con una
           excepción normal, el try/catch de cada controlador puede
           responder un JSON de error limpio. */
        throw new Exception('No se pudo conectar a la base de datos: ' . $m['message']);
    }
    return $c;
}

// Ejecutar consultas SELECT
function consultaOracle($query) {
    $conexion = conectaOracle();
    $s = oci_parse($conexion, $query);
    if (!$s) {
        $e = oci_error($conexion);
        throw new Exception(htmlentities($e['message'], ENT_QUOTES));
    }
    $r = oci_execute($s);
    if (!$r) {
        $e = oci_error($s);
        throw new Exception(htmlentities($e['message'], ENT_QUOTES));
    }
    return $s; // retornamos el statement para poder fetch
}

// Ejecutar INSERT, UPDATE o DELETE
function insertaOracle($iquery) {
    $conexion = conectaOracle();
    $s = oci_parse($conexion, $iquery);
    if (!$s) {
        $e = oci_error($conexion);
        throw new Exception(htmlentities($e['message'], ENT_QUOTES));
    }
    $r = oci_execute($s, OCI_NO_AUTO_COMMIT);
    if (!$r) {
        $e = oci_error($s);
        throw new Exception(htmlentities($e['message'], ENT_QUOTES));
    }
    oci_commit($conexion);
    oci_free_statement($s);
    oci_close($conexion);
}
?>
