<?php
/* Marca como EXPIRADA cualquier transacción cuya fecha de expiración ya
   pasó. Pensado para ejecutarse por cron (línea de comandos), no por HTTP:
   así no queda expuesta como una URL pública sin autenticación.

   Uso en crontab (cada hora, por ejemplo):
   0 * * * * php /var/www/html/cron/expirar_alquileres.php >> /var/log/cinebox_cron.log 2>&1
*/

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
