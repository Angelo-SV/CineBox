<?php
$basePath = trim(BASE_PATH, '/');
$uri = parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH);
$uri = trim($uri, '/');
/* quitar el base path */
if (str_starts_with($uri, $basePath)) {
    $uri = substr($uri, strlen($basePath));
    $uri = trim($uri, '/');
}
/* PUBLIC */
if ($uri === '' || $uri === 'index.php') {
    require __DIR__ . '/../controllers/PeliculasPublicController.php';
    PeliculasPublicController::index();
    exit;
}
if ($uri === 'peliculas-publicas') { 
    require __DIR__ . '/../controllers/PeliculasPublicController.php';
    PeliculasPublicController::listar(); 
    exit; 
} 
if ($uri === 'peliculas-filtros') { 
    require __DIR__ . '/../controllers/PeliculasPublicController.php';
    PeliculasPublicController::filtros(); 
    exit; 
} 
if ($uri === 'nosotros') {
    require_once __DIR__ . '/../config/constants.php';
    require __DIR__ . '/../views/public/nosotros.php';
    exit;
}
if ($uri === 'pelicula') { 
    require __DIR__ . '/../controllers/PeliculasPublicController.php';
    PeliculasPublicController::detalle(); 
    exit; 
}
if ($uri === 'pelicula-json') {
    require __DIR__ . '/../controllers/PeliculasPublicController.php';
    PeliculasPublicController::detalleJson();
    exit;
}
if ($uri === 'login') {
    require __DIR__ . '/../views/public/login.php';
    exit;
}
if ($uri === 'registro') {
    require __DIR__ . '/../views/public/registro.php';
    exit;
}
/* AUTH */
if ($uri === 'auth/login' || $uri === 'auth/logout') {
    require __DIR__ . '/../controllers/AuthController.php';
    exit;
}
/* PERFIL USUARIO */
if ($uri === 'perfil') {
    require __DIR__ . '/../controllers/PerfilController.php';
    PerfilController::index();
    exit;
}
if ($uri === 'perfil/biblioteca') {
    require __DIR__ . '/../controllers/PerfilController.php';
    PerfilController::biblioteca();
    exit;
}
if ($uri === 'perfil/favoritos') {
    require __DIR__ . '/../controllers/PerfilController.php';
    PerfilController::favoritos();
    exit;
}
if ($uri === 'perfil/historial') {
    require __DIR__ . '/../controllers/PerfilController.php';
    PerfilController::historial();
    exit;
}
if (preg_match('#^ver/([0-9]+)$#', $uri, $matches)) {
    require __DIR__ . '/../controllers/ReproductorController.php';
    ReproductorController::ver($matches[1]);
    exit;
}
if ($uri === 'review-guardar') {
    require __DIR__ . '/../controllers/ReviewsController.php';
    ReviewsController::guardar();
    exit;
}
if ($uri === 'favoritos-toggle') {
    require __DIR__ . '/../controllers/ListaController.php';
    ListaController::toggle();
    exit;
}
/* ===============================
    CARRITO
    =============================== */
if ($uri === 'carrito-toggle') {
require __DIR__ . '/../controllers/CarritoController.php';
CarritoController::toggle();
exit;
}
if ($uri === 'carrito-listar') {
    require __DIR__ . '/../controllers/CarritoController.php';
    CarritoController::listar();
    exit;
}
if ($uri === 'carrito-vaciar') {
    require __DIR__ . '/../controllers/CarritoController.php';
    CarritoController::vaciar();
    exit;
}
if ($uri === 'carrito-eliminar') {
    require __DIR__ . '/../controllers/CarritoController.php';
    CarritoController::eliminarItem();
    exit;
}
if ($uri === 'carrito/cantidad') {
    require __DIR__ . '/../controllers/CarritoController.php';
    CarritoController::carritoCantidad();
    exit;
}
if ($uri === 'perfil/carrito') {
    require __DIR__ . '/../controllers/CarritoController.php';
    CarritoController::vista();
    exit;
}
if ($uri === 'carrito-alquilar') {
    require __DIR__ . '/../controllers/CarritoController.php';
    CarritoController::alquilarTodo();
    exit;
}    
/* ===============================
   Transacciones
   =============================== */
if ($uri === 'alquilar' && $_SERVER['REQUEST_METHOD'] === 'POST') {
require __DIR__ . '/../controllers/AlquilerController.php';
AlquilerController::crear();
exit;
}

if ($uri === 'alquiler-validar') {
    require __DIR__ . '/../controllers/AlquilerController.php';
    AlquilerController::validar();
    exit;
}

/* ADMIN */
if ($uri === 'admin') {
require __DIR__ . '/../middleware/admin.php';
requireAdmin();
require __DIR__ . '/../controllers/AdminController.php';
AdminController::index();
exit;
}
/* ===============================
   USUARIOS (MVC)
   =============================== */
/* LISTADO */
if ($uri === 'usuarios') {
    require __DIR__ . '/../controllers/UsuariosController.php';
    exit;
}
/* ACCIONES */
if (
    $uri === 'usuarios/crear' ||
    $uri === 'usuarios/actualizar' ||
    $uri === 'usuarios/eliminar' ||
    $uri === 'usuarios/alquileres'
) {
    require __DIR__ . '/../controllers/UsuariosController.php';
    exit;
}
/* ===============================
   PROVEEDORES (ADMIN)
   =============================== */
if ($uri === 'proveedores') {
require __DIR__ . '/../middleware/admin.php';
requireAdmin();
require __DIR__ . '/../controllers/ProveedoresController.php';
ProveedoresController::index();
exit;
}
if (
    $uri === 'proveedores/crear' ||
    $uri === 'proveedores/actualizar' ||
    $uri === 'proveedores/eliminar'
) {
    require __DIR__ . '/../middleware/admin.php';
    requireAdmin();
    require __DIR__ . '/../controllers/ProveedoresController.php';
    ProveedoresController::accion();
    exit;
}
/* ===============================
   ESTUDIOS (ADMIN)
   =============================== */
if ($uri === 'estudios') {
require __DIR__ . '/../middleware/admin.php';
requireAdmin();
require __DIR__ . '/../controllers/EstudiosController.php';
EstudiosController::index();
exit;
}
if (
    $uri === 'estudios/crear' ||
    $uri === 'estudios/actualizar' ||
    $uri === 'estudios/eliminar'
) {
    require __DIR__ . '/../middleware/admin.php';
    requireAdmin();
    require __DIR__ . '/../controllers/EstudiosController.php';
    EstudiosController::accion();
    exit;
}
/* ===============================
   GÉNEROS (ADMIN)
   =============================== */
if ($uri === 'generos') {
require __DIR__ . '/../middleware/admin.php';
requireAdmin();
require __DIR__ . '/../controllers/GenerosController.php';
GenerosController::index();
exit;
}
if (
    $uri === 'generos/crear' ||
    $uri === 'generos/actualizar' ||
    $uri === 'generos/eliminar'
) {
    require __DIR__ . '/../middleware/admin.php';
    requireAdmin();
    require __DIR__ . '/../controllers/GenerosController.php';
    GenerosController::accion();
    exit;
}
/* ===============================
   ACTORES (ADMIN)
   =============================== */
if ($uri === 'actores') {
require __DIR__ . '/../middleware/admin.php';
requireAdmin();
require __DIR__ . '/../controllers/ActoresController.php';
ActoresController::index();
exit;
}
if (
    $uri === 'actores/crear' ||
    $uri === 'actores/actualizar' ||
    $uri === 'actores/eliminar'
) {
    require __DIR__ . '/../middleware/admin.php';
    requireAdmin();
    require __DIR__ . '/../controllers/ActoresController.php';
    ActoresController::accion();
    exit;
}
/* ===============================
   DIRECTORES (ADMIN)
   =============================== */
if ($uri === 'directores') {
require __DIR__ . '/../middleware/admin.php';
requireAdmin();
require __DIR__ . '/../controllers/DirectoresController.php';
DirectoresController::index();
exit;
}
if (
    $uri === 'directores/crear' ||
    $uri === 'directores/actualizar' ||
    $uri === 'directores/eliminar'
) {
    require __DIR__ . '/../middleware/admin.php';
    requireAdmin();
    require __DIR__ . '/../controllers/DirectoresController.php';
    DirectoresController::accion();
    exit;
}
/* ===============================
   PELÍCULAS (ADMIN)
   =============================== */
if ($uri === 'peliculas') {
    require __DIR__ . '/../middleware/admin.php';
    requireAdmin();
    require __DIR__ . '/../controllers/PeliculasController.php';
    PeliculasController::listarAdmin();
    exit;
}
if ($uri === 'peliculas/crear') {
    require __DIR__ . '/../controllers/PeliculasController.php';
    PeliculasController::crear();
    exit;
}
if ($uri === 'peliculas/actualizar') {
    require __DIR__ . '/../controllers/PeliculasController.php';
    PeliculasController::actualizar();
    exit;
}
if ($uri === 'peliculas/eliminar') {
    require __DIR__ . '/../controllers/PeliculasController.php';
    PeliculasController::eliminar();
    exit;
}
/* ===============================
   CAST
   =============================== */
if ($uri === 'cast/listarActores') {
require __DIR__ . '/../middleware/admin.php';
requireAdmin();
require __DIR__ . '/../controllers/PeliculaCastController.php';
PeliculaCastController::listar();
exit;
}
if ($uri === 'cast/guardar') {
require __DIR__ . '/../middleware/admin.php';
requireAdmin();
require __DIR__ . '/../controllers/PeliculaCastController.php';
PeliculaCastController::guardar();
exit;
}
/* 404 */
http_response_code(404);
echo "Página no encontrada";