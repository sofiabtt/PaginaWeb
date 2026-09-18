<?php

include "conexionBD.php";


// MODIFICAR USUARIO

function modificarUsuario($conexion, $codUsuario, $nombre, $email, $telefono)
{
    $consulta = "UPDATE Usuarios
                 SET nombreUsuario = ?,
                     emailUsuario = ?,
                     telefonoUsuario = ?
                 WHERE codUsuario = ?";

    $stmt = $conexion->prepare($consulta);

    $stmt->bind_param(
        "sssi",
        $nombre,
        $email,
        $telefono,
        $codUsuario
    );

    return $stmt->execute();
}


// OBTENER USUARIO

function obtenerUsuario($conexion, $codUsuario)
{
    $consulta = "SELECT nombreUsuario,
                        emailUsuario,
                        telefonoUsuario
                 FROM Usuarios
                 WHERE codUsuario = ?";

    $stmt = $conexion->prepare($consulta);

    $stmt->bind_param(
        "i",
        $codUsuario
    );

    $stmt->execute();

    $resultado = $stmt->get_result();

    return $resultado->fetch_assoc();
}