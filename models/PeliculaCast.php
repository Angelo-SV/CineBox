<?php
require_once __DIR__ . '/../config/Database.php';

class PeliculaCast {

    /* ===============================
       LISTAR
       =============================== */
    public static function obtenerPorPelicula($peliculaId) {
        $c = conectaOracle();
        $cast = [];

        $sql = "BEGIN CONSULTAR_CAST_PELICULA(:pel, :cur); END;";
        $stmt = oci_parse($c, $sql);
        $cur = oci_new_cursor($c);

        oci_bind_by_name($stmt, ":pel", $peliculaId);
        oci_bind_by_name($stmt, ":cur", $cur, -1, OCI_B_CURSOR);

        oci_execute($stmt);
        oci_execute($cur);

        while ($row = oci_fetch_assoc($cur)) {
            $cast[] = $row;
        }

        oci_free_statement($stmt);
        oci_free_statement($cur);
        oci_close($c);

        return $cast;
    }

    /* ===============================
    GUARDAR (SYNC TOTAL)
    =============================== */
    public static function guardarCast($peliculaId, $actores, $protagonistas) {

        $c = conectaOracle();
        $protagonistas = array_map('intval', $protagonistas);

        if (empty($actores)) {
            throw new Exception("Debe agregar al menos un actor.");
        }

        if (empty($protagonistas)) {
            throw new Exception("Debe seleccionar al menos un protagonista.");
        }

        try {
            // 1️⃣ Cast actual en BD
            $actual = self::obtenerPorPelicula($peliculaId);
            $actualIds = array_column($actual, 'ID_ACTOR');

            $enviadosIds = [];

            foreach ($actores as $i => $actor) {

                $idActor  = intval($actor['id']);
                $original = intval($actor['original_id']);
                $rol      = trim($actor['rol']);

                if (!$idActor) {
                    throw new Exception("Actor inválido.");
                }

                $esProta = in_array($idActor, $protagonistas, true) ? 1 : 0;

                $enviadosIds[] = $idActor;            

                if ($original === 0) {
                    self::insertarActor($c, $peliculaId, $idActor, $rol, $esProta);
                } else {
                    self::actualizarActor($c, $peliculaId, $idActor, $rol, $esProta);
                }
            }

            // 2️⃣ Eliminados
            foreach ($actualIds as $idActor) {
                if (!in_array($idActor, $enviadosIds)) {
                    self::eliminarActorDirecto($c, $peliculaId, $idActor);
                }
            }

            oci_close($c);
            return true;

        } catch (Exception $e) {
            oci_close($c);
            throw $e;
        }
    }


    /* ===============================
       HELPERS
       =============================== */
    private static function insertarActor($c, $pel, $act, $rol, $prot) {
        $stmt = oci_parse($c, "BEGIN INSERTAR_PELICULA_ACTOR(:p,:a,:r,:e); END;");
        oci_bind_by_name($stmt, ":p", $pel);
        oci_bind_by_name($stmt, ":a", $act);
        oci_bind_by_name($stmt, ":r", $rol);
        oci_bind_by_name($stmt, ":e", $prot);

        if (!oci_execute($stmt)) {
            throw new Exception(oci_error($stmt)['message']);
        }
    }

    private static function actualizarActor($c, $pel, $act, $rol, $prot) {
        $stmt = oci_parse($c, "BEGIN ACTUALIZAR_PELICULA_ACTOR(:p,:a,:r,:e); END;");
        oci_bind_by_name($stmt, ":p", $pel);
        oci_bind_by_name($stmt, ":a", $act);
        oci_bind_by_name($stmt, ":r", $rol);
        oci_bind_by_name($stmt, ":e", $prot);

        if (!oci_execute($stmt)) {
            throw new Exception(oci_error($stmt)['message']);
        }
    }

    private static function eliminarActorDirecto($c, $pel, $act) {
        $stmt = oci_parse($c, "BEGIN ELIMINAR_PELICULA_ACTOR(:p,:a); END;");
        oci_bind_by_name($stmt, ":p", $pel);
        oci_bind_by_name($stmt, ":a", $act);
        oci_execute($stmt);
    }
}