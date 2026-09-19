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

// OBTENER PERFIL DE USUARIO

function obtenerPerfilUsuario($conexion, $codUsuario)
{
    $consulta = $conexion->prepare(
        "SELECT nombreUsuario,
                emailUsuario,
                telefonoUsuario
         FROM Usuarios
         WHERE codUsuario = ?
           AND tipoUsuario = 'usuario'"
    );

    $consulta->bind_param(
        "i",
        $codUsuario
    );

    $consulta->execute();

    return $consulta
        ->get_result()
        ->fetch_assoc();
}


// ACTUALIZAR PERFIL DE USUARIO

function actualizarPerfilUsuario(
    $conexion,
    $codUsuario,
    $nombre,
    $telefono,
    $claveNueva
) {

    if ($claveNueva !== "") {

        $hash = password_hash(
            $claveNueva,
            PASSWORD_DEFAULT
        );

        $actualizar = $conexion->prepare(
            "UPDATE Usuarios
             SET nombreUsuario = ?,
                 telefonoUsuario = ?,
                 claveUsuario = ?
             WHERE codUsuario = ?
               AND tipoUsuario = 'usuario'"
        );

        $actualizar->bind_param(
            "sssi",
            $nombre,
            $telefono,
            $hash,
            $codUsuario
        );

    } else {

        $actualizar = $conexion->prepare(
            "UPDATE Usuarios
             SET nombreUsuario = ?,
                 telefonoUsuario = ?
             WHERE codUsuario = ?
               AND tipoUsuario = 'usuario'"
        );

        $actualizar->bind_param(
            "ssi",
            $nombre,
            $telefono,
            $codUsuario
        );
    }


    return $actualizar->execute();
}

// OBTENER USUARIO POR EMAIL PARA INICIO DE SESIÓN

function obtenerUsuarioPorEmail($conexion, $email)
{
    $consulta = $conexion->prepare(
        "SELECT codUsuario,
                nombreUsuario,
                claveUsuario,
                tipoUsuario,
                verificado
         FROM Usuarios
         WHERE emailUsuario = ?"
    );

    $consulta->bind_param(
        "s",
        $email
    );

    $consulta->execute();

    return $consulta
        ->get_result()
        ->fetch_assoc();
}