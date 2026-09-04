<?php

require_once __DIR__ . '/../config/Database.php';

class Transaccion
{

    /* =========================================
       CREAR ALQUILER
       ========================================= */
       public static function crearAlquiler($idUsuario, $idPelicula, $precio, $metodoPagoId = null, $conexion = null)
       {
           $c = $conexion ?? conectaOracle();

           $sql = "BEGIN CREAR_ALQUILER(
                       :usuario,
                       :pelicula,
                       :precio,
                       :metodo,
                       :transaccion_id
                   ); END;";

           $stmt = oci_parse($c, $sql);

           oci_bind_by_name($stmt, ":usuario", $idUsuario);
           oci_bind_by_name($stmt, ":pelicula", $idPelicula);
           oci_bind_by_name($stmt, ":precio", $precio);
           oci_bind_by_name($stmt, ":metodo", $metodoPagoId);

           // 🔵 parámetro OUT
           oci_bind_by_name($stmt, ":transaccion_id", $transaccionId, 32);

           $ok = oci_execute($stmt);

           if (!$ok) {
               $e = oci_error($stmt);
               if (!$conexion) oci_close($c);
               throw new Exception($e['message']);
           }

           if (!$conexion) oci_close($c);

           return $transaccionId;
       }

    /* =========================================
       VALIDAR SI TIENE ALQUILER ACTIVO
       ========================================= */
    public static function tieneAlquilerActivo($idUsuario, $idPelicula, $conexion = null)
    {
        $c = $conexion ?? conectaOracle();

        $sql = "BEGIN VALIDAR_ALQUILER_ACTIVO(
                    :usuario,
                    :pelicula,
                    :resultado
                ); END;";

        $stmt = oci_parse($c, $sql);

        oci_bind_by_name($stmt, ":usuario", $idUsuario);
        oci_bind_by_name($stmt, ":pelicula", $idPelicula);
        oci_bind_by_name($stmt, ":resultado", $resultado, 32);

        oci_execute($stmt);

        if (!$conexion) oci_close($c);

        return intval($resultado) === 1;
    }


    /* =========================================
       EXPIRAR ALQUILERES (job manual o cron)
       ========================================= */
    public static function expirarAlquileres()
    {
        $c = conectaOracle();

        $sql = "BEGIN EXPIRAR_ALQUILERES; END;";
        $stmt = oci_parse($c, $sql);

        oci_execute($stmt);

        oci_close($c);
    }


    /* =========================================
       HISTORIAL DE ALQUILERES
       ========================================= */
    public static function obtenerHistorial($idUsuario)
    {
        $c = conectaOracle();

        $sql = "BEGIN OBTENER_HISTORIAL_USUARIO(
                    :usuario,
                    :cursor
                ); END;";

        $stmt = oci_parse($c, $sql);
        $cur  = oci_new_cursor($c);

        oci_bind_by_name($stmt, ":usuario", $idUsuario);
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


    /* =========================================
       CONTAR ALQUILERES ACTIVOS POR USUARIO
       (para el panel admin: saber a qué usuarios
       mostrarles habilitado el botón "Ver alquileres")
       ========================================= */
    public static function contarActivosPorUsuario()
    {
        $c = conectaOracle();

        $sql = "SELECT ID_USUARIO, COUNT(*) AS CANTIDAD
                FROM TRANSACCIONES_USUARIOS
                WHERE ESTADO = 'ACTIVA' AND FECHA_EXPIRACION > SYSDATE
                GROUP BY ID_USUARIO";

        $stmt = oci_parse($c, $sql);
        oci_execute($stmt);

        $conteos = [];
        while ($row = oci_fetch_assoc($stmt)) {
            $conteos[(int) $row['ID_USUARIO']] = (int) $row['CANTIDAD'];
        }

        oci_close($c);

        return $conteos;
    }


    /* =========================================
       MÉTODOS DE PAGO
       ========================================= */
    public static function listarMetodosPago()
    {
        $c = conectaOracle();

        $sql = "SELECT ID_METODO, NOMBRE
                FROM METODOS_PAGO
                ORDER BY NOMBRE";

        $stmt = oci_parse($c, $sql);
        oci_execute($stmt);

        $data = [];

        while ($row = oci_fetch_assoc($stmt)) {
            $data[] = $row;
        }

        oci_close($c);

        return $data;
    }

}