<?php

class Usuario
{
    /* ===============================
       OBTENER POR CORREO (LOGIN)
       =============================== */
    public static function obtenerPorCorreo(string $correo): ?array
    {
        $conn = db();
        $sql = "BEGIN :cursor := CONSULTA_CREDENCIALES_CORREO(:correo); END;";
        $stmt = oci_parse($conn, $sql);
        $cursor = oci_new_cursor($conn);

        oci_bind_by_name($stmt, ':correo', $correo);
        oci_bind_by_name($stmt, ':cursor', $cursor, -1, OCI_B_CURSOR);

        oci_execute($stmt);
        oci_execute($cursor);

        $user = oci_fetch_assoc($cursor);

        oci_free_statement($cursor);
        oci_free_statement($stmt);
        oci_close($conn);

        return $user ?: null;
    }

    /* ===============================
       LISTAR USUARIOS
       =============================== */
    public static function listar(): array
    {
        $conn = db();
        $sql = "BEGIN CONSULTAR_USUARIOS(:cursor); END;";
        $stmt = oci_parse($conn, $sql);
        $cursor = oci_new_cursor($conn);

        oci_bind_by_name($stmt, ":cursor", $cursor, -1, OCI_B_CURSOR);

        oci_execute($stmt);
        oci_execute($cursor);

        $usuarios = [];
        while ($row = oci_fetch_assoc($cursor)) {
            $usuarios[] = $row;
        }

        oci_free_statement($cursor);
        oci_free_statement($stmt);
        oci_close($conn);

        return $usuarios;
    }

    /* ===============================
       INSERTAR
       =============================== */
    public static function insertar(array $data): void
    {
        $conn = db();
        $sql = "BEGIN INSERTAR_USUARIO(
            :nom, :ap, :am, :cor, :tel, :pass, :rol
        ); END;";

        $stmt = oci_parse($conn, $sql);

        oci_bind_by_name($stmt, ":nom",  $data['nombre']);
        oci_bind_by_name($stmt, ":ap",   $data['apellidoPaterno']);
        oci_bind_by_name($stmt, ":am",   $data['apellidoMaterno']);
        oci_bind_by_name($stmt, ":cor",  $data['correo']);
        oci_bind_by_name($stmt, ":tel",  $data['telefono']);
        oci_bind_by_name($stmt, ":pass", $data['password']);
        oci_bind_by_name($stmt, ":rol",  $data['rol']);

        if (!oci_execute($stmt)) {
            throw new Exception(oci_error($stmt)['message']);
        }

        oci_free_statement($stmt);
        oci_close($conn);
    }

    /* ===============================
       ACTUALIZAR
       =============================== */
    public static function actualizar(array $data): void
    {
        $conn = db();
        $sql = "BEGIN ACTUALIZAR_USUARIO(
            :idu, :nom, :ap, :am, :cor, :tel, :pass, :rol
        ); END;";

        $stmt = oci_parse($conn, $sql);

        oci_bind_by_name($stmt, ":idu",  $data['id']);
        oci_bind_by_name($stmt, ":nom",  $data['nombre']);
        oci_bind_by_name($stmt, ":ap",   $data['apellidoPaterno']);
        oci_bind_by_name($stmt, ":am",   $data['apellidoMaterno']);
        oci_bind_by_name($stmt, ":cor",  $data['correo']);
        oci_bind_by_name($stmt, ":tel",  $data['telefono']);
        oci_bind_by_name($stmt, ":pass", $data['password']);
        oci_bind_by_name($stmt, ":rol",  $data['rol']);

        if (!oci_execute($stmt)) {
            throw new Exception(oci_error($stmt)['message']);
        }

        oci_free_statement($stmt);
        oci_close($conn);
    }

    /* ===============================
       ELIMINAR
       =============================== */
    public static function eliminar(int $id): void
    {
        $conn = db();
        $sql = "BEGIN ELIMINAR_USUARIO(:idu); END;";
        $stmt = oci_parse($conn, $sql);

        oci_bind_by_name($stmt, ":idu", $id);

        if (!oci_execute($stmt)) {
            throw new Exception(oci_error($stmt)['message']);
        }

        oci_free_statement($stmt);
        oci_close($conn);
    }
}