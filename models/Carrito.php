<?php
require_once __DIR__ . '/../config/Database.php';
class Carrito
{
    /* ===============================
       AGREGAR ITEM
       =============================== */
    public static function agregar($idUsuario, $idPelicula)
    {
        $c = conectaOracle();

        $sql = "BEGIN SP_CARRITO_AGREGAR_ITEM(:u, :p); END;";
        $stmt = oci_parse($c, $sql);

        oci_bind_by_name($stmt, ":u", $idUsuario);
        oci_bind_by_name($stmt, ":p", $idPelicula);

        $ok = oci_execute($stmt);

        oci_close($c);
        return $ok;
    }

    /* ===============================
       ELIMINAR ITEM
       =============================== */
    public static function eliminar($idUsuario, $idPelicula)
    {
        $c = conectaOracle();

        $sql = "BEGIN SP_CARRITO_ELIMINAR_ITEM(:u, :p); END;";
        $stmt = oci_parse($c, $sql);

        oci_bind_by_name($stmt, ":u", $idUsuario);
        oci_bind_by_name($stmt, ":p", $idPelicula);

        $ok = oci_execute($stmt);

        oci_close($c);
        return $ok;
    }

    /* ===============================
       LISTAR CARRITO
       =============================== */
    public static function listar($idUsuario)
    {
        $c = conectaOracle();

        $sql = "BEGIN SP_CARRITO_LISTAR(:u, :cur); END;";
        $stmt = oci_parse($c, $sql);
        $cur  = oci_new_cursor($c);

        oci_bind_by_name($stmt, ":u", $idUsuario);
        oci_bind_by_name($stmt, ":cur", $cur, -1, OCI_B_CURSOR);

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
       TOTAL CARRITO
       =============================== */
    public static function total($idUsuario)
    {
        $c = conectaOracle();

        $sql = "BEGIN SP_CARRITO_TOTAL(:u, :t); END;";
        $stmt = oci_parse($c, $sql);

        oci_bind_by_name($stmt, ":u", $idUsuario);
        oci_bind_by_name($stmt, ":t", $total, 32);

        oci_execute($stmt);

        oci_close($c);
        return floatval($total);
    }

    /* ===============================
       VACIAR
       =============================== */
    public static function vaciar($idUsuario)
    {
        $c = conectaOracle();

        $sql = "BEGIN SP_CARRITO_VACIAR(:u); END;";
        $stmt = oci_parse($c, $sql);

        oci_bind_by_name($stmt, ":u", $idUsuario);

        $ok = oci_execute($stmt);

        oci_close($c);
        return $ok;
    }

    /* ===============================
       CERRAR CARRITO
       =============================== */
    public static function cerrar($idUsuario)
    {
        $c = conectaOracle();

        $sql = "BEGIN SP_CARRITO_CERRAR(:u); END;";
        $stmt = oci_parse($c, $sql);

        oci_bind_by_name($stmt, ":u", $idUsuario);

        $ok = oci_execute($stmt);

        oci_close($c);
        return $ok;
    }
}