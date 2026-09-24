<?php

session_start();

$_SESSION["reservaTemporal"] = [

    "codVuelo" => $_POST["codVuelo"],

    "adultos" => $_POST["adultos"],

    "menores" => $_POST["menores"],

    "nombreAdulto" =>
        $_POST["nombreAdulto"] ?? [],

    "apellidoAdulto" =>
        $_POST["apellidoAdulto"] ?? [],

    "emailAdulto" =>
        $_POST["emailAdulto"] ?? [],

    "fechaAdulto" =>
        $_POST["fechaAdulto"] ?? [],

    "nombreMenor" =>
        $_POST["nombreMenor"] ?? [],

    "apellidoMenor" =>
        $_POST["apellidoMenor"] ?? [],

    "emailMenor" =>
        $_POST["emailMenor"] ?? [],

    "fechaMenor" =>
        $_POST["fechaMenor"] ?? [],

    // Datos de la búsqueda. Se guardan para no perder el flujo
    // de ida y vuelta al iniciar sesión o registrarse.
    "tipoViaje" => $_POST["tipoViaje"] ?? null,
    "origen" => $_POST["origen"] ?? null,
    "destino" => $_POST["destino"] ?? null,
    "fechaIda" => $_POST["fechaIda"] ?? null,
    "fechaVuelta" => $_POST["fechaVuelta"] ?? null,
    "tramo" => $_POST["tramo"] ?? null

];

// Además los dejamos disponibles directamente en la sesión,
// que es de donde vueloElegido.php los recupera.
if (!empty($_POST["tipoViaje"])) {
    $_SESSION["tipoViajeReserva"] = $_POST["tipoViaje"];
}
if (isset($_POST["origen"])) {
    $_SESSION["origenReserva"] = $_POST["origen"];
}
if (isset($_POST["destino"])) {
    $_SESSION["destinoReserva"] = $_POST["destino"];
}
if (isset($_POST["fechaIda"])) {
    $_SESSION["fechaIdaReserva"] = $_POST["fechaIda"];
}
if (isset($_POST["fechaVuelta"])) {
    $_SESSION["fechaVueltaReserva"] = $_POST["fechaVuelta"];
}
if (isset($_POST["tramo"])) {
    $_SESSION["tramoReserva"] = $_POST["tramo"];
}

echo "ok";
