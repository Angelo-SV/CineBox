<?php
require_once __DIR__ . '/config/env.php';

$debug = filter_var(env('APP_DEBUG', 'false'), FILTER_VALIDATE_BOOLEAN);
ini_set('display_errors', $debug ? '1' : '0');
ini_set('display_startup_errors', $debug ? '1' : '0');
error_reporting(E_ALL);

/* Ruta base detectada automáticamente: vacía en la raíz del dominio (producción),
   o "/NombreCarpeta" cuando el proyecto vive en una subcarpeta (ej. XAMPP local).
   No requiere configuración manual por entorno. */
define('BASE_PATH', rtrim(str_replace('\\', '/', dirname($_SERVER['SCRIPT_NAME'])), '/'));

session_start();

/* Páginas vistas con sesión iniciada nunca deben quedar en el caché del
   navegador: sin esto, el botón "atrás" tras cerrar sesión puede mostrar
   por un instante una copia cacheada de una página con datos del usuario. */
if (!empty($_SESSION['id'])) {
    header('Cache-Control: no-store, no-cache, must-revalidate');
    header('Pragma: no-cache');
}

require_once __DIR__ . '/routes/web.php';