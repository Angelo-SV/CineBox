<?php
/* Marca como EXPIRADA cualquier transacción cuya fecha de expiración ya
   pasó. Pensado para ejecutarse por cron (línea de comandos), no por HTTP:
   así no queda expuesta como una URL pública sin autenticación.

   Uso en crontab (cada hora, por ejemplo):
   0 * * * * php /var/www/html/cron/expirar_alquileres.php >> /var/log/cinebox_cron.log 2>&1
*/

/* Un cron corre como un proceso de PHP-CLI aparte, sin nada de lo que
   configuramos para PHP-FPM (incluido TNS_ADMIN en /etc/php-fpm.d/www.conf)
   — cada contexto (httpd, php-fpm, cli) tiene su propio entorno. En Linux,
   sin TNS_ADMIN, el cliente de Oracle no encuentra el tnsnames.ora del
   Wallet y falla con ORA-12154. Se fija aquí mismo, igual que en el pool
   de PHP-FPM, solo si hace falta y solo en Linux (en Windows, desarrollo
   local, el cliente de Oracle ya resuelve esto por su cuenta). */
if (DIRECTORY_SEPARATOR === '/' && getenv('TNS_ADMIN') === false) {
    putenv('TNS_ADMIN=/opt/oracle/wallet');
}

require_once __DIR__ . '/../config/Database.php';
require_once __DIR__ . '/../models/Transaccion.php';

$inicio = date('Y-m-d H:i:s');

try {
    Transaccion::expirarAlquileres();
    echo "[$inicio] OK: alquileres vencidos marcados como EXPIRADA.\n";
} catch (Exception $e) {
    echo "[$inicio] ERROR: " . $e->getMessage() . "\n";
    exit(1);
}
