<?php
require_once __DIR__ . '/../models/Admin.php';

class AdminController
{
    public static function index()
    {
        if (!isset($_SESSION['id'])) {
            header("Location: /Videoteca_ElResplandor/login");
            exit;
        }
        $resumen = Admin::obtenerResumenAdmin();
        require __DIR__ . '/../views/admin/index.php';
        exit;
    }

}