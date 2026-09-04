<?php
require_once __DIR__ . '/../config/Database.php';

class Review {

    /* ===============================
       LISTAR POR PELÍCULA
       =============================== */
    public static function listarPorPelicula($peliculaId, $conexion = null) {

        $c = $conexion ?? conectaOracle();

        $sql = "BEGIN CONSULTAR_REVIEWS_PELICULA(:id, :cur); END;";
        $stmt = oci_parse($c, $sql);
        $cur = oci_new_cursor($c);

        oci_bind_by_name($stmt, ":id", $peliculaId);
        oci_bind_by_name($stmt, ":cur", $cur, -1, OCI_B_CURSOR);

        oci_execute($stmt);
        oci_execute($cur);

        $data = [];
        while ($row = oci_fetch_assoc($cur)) {
            $data[] = $row;
        }

        if (!$conexion) oci_close($c);
        return $data;
    }

    /* ===============================
       INSERTAR
       =============================== */
    public static function insertar($usuario, $pelicula, $calif, $coment, $conexion = null) {

        $c = $conexion ?? conectaOracle();

        $sql = "BEGIN INSERTAR_REVIEW(:u,:p,:c,:m); END;";
        $stmt = oci_parse($c, $sql);

        oci_bind_by_name($stmt, ":u", $usuario);
        oci_bind_by_name($stmt, ":p", $pelicula);
        oci_bind_by_name($stmt, ":c", $calif);
        oci_bind_by_name($stmt, ":m", $coment);

        if (!oci_execute($stmt)) {
            $e = oci_error($stmt);
            if (!$conexion) oci_close($c);
            throw new Exception($e['message']);
        }

        if (!$conexion) oci_close($c);
    }

    public static function promedio($peliculaId, $conexion = null)
    {
        $c = $conexion ?? conectaOracle();

        $sql = "BEGIN :res := PROMEDIO_REVIEW_PELICULA(:id); END;";
        $stmt = oci_parse($c,$sql);

        oci_bind_by_name($stmt,":res",$res,32);
        oci_bind_by_name($stmt,":id",$peliculaId);

        oci_execute($stmt);
        if (!$conexion) oci_close($c);

        return floatval($res);
    }

    public static function usuarioYaComento($usuarioId, $peliculaId, $conexion = null)
    {
        $c = $conexion ?? conectaOracle();

        $sql = "SELECT COUNT(*) C
                FROM REVIEWS
                WHERE ID_USUARIO = :u
                AND ID_PELICULA = :p";

        $stmt = oci_parse($c,$sql);
        oci_bind_by_name($stmt,":u",$usuarioId);
        oci_bind_by_name($stmt,":p",$peliculaId);

        oci_execute($stmt);
        $row = oci_fetch_assoc($stmt);

        if (!$conexion) oci_close($c);
        return $row['C'] > 0;
    }

}
