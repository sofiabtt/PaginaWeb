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

// CANTIDAD DE USUARIOS

function cantidadUsuarios($conexion)
{
    $consulta = "SELECT COUNT(*) AS cantidad
                 FROM Usuarios
                 WHERE tipoUsuario = 'usuario'";

    $resultado = $conexion->query($consulta);

    return $resultado->fetch_assoc()["cantidad"];
}


// OBTENER USUARIOS PAGINADOS

function obtenerUsuariosPaginados($conexion, $porPagina,$inicio)
{
    $consulta = $conexion->prepare(
        "SELECT codUsuario,
                nombreUsuario,
                emailUsuario,
                telefonoUsuario,
                verificado
         FROM Usuarios
         WHERE tipoUsuario = 'usuario'
         ORDER BY codUsuario DESC
         LIMIT ? OFFSET ?"
    );

    $consulta->bind_param(
        "ii",
        $porPagina,
        $inicio
    );

    $consulta->execute();

    return $consulta->get_result();
}