<?php
function requireAdmin()
{
    /* No autenticado */
    if (!isset($_SESSION['id'])) {
        header("Location: /Videoteca_ElResplandor/login?msg=no_auth");
        exit;
    }

    /* No es admin */
    if (!isset($_SESSION['rol']) || $_SESSION['rol'] != 1) {
        header("Location: /Videoteca_ElResplandor/?msg=no_permiso");
        exit;
    }
}