<?php
require_once __DIR__ . '/../connDB.php';
function db() {
    $conn = conectaOracle();
    if (!$conn) {
        throw new Exception('Error conexión BD');
    }
    return $conn;
}