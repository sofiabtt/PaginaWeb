<?php

require "../includes/protegerUsuario.php";
include "../../php/consultasReservas.php";


if ($_SERVER["REQUEST_METHOD"] !== "POST") {

    header("Location: gestionReservas.php");
    exit();

}


$codReserva = filter_input(
    INPUT_POST,
    "id",
    FILTER_VALIDATE_INT
);

$codUsuario = (int) $_SESSION["codUsuario"];


$correcta = cancelarReserva(
    $conexion,
    $codReserva,
    $codUsuario
);


$conexion->close();


header(
    "Location: gestionReservas.php?"
    . ($correcta ? "cancelada=1" : "error=cancelar")
);

exit();