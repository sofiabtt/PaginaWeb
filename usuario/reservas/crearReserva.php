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
    "SELECT precioVuelo, asientosDisponibles
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
if ($vuelo["asientosDisponibles"] < $cantidadPasajeros) {

    echo json_encode([
        "ok" => false,
        "mensaje" => "No hay suficientes asientos disponibles."
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
        precioFinalReserva,
        cantidadPasajerosReserva
    )
    VALUES (?, ?, NOW(), ?, ?, ?)"
);

$consulta->bind_param(
    "iisdi",
    $codUsuario,
    $codVuelo,
    $estado,
    $precioFinal,
    $cantidadPasajeros
);


if ($consulta->execute()) {

    $codReserva = $conexion->insert_id;

    $actualizarAsientos = $conexion->prepare(
        "UPDATE Vuelos
         SET asientosDisponibles = asientosDisponibles - ?
         WHERE codVuelo = ?
         AND asientosDisponibles >= ?"
    );

    $actualizarAsientos->bind_param(
        "iii",
        $cantidadPasajeros,
        $codVuelo,
        $cantidadPasajeros
    );

    $actualizarAsientos->execute();

    unset($_SESSION["reservaTemporal"]);

    echo json_encode([
        "ok" => true,
        "codReserva" => $codReserva
    ]);
}