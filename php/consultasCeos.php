<?php

include "conexionBD.php";


// OBTENER CEOs

function obtenerCeos($conexion)
{
    $consulta = "SELECT Usuarios.*,
                        Aerolineas.nombreAerolinea
                 FROM Usuarios
                 LEFT JOIN Aerolineas
                    ON Usuarios.codUsuario = Aerolineas.codUsuario
                 WHERE Usuarios.tipoUsuario = 'ceo'
                 AND Usuarios.activoUsuario = 1
                 ORDER BY Usuarios.nombreUsuario";

    return $conexion->query($consulta);
}


// OBTENER UN CEO

function obtenerCeo($conexion, $codUsuario)
{
    $consulta = "SELECT Usuarios.*,
                        Aerolineas.codAerolinea,
                        Aerolineas.nombreAerolinea
                 FROM Usuarios
                 LEFT JOIN Aerolineas
                    ON Usuarios.codUsuario = Aerolineas.codUsuario
                 WHERE Usuarios.codUsuario = ?
                 AND Usuarios.tipoUsuario = 'ceo'
                 AND Usuarios.activoUsuario = 1";

    $stmt = $conexion->prepare($consulta);

    $stmt->bind_param(
        "i",
        $codUsuario
    );

    $stmt->execute();

    $resultado = $stmt->get_result();

    return $resultado->fetch_assoc();
}


// OBTENER CEO POR TOKEN

function obtenerCeoPorToken($conexion, $token)
{
    $consulta = "SELECT
                        codUsuario,
                        nombreUsuario,
                        emailUsuario,
                        tokenVerificacion,
                        fechaVerificacion
                 FROM Usuarios
                 WHERE tokenVerificacion = ?
                 AND tipoUsuario = 'ceo'
                 AND activoUsuario = 1";

    $stmt = $conexion->prepare($consulta);

    $stmt->bind_param(
        "s",
        $token
    );

    $stmt->execute();

    $resultado = $stmt->get_result();

    return $resultado->fetch_assoc();
}


// VERIFICAR SI EXISTE UN EMAIL

function emailExiste($conexion, $email)
{
    $consulta = "SELECT codUsuario
                 FROM Usuarios
                 WHERE emailUsuario = ?";

    $stmt = $conexion->prepare($consulta);

    $stmt->bind_param(
        "s",
        $email
    );

    $stmt->execute();

    $resultado = $stmt->get_result();

    return $resultado->num_rows > 0;
}


// CREAR CEO

function crearCeo(
    $conexion,
    $nombre,
    $clave,
    $tipoUsuario,
    $email,
    $telefono,
    $verificado,
    $token,
    $fechaVerificacion
)
{
    $consulta = "INSERT INTO Usuarios
                 (
                     nombreUsuario,
                     claveUsuario,
                     tipoUsuario,
                     emailUsuario,
                     telefonoUsuario,
                     verificado,
                     tokenVerificacion,
                     fechaVerificacion
                 )
                 VALUES (?, ?, ?, ?, ?, ?, ?, ?)";

    $stmt = $conexion->prepare($consulta);

    $stmt->bind_param(
        "sssssiss",
        $nombre,
        $clave,
        $tipoUsuario,
        $email,
        $telefono,
        $verificado,
        $token,
        $fechaVerificacion
    );

    if (!$stmt->execute()) {
        return false;
    }

    return $conexion->insert_id;
}


// CREAR CONTRASEÑA DEL CEO

function crearClaveCeo($conexion, $codUsuario, $claveHash)
{
    $consulta = "UPDATE Usuarios
                 SET claveUsuario = ?,
                     verificado = 1,
                     tokenVerificacion = NULL,
                     fechaVerificacion = NULL
                 WHERE codUsuario = ?";

    $stmt = $conexion->prepare($consulta);

    $stmt->bind_param(
        "si",
        $claveHash,
        $codUsuario
    );

    return $stmt->execute();
}


// ELIMINAR CEO - BAJA LÓGICA

function eliminarCeo($conexion, $codUsuario)
{
    // Desvincular CEO de la aerolínea

    $consulta = "UPDATE Aerolineas
                 SET codUsuario = NULL
                 WHERE codUsuario = ?";

    $stmt = $conexion->prepare($consulta);

    $stmt->bind_param(
        "i",
        $codUsuario
    );

    if (!$stmt->execute()) {
        return false;
    }


    // Dar de baja al CEO

    $consulta = "UPDATE Usuarios
                 SET activoUsuario = 0,
                     fechaEliminacion = NOW()
                 WHERE codUsuario = ?
                 AND tipoUsuario = 'ceo'";

    $stmt = $conexion->prepare($consulta);

    $stmt->bind_param(
        "i",
        $codUsuario
    );

    return $stmt->execute();
}

// CANTIDAD DE CEOs

function cantidadCeos($conexion)
{
    $consulta = "SELECT COUNT(*) AS cantidad
                 FROM Usuarios
                 WHERE tipoUsuario = 'ceo'";

    $resultado = $conexion->query($consulta);

    return $resultado->fetch_assoc()["cantidad"];
}

// VERIFICAR QUE SEA CEO

function verificarCeo()
{
    if (
        !isset($_SESSION["tipoUsuario"]) ||
        $_SESSION["tipoUsuario"] != "ceo"
    ) {

        header("Location: ../../inicioSesion.php");
        exit();

    }
}


// OBTENER CÓDIGO DEL CEO

function obtenerCodCeo()
{
    if (!isset($_SESSION["codUsuario"])) {

        echo "No se pudo identificar al CEO.";
        exit();

    }

    return $_SESSION["codUsuario"];
}