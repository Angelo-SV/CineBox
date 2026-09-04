<?php
require_once __DIR__ . '/../models/Perfil.php';

class PerfilController
{
    public static function index()
    {
        if (!isset($_SESSION['id'])) {
            header("Location: " . BASE_PATH . "/login");
            exit;
        }

        $idUsuario = $_SESSION['id'];
        $resumen = Perfil::obtenerResumen($idUsuario);
        require __DIR__ . '/../views/perfil/index.php';
        exit;
    }

    public static function biblioteca()
    {
        if (!isset($_SESSION['id'])) {
            header("Location: " . BASE_PATH . "/login");
            exit;
        }

        $idUsuario = $_SESSION['id'];

        $peliculas = Perfil::obtenerBiblioteca($idUsuario);

        require __DIR__ . '/../views/perfil/biblioteca.php';
    }

    public static function favoritos()
    {
        if (!isset($_SESSION['id'])) {
            header("Location: " . BASE_PATH . "/login");
            exit;
        }

        $idUsuario = $_SESSION['id'];

        $items = Perfil::obtenerFavoritos($idUsuario);
        require __DIR__ . '/../views/perfil/favoritos.php';
    }

    public static function historial()
    {
        if (!isset($_SESSION['id'])) {
            header("Location: " . BASE_PATH . "/login");
            exit;
        }

        $idUsuario = $_SESSION['id'];

        $items = Perfil::obtenerHistorial($idUsuario);

        require __DIR__ . '/../views/perfil/historial.php';
    }
}