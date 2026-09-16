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
        $_POST["fechaMenor"] ?? []

];

echo "ok";