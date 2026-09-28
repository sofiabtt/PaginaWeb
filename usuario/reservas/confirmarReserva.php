<?php

require "../includes/protegerUsuario.php";

if ($_SERVER["REQUEST_METHOD"] !== "POST") {
    header("Location: gestionReservas.php");
    exit();
}

$codReserva = filter_input(INPUT_POST, "id", FILTER_VALIDATE_INT);
$codUsuario = (int) $_SESSION["codUsuario"];
include "../../php/conexionBD.php";

$consulta = $conexion->prepare(
    "UPDATE Reservas SET estadoReserva = 'confirmada'
     WHERE codReserva = ? AND codUsuario = ? AND estadoReserva = 'pendiente de pago'"
);
$consulta->bind_param("ii", $codReserva, $codUsuario);
$consulta->execute();
$correcta = $consulta->affected_rows === 1;
$consulta->close();
$conexion->close();

$parametros = [];

$parametros[] =
    $correcta
        ? "confirmada=1"
        : "error=confirmar";

if (
    isset($_POST["desdeReserva"])
    && $_POST["desdeReserva"] === "1"
    && ($_POST["tipoViaje"] ?? "") === "idaVuelta"
) {

    $reservaIda =
        filter_input(
            INPUT_POST,
            "reservaIda",
            FILTER_VALIDATE_INT
        );

    $reservaVuelta =
        filter_input(
            INPUT_POST,
            "reserva",
            FILTER_VALIDATE_INT
        );

    if ($reservaIda && $reservaVuelta) {

        $parametros[] = "desdeReserva=1";
        $parametros[] = "tipoViaje=idaVuelta";
        $parametros[] = "tramo=vuelta";
        $parametros[] =
            "reservaIda=" . urlencode($reservaIda);
        $parametros[] =
            "reserva=" . urlencode($reservaVuelta);
    }
}

header(
    "Location: gestionReservas.php?"
    . implode("&", $parametros)
);

exit();

