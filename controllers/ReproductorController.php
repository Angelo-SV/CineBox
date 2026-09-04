<?php
require_once __DIR__ . '/../config/constants.php';
require_once __DIR__ . '/../models/Reproductor.php';

class ReproductorController
{

    public static function ver($idTransaccion)
    {
        if (!isset($_SESSION['id'])) {
            header("Location: " . BASE_PATH . "/login");
            exit;
        }

        $idUsuario = $_SESSION['id'];

        $pelicula = Reproductor::validarTransaccion($idTransaccion, $idUsuario);

        if (!$pelicula) {
            echo "<h2 style='color:red'>No tienes acceso a esta película</h2>";
            exit;
        }

        require __DIR__ . '/../views/reproductor/ver.php';
    }

}