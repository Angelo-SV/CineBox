<?php
require_once __DIR__ . '/../models/Pelicula.php';
require_once __DIR__ . '/../models/Actor.php';
require_once __DIR__ . '/../models/Director.php';
require_once __DIR__ . '/../models/Estudio.php';
require_once __DIR__ . '/../models/Genero.php';
require_once __DIR__ . '/../models/Proveedor.php';
require_once __DIR__ . '/../config/constants.php';
require_once __DIR__ . '/../middleware/admin.php';

class PeliculasController {

    /* ===============================
       Listar admin
       =============================== */
       public static function listarAdmin() {
        requireAdmin();
    
        try {
            $peliculas   = Pelicula::listar();
            $generos     = Genero::all();
            $actores     = Actor::obtenerTodos();
            $directores  = Director::obtenerTodos();
            $estudios    = Estudio::all();
            $proveedores = Proveedor::all();
    
            require __DIR__ . '/../views/admin/peliculas.php';
    
        } catch (Exception $e) {
            error_log($e->getMessage());
            header("Location: " . BASE_PATH . "/peliculas?msg=error_bd");
            exit;
        }
    }    

    private static function validarDatos(array $data, bool $esUpdate = false) {
        if ($esUpdate && empty($data['idp'])) {
            throw new Exception("ID de película inválido.");
        }
    
        if (trim($data['tit']) === '') {
            throw new Exception("El título es obligatorio.");
        }
    
        if (trim($data['sin']) === '') {
            throw new Exception("La sinopsis es obligatoria.");
        }
    
        if (empty($data['gen']) || empty($data['dir']) || empty($data['est']) || empty($data['prov'])) {
            throw new Exception("Debe seleccionar género, director, estudio y proveedor.");
        }
    
        if (empty($data['fec'])) {
            throw new Exception("La fecha de estreno es obligatoria.");
        }
    
        $fechaEstreno = strtotime($data['fec']);
        $hoy = strtotime(date('Y-m-d'));
    
        if ($fechaEstreno > $hoy) {
            throw new Exception("La fecha de estreno no puede ser posterior a hoy.");
        }
    
        if (empty($data['dur']) || $data['dur'] <= 0) {
            throw new Exception("La duración debe ser mayor a 0.");
        }
    
        if (empty($data['pre']) || $data['pre'] <= 0) {
            throw new Exception("El precio debe ser mayor a 0.");
        }
    }    

    /* ===============================
       CREAR
       =============================== */
       public static function crear() {
        requireAdmin();
    
        try {
            $data = [
                'tit'  => $_POST['titulo'] ?? '',
                'sin'  => $_POST['sinopsis'] ?? '',
                'ima'  => $_POST['imagen'] ?: IMG_DEFAULT,
                'gen'  => $_POST['genero_id'] ?? null,
                'dir'  => $_POST['director_id'] ?? null,
                'est'  => $_POST['estudio_id'] ?? null,
                'fec'  => $_POST['fecha_estreno'] ?? '',
                'dur'  => $_POST['duracion'] ?? 0,
                'pre'  => $_POST['precio'] ?? 0,
                'prov' => $_POST['proveedor_id'] ?? null
            ];
    
            self::validarDatos($data);
    
            Pelicula::insertar($data);
    
            header("Location: " . BASE_PATH . "/peliculas?msg=insertado");
            exit;
    
        } catch (Exception $e) {
            error_log($e->getMessage());
            header("Location: " . BASE_PATH . "/peliculas?msg=error_campos&detalle=" . urlencode($e->getMessage()));
            exit;
        }
    }    

    /* ===============================
       ACTUALIZAR
       =============================== */
       public static function actualizar() {
        requireAdmin();
    
        try {
            $data = [
                'idp'  => $_POST['id'] ?? null,
                'tit'  => $_POST['titulo'] ?? '',
                'sin'  => $_POST['sinopsis'] ?? '',
                'ima'  => $_POST['imagen'] ?: IMG_DEFAULT,
                'gen'  => $_POST['genero_id'] ?? null,
                'dir'  => $_POST['director_id'] ?? null,
                'est'  => $_POST['estudio_id'] ?? null,
                'fec'  => $_POST['fecha_estreno'] ?? '',
                'dur'  => $_POST['duracion'] ?? 0,
                'pre'  => $_POST['precio'] ?? 0,
                'prov' => $_POST['proveedor_id'] ?? null
            ];
    
            self::validarDatos($data, true);
    
            Pelicula::actualizar($data);
    
            header("Location: " . BASE_PATH . "/peliculas?msg=actualizado");
            exit;
    
        } catch (Exception $e) {
            error_log($e->getMessage());
            header("Location: " . BASE_PATH . "/peliculas?msg=error_campos&detalle=" . urlencode($e->getMessage()));
            exit;
        }
    }    

    /* ===============================
       ELIMINAR
       =============================== */
    public static function eliminar() {
        requireAdmin();
        try {
            Pelicula::eliminar($_POST['id']);
            header("Location: " . BASE_PATH . "/peliculas?msg=eliminado");
            exit;

        } catch (Exception $e) {
            error_log($e->getMessage());
            header("Location: " . BASE_PATH . "/peliculas?msg=error_bd");
            exit;
        }
    }
}