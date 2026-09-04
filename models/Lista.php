<?php
require_once __DIR__ . '/../config/Database.php';

class Lista {

    public static function toggle($idUsuario, $idPelicula) {

        $db = conectaOracle();

        $sql = "BEGIN TOGGLE_LISTA_ITEM(:u, :p, :fav); END;";

        $stmt = oci_parse($db, $sql);

        oci_bind_by_name($stmt, ":u", $idUsuario);
        oci_bind_by_name($stmt, ":p", $idPelicula);
        oci_bind_by_name($stmt, ":fav", $favorito, 1);

        oci_execute($stmt);

        oci_close($db);

        return $favorito == 1;
    }

}