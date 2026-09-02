<?php

require __DIR__ . '/../vendor/autoload.php';

$dotenv = Dotenv\Dotenv::createImmutable(__DIR__ . '/..');
$dotenv->load();

$puerto = $_ENV['DB_PORT'] ?? 3306;

$conexion = new mysqli(
    "127.0.0.1",
    "root",
    "",
    "aerolineas",
    $puerto
);

if ($conexion->connect_error) {

    die("Error de conexión: " . $conexion->connect_error);

}

$conexion->set_charset("utf8mb4");

?>