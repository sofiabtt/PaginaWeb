<?php

require __DIR__ . '/../vendor/autoload.php';

$rutaLocal = __DIR__ . '/..';
$rutaServidor = __DIR__ . '/../..';

if (is_file($rutaLocal . '/.env')) {
    $dotenv = Dotenv\Dotenv::createImmutable($rutaLocal);
} else {
    $dotenv = Dotenv\Dotenv::createImmutable($rutaServidor);
}

$dotenv->load();

if (!function_exists('urlApp')) {
    // Devuelve la URL absoluta del sitio a partir de APP_URL.
    function urlApp(string $ruta = ''): string
    {
        $base = rtrim($_ENV['APP_URL'] ?? '', '/');

        if ($base === '') {
            $https = (!empty($_SERVER['HTTPS']) && $_SERVER['HTTPS'] !== 'off');
            $base = ($https ? 'https://' : 'http://') . ($_SERVER['HTTP_HOST'] ?? 'localhost');
        }

        return $base . '/' . ltrim($ruta, '/');
    }
}

$puerto = (int) ($_ENV['DB_PORT'] ?? 3306);

$conexion = new mysqli(
    $_ENV['DB_HOST'],
    $_ENV['DB_USER'],
    $_ENV['DB_PASS'],
    $_ENV['DB_NAME'],
    $puerto
);

if ($conexion->connect_error) {

    die("Error de conexión: " . $conexion->connect_error);

}

$conexion->set_charset("utf8mb4");

?>