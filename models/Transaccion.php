<?php

require_once __DIR__ . '/../config/Database.php';

class Transaccion
{

    /* =========================================
       CREAR ALQUILER
       ========================================= */
       public static function crearAlquiler($idUsuario, $idPelicula, $precio, $metodoPagoId = null)
       {
           $c = conectaOracle();
       
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
               throw new Exception($e['message']);
           }
       
           oci_close($c);
       
           return $transaccionId;
       }       

    /* =========================================
       VALIDAR SI TIENE ALQUILER ACTIVO
       ========================================= */
    public static function tieneAlquilerActivo($idUsuario, $idPelicula)
    {
        $c = conectaOracle();

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

        oci_close($c);

        return intval($resultado) === 1;
    }


    /* =========================================
       OBTENER ALQUILER ACTIVO
       ========================================= */
    public static function obtenerAlquilerActivo($idUsuario, $idPelicula)
    {
        $c = conectaOracle();

        $sql = "BEGIN OBTENER_ALQUILER_ACTIVO(
                    :usuario,
                    :pelicula,
                    :cursor
                ); END;";

        $stmt = oci_parse($c, $sql);
        $cur  = oci_new_cursor($c);

        oci_bind_by_name($stmt, ":usuario", $idUsuario);
        oci_bind_by_name($stmt, ":pelicula", $idPelicula);
        oci_bind_by_name($stmt, ":cursor", $cur, -1, OCI_B_CURSOR);

        oci_execute($stmt);
        oci_execute($cur);

        $row = oci_fetch_assoc($cur);

        oci_close($c);

        return $row ?: null;
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