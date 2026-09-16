<?php

session_start();

include "../../php/conexionBD.php";

header("Content-Type: application/json");
mysqli_report(MYSQLI_REPORT_ERROR | MYSQLI_REPORT_STRICT);


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

$codUsuario =
    (int) $_SESSION["codUsuario"];

$codVuelo =
    (int) $reservaTemporal["codVuelo"];

$adultos =
    (int) $reservaTemporal["adultos"];

$menores =
    (int) $reservaTemporal["menores"];

$cantidadPasajeros =
    $adultos + $menores;


/* BUSCAMOS EL PRECIO DEL VUELO */

$consultaVuelo = $conexion->prepare(
    "SELECT precioVuelo
     FROM Vuelos
     WHERE codVuelo = ?
     AND activoVuelo = 1"
);

$consultaVuelo->bind_param(
    "i",
    $codVuelo
);

$consultaVuelo->execute();

$resultadoVuelo =
    $consultaVuelo->get_result();

$vuelo =
    $resultadoVuelo->fetch_assoc();


if (!$vuelo) {

    echo json_encode([
        "ok" => false,
        "mensaje" => "No se encontró el vuelo."
    ]);

    exit();
}


$precioFinal =
    $vuelo["precioVuelo"]
    * $cantidadPasajeros;


/* CREAMOS LA RESERVA */

$estado =
    "pendiente de pago";

$consulta = $conexion->prepare(
    "INSERT INTO Reservas
    (
        codUsuario,
        codVuelo,
        fechaReserva,
        estadoReserva,
        precioFinalReserva
    )
    VALUES (?, ?, NOW(), ?, ?)"
);

$consulta->bind_param(
    "iisd",
    $codUsuario,
    $codVuelo,
    $estado,
    $precioFinal
);


if ($consulta->execute()) {

    echo json_encode([
        "ok" => true,
        "codReserva" => $conexion->insert_id
    ]);

} else {

    echo json_encode([
        "ok" => false,
        "mensaje" => "No se pudo crear la reserva."
    ]);
}