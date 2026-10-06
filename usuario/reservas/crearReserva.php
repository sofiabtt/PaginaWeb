<?php

session_start();

include "../../php/consultasReservas.php";

mysqli_report(
    MYSQLI_REPORT_ERROR | MYSQLI_REPORT_STRICT
);


// =====================================
// VERIFICAR QUE EL USUARIO ESTÉ LOGUEADO
// =====================================

if (
    !isset($_SESSION["codUsuario"]) ||
    !isset($_SESSION["tipoUsuario"]) ||
    $_SESSION["tipoUsuario"] !== "usuario"
) {

    header(
        "Location: ../../inicioSesion.php"
    );

    exit();
}


// =====================================
// VERIFICAR RESERVA TEMPORAL
// =====================================

if (!isset($_SESSION["reservaTemporal"])) {

    die(
        "No se encontraron los datos de la reserva."
    );
}


// =====================================
// OBTENER DATOS
// =====================================

$reservaTemporal =
    $_SESSION["reservaTemporal"];

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


// Datos del tipo de viaje

$tipoViaje =
    $reservaTemporal["tipoViaje"]
    ?? "soloIda";

$origen =
    $reservaTemporal["origen"]
    ?? "";

$destino =
    $reservaTemporal["destino"]
    ?? "";

$fechaIda =
    $reservaTemporal["fechaIda"]
    ?? "";

$fechaVuelta =
    $reservaTemporal["fechaVuelta"]
    ?? "";

$tramo =
    $reservaTemporal["tramo"]
    ?? "ida";

$reservaIda =
    (int) (
        $reservaTemporal["reservaIda"]
        ?? 0
    );


// =====================================
// CREAR RESERVA
// =====================================

$resultado = crearReserva(
    $conexion,
    $codUsuario,
    $codVuelo,
    $cantidadPasajeros
);


// =====================================
// SI HUBO ERROR
// =====================================

if (!$resultado["ok"]) {

    die(
        "No se pudo completar la reserva: "
        . htmlspecialchars(
            $resultado["mensaje"]
            ?? "Error desconocido."
        )
    );
}


// =====================================
// RESERVA CREADA
// =====================================

$codReserva =
    (int) $resultado["codReserva"];


// Ya no necesitamos la reserva temporal

unset($_SESSION["reservaTemporal"]);

unset($_SESSION["crearReservaDesdeVuelo"]);


// =====================================
// IDA Y VUELTA - RESERVA DE IDA
// =====================================

if (
    $tipoViaje === "idaVuelta"
    && $tramo === "ida"
) {

    /*
        Ya reservamos la ida.

        Ahora mandamos al usuario a elegir
        el vuelo de vuelta.
    */

    $url =
        "../../resultadosVuelos.php"
        . "?tipoViaje=idaVuelta"
        . "&origen=" . urlencode($origen)
        . "&destino=" . urlencode($destino)
        . "&fechaIda=" . urlencode($fechaIda)
        . "&fechaVuelta=" . urlencode($fechaVuelta)
        . "&etapa=vuelta"
        . "&reservaIda=" . $codReserva
        . "#vuelo-vuelta";

    header(
        "Location: " . $url
    );

    exit();
}


// =====================================
// IDA Y VUELTA - RESERVA DE VUELTA
// =====================================

if (
    $tipoViaje === "idaVuelta"
    && $tramo === "vuelta"
) {

    $url =
        "gestionReservas.php"
        . "?desdeReserva=1"
        . "&tipoViaje=idaVuelta"
        . "&tramo=vuelta"
        . "&reservaIda=" . $reservaIda
        . "&reserva=" . $codReserva;

    header(
        "Location: " . $url
    );

    exit();
}


// =====================================
// SOLO IDA
// =====================================

$url =
    "gestionReservas.php"
    . "?desdeReserva=1"
    . "&tipoViaje=soloIda"
    . "&reserva=" . $codReserva;

header(
    "Location: " . $url
);

exit();