<?php

require __DIR__ . '/../vendor/autoload.php';

$dotenv = Dotenv\Dotenv::createImmutable(__DIR__ . '/../..');
$dotenv->load();

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