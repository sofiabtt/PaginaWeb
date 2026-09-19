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


$correcta = confirmarReserva(
    $conexion,
    $codReserva,
    $codUsuario
);


$conexion->close();


header(
    "Location: gestionReservas.php?"
    . ($correcta ? "confirmada=1" : "error=confirmar")
);

exit();