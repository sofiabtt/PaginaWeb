<?php

$token = $_GET["token"] ?? "";

if (!preg_match('/^[a-f0-9]{64}$/', $token)) {
    header("Location: ../inicioSesion.php?verificacion=invalida");
    exit();
}

include "conexionBD.php";

$consulta = $conexion->prepare(
    "SELECT codUsuario, fechaVerificacion
     FROM Usuarios
     WHERE tokenVerificacion = ? AND verificado = 0"
);
$consulta->bind_param("s", $token);
$consulta->execute();
$usuario = $consulta->get_result()->fetch_assoc();
$consulta->close();

if (
    !$usuario
    || !$usuario["fechaVerificacion"]
    || strtotime($usuario["fechaVerificacion"]) < time()
) {
    $conexion->close();
    header("Location: ../inicioSesion.php?verificacion=vencida");
    exit();
}

$actualizar = $conexion->prepare(
    "UPDATE Usuarios
     SET verificado = 1, tokenVerificacion = NULL, fechaVerificacion = NOW()
     WHERE codUsuario = ?"
);
$actualizar->bind_param("i", $usuario["codUsuario"]);
$actualizar->execute();
$actualizar->close();
$conexion->close();

header("Location: ../inicioSesion.php?verificacion=correcta");
exit();
