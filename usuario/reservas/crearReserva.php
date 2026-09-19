<?php

session_start();

include "../../php/consultasReservas.php";

header("Content-Type: application/json");

mysqli_report(
    MYSQLI_REPORT_ERROR | MYSQLI_REPORT_STRICT
);


if (
    !isset($_SESSION["codUsuario"]) ||
    !isset($_SESSION["tipoUsuario"]) ||
    $_SESSION["tipoUsuario"] !== "usuario"
) {

    echo json_encode([
        "ok" => false,
        "mensaje" => "Debe iniciar sesión."
    ]);

    exit();
}


if (!isset($_SESSION["reservaTemporal"])) {

    echo json_encode([
        "ok" => false,
        "mensaje" => "No se encontraron los datos de la reserva."
    ]);

    exit();
}


$reservaTemporal = $_SESSION["reservaTemporal"];

$codUsuario = (int) $_SESSION["codUsuario"];

$codVuelo = (int) $reservaTemporal["codVuelo"];

$adultos = (int) $reservaTemporal["adultos"];

$menores = (int) $reservaTemporal["menores"];

$cantidadPasajeros = $adultos + $menores;


$resultado = crearReserva(
    $conexion,
    $codUsuario,
    $codVuelo,
    $cantidadPasajeros
);


if ($resultado["ok"]) {

    unset($_SESSION["reservaTemporal"]);
}


echo json_encode($resultado);

$conexion->close();