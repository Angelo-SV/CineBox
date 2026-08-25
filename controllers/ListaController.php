<?php
require_once __DIR__ . '/../models/Lista.php';

class ListaController {

    public static function toggle() {
        if (empty($_SESSION['id'])) {
            echo json_encode(['ok'=>false]);
            exit;
        }

        $idUsuario = $_SESSION['id'];
        $idPelicula = (int) $_GET['id'];

        $estado = Lista::toggle($idUsuario, $idPelicula);

        echo json_encode([
            'ok' => true,
            'favorito' => $estado
        ]);
    }

}