<?php

require "../includes/protegerUsuario.php";

if ($_SERVER["REQUEST_METHOD"] !== "POST") {
    header("Location: gestionReservas.php");
    exit();
}

$codReserva = filter_input(INPUT_POST, "id", FILTER_VALIDATE_INT);
$codUsuario = (int) $_SESSION["codUsuario"];
include "../../php/conexionBD.php";
$conexion->begin_transaction();

try {
    $consulta = $conexion->prepare(
        "SELECT r.codVuelo, r.estadoReserva, v.fechaSalidaVuelo, v.horaSalidaVuelo
         FROM Reservas r INNER JOIN Vuelos v ON v.codVuelo = r.codVuelo
         WHERE r.codReserva = ? AND r.codUsuario = ? FOR UPDATE"
    );
    $consulta->bind_param("ii", $codReserva, $codUsuario);
    $consulta->execute();
    $reserva = $consulta->get_result()->fetch_assoc();

    $salida = $reserva ? strtotime($reserva["fechaSalidaVuelo"] . " " . $reserva["horaSalidaVuelo"]) : 0;
    if (!$reserva || $reserva["estadoReserva"] === "cancelada" || ($salida - time()) < 72 * 60 * 60) {
        throw new RuntimeException("Cancelación fuera de término");
    }

    $cancelar = $conexion->prepare(
        "UPDATE Reservas SET estadoReserva = 'cancelada'
         WHERE codReserva = ? AND codUsuario = ?"
    );
    $cancelar->bind_param("ii", $codReserva, $codUsuario);
    $cancelar->execute();

    $devolver = $conexion->prepare(
        "UPDATE Vuelos SET asientosDisponibles = asientosDisponibles + 1 WHERE codVuelo = ?"
    );
    $devolver->bind_param("i", $reserva["codVuelo"]);
    $devolver->execute();

    $conexion->commit();
    header("Location: gestionReservas.php?cancelada=1");
    exit();
} catch (Throwable $e) {
    $conexion->rollback();
    header("Location: gestionReservas.php?error=cancelar");
    exit();
}
