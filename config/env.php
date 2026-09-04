<?php
/* Carga variables desde .env (si existe) hacia un caché local de esta
   petición ($GLOBALS), en vez de escribirlas con putenv().
   putenv() modifica el entorno de TODO el proceso de PHP, no solo el de
   esta petición. En un servidor donde varias peticiones comparten un mismo
   proceso con hilos (como el Apache de XAMPP en Windows), dos peticiones
   concurrentes llamando loadEnv() al mismo tiempo pueden pisarse: una lee
   la tabla de entorno mientras la otra la está escribiendo, y obtiene un
   valor vacío o nulo (se observó así con oci_connect() recibiendo un
   usuario nulo bajo carga concurrente). Guardar los valores en un array
   propio de este archivo evita esa condición de carrera por completo. */
function loadEnv(string $path): array
{
    $values = [];
    if (!is_file($path)) {
        return $values;
    }

    foreach (file($path, FILE_IGNORE_NEW_LINES | FILE_SKIP_EMPTY_LINES) as $line) {
        $line = trim($line);
        if ($line === '' || str_starts_with($line, '#')) {
            continue;
        }

        [$key, $value] = array_pad(explode('=', $line, 2), 2, '');
        $key = trim($key);
        $value = trim($value, " \t\n\r\0\x0B\"'");

        if ($key !== '') {
            $values[$key] = $value;
        }
    }

    return $values;
}

$GLOBALS['__cinebox_env'] = loadEnv(__DIR__ . '/../.env');

function env(string $key, $default = null)
{
    if (array_key_exists($key, $GLOBALS['__cinebox_env'])) {
        return $GLOBALS['__cinebox_env'][$key];
    }

    // Variables definidas realmente a nivel de servidor (no en .env)
    $value = getenv($key);
    return $value !== false ? $value : $default;
}
