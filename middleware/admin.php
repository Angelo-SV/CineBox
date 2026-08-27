<?php
function requireAdmin()
{
    /* No autenticado */
    if (!isset($_SESSION['id'])) {
        header("Location: " . BASE_PATH . "/login?msg=no_auth");
        exit;
    }

    /* No es admin */
    if (!isset($_SESSION['rol']) || $_SESSION['rol'] != 1) {
        header("Location: " . BASE_PATH . "/?msg=no_permiso");
        exit;
    }
}