<?php
require_once __DIR__ . '/../config/Database.php';
class Carrito
{
    /* Cada método acepta una conexión ya abierta como último parámetro
       opcional. Si no se pasa ninguna, el método abre y cierra la suya
       propia (comportamiento de antes, sin cambios para quien ya lo usa
       así). Esto permite que un flujo que hace varias operaciones seguidas
       (como el toggle de agregar/quitar del carrito) las haga todas sobre
       una sola conexión en vez de abrir una conexión nueva —con su propio
       handshake contra el Wallet— por cada llamada. */

    /* ===============================
       AGREGAR ITEM
       =============================== */
    public static function agregar($idUsuario, $idPelicula, $conexion = null)
    {
        $c = $conexion ?? conectaOracle();

        $sql = "BEGIN SP_CARRITO_AGREGAR_ITEM(:u, :p); END;";
        $stmt = oci_parse($c, $sql);

        oci_bind_by_name($stmt, ":u", $idUsuario);
        oci_bind_by_name($stmt, ":p", $idPelicula);

        $ok = oci_execute($stmt);

        if (!$conexion) oci_close($c);
        return $ok;
    }

    /* ===============================
       ELIMINAR ITEM
       =============================== */
    public static function eliminar($idUsuario, $idPelicula, $conexion = null)
    {
        $c = $conexion ?? conectaOracle();

        $sql = "BEGIN SP_CARRITO_ELIMINAR_ITEM(:u, :p); END;";
        $stmt = oci_parse($c, $sql);

        oci_bind_by_name($stmt, ":u", $idUsuario);
        oci_bind_by_name($stmt, ":p", $idPelicula);

        $ok = oci_execute($stmt);

        if (!$conexion) oci_close($c);
        return $ok;
    }

    /* ===============================
       LISTAR CARRITO
       =============================== */
    public static function listar($idUsuario, $conexion = null)
    {
        $c = $conexion ?? conectaOracle();

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

        if (!$conexion) oci_close($c);
        return $data;
    }

    /* ===============================
       TOTAL CARRITO
       =============================== */
    public static function total($idUsuario, $conexion = null)
    {
        $c = $conexion ?? conectaOracle();

        $sql = "BEGIN SP_CARRITO_TOTAL(:u, :t); END;";
        $stmt = oci_parse($c, $sql);

        oci_bind_by_name($stmt, ":u", $idUsuario);
        oci_bind_by_name($stmt, ":t", $total, 32);

        oci_execute($stmt);

        if (!$conexion) oci_close($c);
        return floatval($total);
    }

    /* ===============================
       VACIAR
       =============================== */
    public static function vaciar($idUsuario, $conexion = null)
    {
        $c = $conexion ?? conectaOracle();

        $sql = "BEGIN SP_CARRITO_VACIAR(:u); END;";
        $stmt = oci_parse($c, $sql);

        oci_bind_by_name($stmt, ":u", $idUsuario);

        $ok = oci_execute($stmt);

        if (!$conexion) oci_close($c);
        return $ok;
    }

    /* ===============================
       CERRAR CARRITO
       =============================== */
    public static function cerrar($idUsuario, $conexion = null)
    {
        $c = $conexion ?? conectaOracle();

        $sql = "BEGIN SP_CARRITO_CERRAR(:u); END;";
        $stmt = oci_parse($c, $sql);

        oci_bind_by_name($stmt, ":u", $idUsuario);

        $ok = oci_execute($stmt);

        if (!$conexion) oci_close($c);
        return $ok;
    }
}
