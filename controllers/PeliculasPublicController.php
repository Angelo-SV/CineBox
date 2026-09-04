<?php

require_once __DIR__ . '/../models/Pelicula.php';
require_once __DIR__ . '/../models/Estudio.php';
require_once __DIR__ . '/../models/Genero.php';
require_once __DIR__ . '/../config/constants.php';
require_once __DIR__ . '/../config/Database.php';


class PeliculasPublicController
{
    /* ===============================
       HOME / INDEX (vista pública)
       =============================== */
    public static function index() {
        try {
            require __DIR__ . '/../views/public/index.php';

        } catch (Exception $e) {
            error_log($e->getMessage());
            header("Location: " . BASE_PATH . "/index?msg=error_bd");
            exit;
        }
    }

    /* ===============================
   LISTAR PELÍCULAS (AJAX)
   =============================== */
   public static function listar()
   {
       header('Content-Type: application/json');
       $idUsuario = $_SESSION['id'] ?? 0;
   
       try {
           $pagina  = max(1, intval($_GET['pagina'] ?? 1));
           $limite  = 6;
           $offset  = ($pagina - 1) * $limite;
   
           $genero  = intval($_GET['genero'] ?? 0);
           $estudio = intval($_GET['estudio'] ?? 0);
   
           // ⭐ TEXTO BÚSQUEDA
           $texto = trim($_GET['q'] ?? '');
           if ($texto === '') $texto = null;
   
           // ⭐ SOLO MI LISTA
           $soloLista = intval($_GET['solo_lista'] ?? 0);
           $soloAlquiladas = intval($_GET['solo_alquiladas'] ?? 0);
           $soloCarrito    = intval($_GET['solo_carrito'] ?? 0);

   
           // ⭐ TODO VA AL MISMO MODELO
           $resultado = Pelicula::listarPublicas([
               'limit'       => $limite,
               'offset'      => $offset,
               'genero'      => $genero,
               'estudio'     => $estudio,
               'usuario'     => $idUsuario,
               'texto'       => $texto,
               'solo_lista'  => $soloLista,
               'solo_alquiladas'  => $soloAlquiladas,
               'solo_carrito'  => $soloCarrito
           ]);
   
           echo json_encode([
               'ok'        => true,
               'peliculas' => $resultado['data'],
               'total'     => $resultado['total'],
               'pagina'    => $pagina,
               'limite'    => $limite
           ]);
   
       } catch (Exception $e) {
           http_response_code(500);
           echo json_encode([
               'ok'  => false,
               'msg' => 'Error al cargar películas'
           ]);
       }
   
       exit;
   }
   

    /* ===============================
       DETALLE DE PELÍCULA
       =============================== */
    public static function detalle()
    {
        $idUsuario = $_SESSION['id'] ?? 0;        
        $id = intval($_GET['id'] ?? 0);
    
        if ($id <= 0) {
            header('Location: ' . BASE_PATH . '/');
            exit;
        }
    
        $conn = null;
        try {
            $conn = conectaOracle();

            $pelicula = Pelicula::obtenerDetallePublico($id, $idUsuario, $conn);

            if (!$pelicula) {
                header('Location: ' . BASE_PATH . '/');
                exit;
            }

            $cast = Pelicula::obtenerCastPublico($id, $conn);
            require_once __DIR__ . '/../models/Review.php';
            $reviews = Review::listarPorPelicula($id, $conn);
            $promedio = Review::promedio($id, $conn);

            oci_close($conn);
            $conn = null;

            require __DIR__ . '/../views/public/pelicula_detalle.php';

        } catch (Exception $e) {
            error_log($e->getMessage());
            header('Location: ' . BASE_PATH . '/');
        } finally {
            if ($conn) oci_close($conn);
        }

        exit;
    }

    /* ===============================
    FILTROS (géneros y estudios)
    =============================== */
   public static function filtros()
   {
       header('Content-Type: application/json');
   
       try {
           echo json_encode([
               'ok' => true,
               'generos'  => Genero::all(),
               'estudios' => Estudio::all()
           ]);
       } catch (Exception $e) {
           http_response_code(500);
           echo json_encode([
               'ok' => false,
               'msg' => 'Error al cargar filtros'
           ]);
       }
   
       exit;
   }

   public static function detalleJson()
    {
        header('Content-Type: application/json');

        $idUsuario = $_SESSION['id'] ?? 0;
        $id = intval($_GET['id'] ?? 0);

        if ($id <= 0) {
            echo json_encode(['ok' => false]);
            return;
        }

        try {

            $pelicula = Pelicula::obtenerDetallePublico($id, $idUsuario);

            if (!$pelicula) {
                echo json_encode(['ok' => false]);
                return;
            }

            echo json_encode([
                'ok' => true,
                'pelicula' => $pelicula
            ]);

        } catch (Exception $e) {

            error_log($e->getMessage());

            echo json_encode([
                'ok' => false,
                'error' => 'Error cargando película'
            ]);
        }
    }

}   