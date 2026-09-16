<?php

session_start();

$codVuelo =
    isset($_GET["codVuelo"])
    ? (int) $_GET["codVuelo"]
    : 0;

$cantidadPasajes =
    isset($_GET["cantidadPasajes"])
    ? (int) $_GET["cantidadPasajes"]
    : 1;


/* VALIDACIONES */

if ($cantidadPasajes < 1) {
    $cantidadPasajes = 1;
}

if ($cantidadPasajes > 10) {
    $cantidadPasajes = 10;
}


/* GUARDAR TEMPORALMENTE */

$_SESSION["reservaTemporal"] = [

    "codVuelo" => $codVuelo,

    "cantidadPasajes" => $cantidadPasajes

];


/* IR AL REGISTRO */

header(
    "Location: registroUsuario.php"
);

exit;