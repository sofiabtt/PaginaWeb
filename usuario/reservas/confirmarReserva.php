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

header("Location: gestionReservas.php?" . ($correcta ? "confirmada=1" : "error=confirmar"));
exit();

