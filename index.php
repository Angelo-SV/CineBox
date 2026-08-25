<?php
require_once __DIR__ . '/config/env.php';

$debug = filter_var(env('APP_DEBUG', 'false'), FILTER_VALIDATE_BOOLEAN);
ini_set('display_errors', $debug ? '1' : '0');
ini_set('display_startup_errors', $debug ? '1' : '0');
error_reporting(E_ALL);

session_start();
require_once __DIR__ . '/routes/web.php';