<?php
require_once('connDB.php');
header('Content-Type: application/json');

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $correo = trim($_POST['correo'] ?? '');
    if ($correo === '' || !filter_var($correo, FILTER_VALIDATE_EMAIL)) {
        echo json_encode(['status' => 'error', 'message' => 'Correo inválido']);
        exit;
    }

    try {
        $conexion = conectaOracle();
        if (!$conexion) {
            throw new Exception("Error de conexión a la base de datos");
        }

        // 🔹 Usar la nueva función con cursor
        $sql = "BEGIN :cursor := CONSULTA_CREDENCIALES_CORREO(:correo); END;";
        $stmt = oci_parse($conexion, $sql);

        $cursor = oci_new_cursor($conexion);
        oci_bind_by_name($stmt, ":correo", $correo);
        oci_bind_by_name($stmt, ":cursor", $cursor, -1, OCI_B_CURSOR);

        oci_execute($stmt);
        oci_execute($cursor);

        $row = oci_fetch_assoc($cursor);
        $correoExiste = !empty($row['CORREO']); // Si devuelve algo, el correo ya está en BD

        oci_free_statement($cursor);
        oci_free_statement($stmt);
        oci_close($conexion);

        if ($correoExiste) {
            echo json_encode(['status' => 'exists', 'message' => 'El correo ya está registrado']);
        } else {
            echo json_encode(['status' => 'ok']);
        }
    } catch (Exception $e) {
        error_log("Error en valida_correo.php: " . $e->getMessage());
        echo json_encode(['status' => 'error', 'message' => 'Error en el servidor']);
    }
}