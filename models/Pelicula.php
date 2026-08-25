<?php
require_once __DIR__ . '/../config/Database.php';

class Pelicula {

    /* ===============================
       LISTAR
       =============================== */
    public static function listar() {

        $c = conectaOracle();
        $stmt = oci_parse($c, "BEGIN CONSULTAR_PELICULAS(:cursor); END;");
        $cur  = oci_new_cursor($c);

        oci_bind_by_name($stmt, ":cursor", $cur, -1, OCI_B_CURSOR);

        if (!oci_execute($stmt)) {
            $e = oci_error($stmt);
            oci_close($c);
            throw new Exception($e['message']);
        }

        if (!oci_execute($cur)) {
            $e = oci_error($cur);
            oci_close($c);
            throw new Exception($e['message']);
        }

        $data = [];
        while ($row = oci_fetch_assoc($cur)) {
            $data[] = $row;
        }

        oci_close($c);
        return $data;
    }

    /* ===============================
       INSERTAR
       =============================== */
    public static function insertar($data) {

        $c = conectaOracle();
        $sql = "BEGIN INSERTAR_PELICULA(
            :tit, :sin, :ima, :gen, :dir, :est,
            TO_DATE(:fec,'YYYY-MM-DD'),
            :dur, :pre, :prov
        ); END;";

        $stmt = oci_parse($c, $sql);

        foreach ($data as $k => $v) {
            oci_bind_by_name($stmt, ":$k", $data[$k]);
        }

        if (!oci_execute($stmt)) {
            $e = oci_error($stmt);
            oci_close($c);
            throw new Exception($e['message']);
        }

        oci_close($c);
    }

    /* ===============================
       ACTUALIZAR
       =============================== */
    public static function actualizar($data) {

        $c = conectaOracle();
        $sql = "BEGIN ACTUALIZAR_PELICULA(
            :idp, :tit, :sin, :ima, :gen, :dir, :est,
            TO_DATE(:fec,'YYYY-MM-DD'),
            :dur, :pre, :prov
        ); END;";

        $stmt = oci_parse($c, $sql);

        foreach ($data as $k => $v) {
            oci_bind_by_name($stmt, ":$k", $data[$k]);
        }

        if (!oci_execute($stmt)) {
            $e = oci_error($stmt);
            oci_close($c);
            throw new Exception($e['message']);
        }

        oci_close($c);
    }

    /* ===============================
       ELIMINAR
       =============================== */
    public static function eliminar($id) {

        $c = conectaOracle();
        $stmt = oci_parse($c, "BEGIN ELIMINAR_PELICULA(:id); END;");
        oci_bind_by_name($stmt, ":id", $id);

        if (!oci_execute($stmt)) {
            $e = oci_error($stmt);
            oci_close($c);
            throw new Exception($e['message']);
        }
        oci_close($c);
    }

/* ===============================
   LISTAR PÚBLICAS (PAGINADO)
   =============================== */
   public static function listarPublicas($params)
    {
        $c = conectaOracle();

        $limit   = intval($params['limit']);
        $offset  = intval($params['offset']);
        $genero  = intval($params['genero']);
        $estudio = intval($params['estudio']);
        $usuario = intval($params['usuario']);
        $texto   = $params['texto'] ?? null;
        $soloLista = intval($params['solo_lista'] ?? 0);
        $soloAlquiladas = intval($params['solo_alquiladas'] ?? 0);
        $soloCarrito = intval($params['solo_carrito'] ?? 0);

        /* ========= LISTADO ========= */

        $sql = "BEGIN CONSULTAR_PELICULAS_PUBLICAS(
            :limite,
            :offset,
            :genero,
            :estudio,
            :usuario,
            :texto,
            :solo_lista,
            :solo_alquiladas,
            :solo_carrito,
            :cursor
        ); END;";

        $stmt = oci_parse($c, $sql);
        $cur  = oci_new_cursor($c);

        oci_bind_by_name($stmt, ":limite",  $limit);
        oci_bind_by_name($stmt, ":offset",  $offset);
        oci_bind_by_name($stmt, ":genero",  $genero);
        oci_bind_by_name($stmt, ":estudio", $estudio);
        oci_bind_by_name($stmt, ":usuario", $usuario);
        oci_bind_by_name($stmt, ":texto",   $texto);
        oci_bind_by_name($stmt, ":solo_lista", $soloLista);
        oci_bind_by_name($stmt, ":solo_alquiladas", $soloAlquiladas);
        oci_bind_by_name($stmt, ":solo_carrito", $soloCarrito);
        oci_bind_by_name($stmt, ":cursor",  $cur, -1, OCI_B_CURSOR);

        oci_execute($stmt);
        oci_execute($cur);

        $data = [];
        while ($row = oci_fetch_assoc($cur)) {
            $data[] = $row;
        }

        /* ========= COUNT ========= */

        $sqlTotal = "BEGIN CONTAR_PELICULAS_PUBLICAS(
            :genero,
            :estudio,
            :usuario,
            :texto,
            :solo_lista,
            :solo_alquiladas,
            :solo_carrito,
            :total
        ); END;";
        

        $stmtTotal = oci_parse($c, $sqlTotal);

        oci_bind_by_name($stmtTotal, ":genero", $genero);
        oci_bind_by_name($stmtTotal, ":estudio", $estudio);
        oci_bind_by_name($stmtTotal, ":usuario", $usuario);
        oci_bind_by_name($stmtTotal, ":texto", $texto);
        oci_bind_by_name($stmtTotal, ":solo_lista", $soloLista);
        oci_bind_by_name($stmtTotal, ":solo_alquiladas", $soloAlquiladas);
        oci_bind_by_name($stmtTotal, ":solo_carrito", $soloCarrito);
        oci_bind_by_name($stmtTotal, ":total", $total, 32);

        oci_execute($stmtTotal);

        oci_close($c);

        return [
            'data'  => $data,
            'total' => intval($total)
        ];
    }

   
   /* ===============================
      DETALLE PÚBLICO (FUTURO)
      =============================== */
      public static function obtenerDetallePublico($id, $usuarioId = 0)
      {
          $c = conectaOracle();
      
          $sql = "BEGIN CONSULTAR_PELICULA_DETALLE(:id, :usr, :cursor); END;";
          $stmt = oci_parse($c, $sql);
          $cur  = oci_new_cursor($c);
      
          oci_bind_by_name($stmt, ":id", $id);
          oci_bind_by_name($stmt, ":usr", $usuarioId);
          oci_bind_by_name($stmt, ":cursor", $cur, -1, OCI_B_CURSOR);
      
          oci_execute($stmt);
          oci_execute($cur);
      
          $row = oci_fetch_assoc($cur);
      
          oci_close($c);
          return $row ?: null;
      }        

   public static function obtenerCastPublico($id)
    {
        $c = conectaOracle();

        $sql = "BEGIN CONSULTAR_CAST_PELICULA(:id, :cursor); END;";
        $stmt = oci_parse($c, $sql);
        $cur  = oci_new_cursor($c);

        oci_bind_by_name($stmt, ":id", $id);
        oci_bind_by_name($stmt, ":cursor", $cur, -1, OCI_B_CURSOR);

        oci_execute($stmt);
        oci_execute($cur);

        $data = [];
        while ($row = oci_fetch_assoc($cur)) {
            $data[] = $row;
        }

        oci_close($c);
        return $data;
    }

    public static function buscarPublicas($texto, $limite, $offset, $genero, $estudio, $usuario)
    {
        $c = conectaOracle();

        $sql = "BEGIN BUSCAR_PELICULAS_PUBLICAS(
                    :texto,
                    :limite,
                    :offset,
                    :genero,
                    :estudio,
                    :usuario,
                    :cursor
                ); END;";

        $stmt = oci_parse($c, $sql);
        $cur  = oci_new_cursor($c);

        oci_bind_by_name($stmt, ":texto", $texto);
        oci_bind_by_name($stmt, ":limite", $limite);
        oci_bind_by_name($stmt, ":offset", $offset);
        oci_bind_by_name($stmt, ":genero", $genero);
        oci_bind_by_name($stmt, ":estudio", $estudio);
        oci_bind_by_name($stmt, ":usuario", $usuario);
        oci_bind_by_name($stmt, ":cursor", $cur, -1, OCI_B_CURSOR);

        oci_execute($stmt);
        oci_execute($cur);

        $data = [];
        while ($row = oci_fetch_assoc($cur)) {
            $data[] = $row;
        }

        oci_close($c);
        return $data;
    }

    /* ===============================
   OBTENER POR ID (ligero)
   =============================== */
    public static function obtenerPorId($id)
    {
        $c = conectaOracle();

        $sql = "SELECT ID_PELICULA, TITULO, PRECIO
                FROM PELICULAS
                WHERE ID_PELICULA = :id";

        $stmt = oci_parse($c, $sql);
        oci_bind_by_name($stmt, ":id", $id);

        if (!oci_execute($stmt)) {
            $e = oci_error($stmt);
            oci_close($c);
            throw new Exception($e['message']);
        }

        $row = oci_fetch_assoc($stmt);

        oci_close($c);

        return $row ?: null;
    }


}