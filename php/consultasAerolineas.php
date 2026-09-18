<?php

include "conexionBD.php";

// CREAR AEROLINEA

function crearAerolinea($conexion, $nombre, $iata, $descripcion, $pais)
{
    $consulta = "INSERT INTO Aerolineas
                 (
                     nombreAerolinea,
                     codigoIATA,
                     descripcionAerolinea,
                     codPais
                 )
                 VALUES (?, ?, ?, ?)";

    $stmt = $conexion->prepare($consulta);

    $stmt->bind_param(
        "sssi",
        $nombre,
        $iata,
        $descripcion,
        $pais
    );

    return $stmt->execute();
}


// OBTENER AEROLÍNEAS ACTIVAS

function obtenerAerolineasActivas($conexion)
{
    $consulta = "SELECT Aerolineas.*,
                        Paises.nombrePais
                 FROM Aerolineas
                 INNER JOIN Paises
                    ON Aerolineas.codPais = Paises.codPais
                 WHERE Aerolineas.activoAerolinea = 1
                 ORDER BY Aerolineas.codAerolinea";

    return $conexion->query($consulta);
}


// OBTENER AEROLÍNEAS INACTIVAS

function obtenerAerolineasInactivas($conexion)
{
    $consulta = "SELECT Aerolineas.*,
                        Paises.nombrePais
                 FROM Aerolineas
                 INNER JOIN Paises
                    ON Aerolineas.codPais = Paises.codPais
                 WHERE Aerolineas.activoAerolinea = 0
                 ORDER BY Aerolineas.fechaEliminacion DESC";

    return $conexion->query($consulta);
}


// OBTENER UNA AEROLÍNEA

function obtenerAerolinea($conexion, $codAerolinea)
{
    $consulta = "SELECT Aerolineas.*,
                        Paises.nombrePais
                 FROM Aerolineas
                 INNER JOIN Paises
                    ON Aerolineas.codPais = Paises.codPais
                 WHERE Aerolineas.codAerolinea = ?";

    $stmt = $conexion->prepare($consulta);

    $stmt->bind_param(
        "i",
        $codAerolinea
    );

    $stmt->execute();

    $resultado = $stmt->get_result();

    return $resultado->fetch_assoc();
}

// MODIFICAR UNA AEROLINEA

function modificarAerolinea($conexion, $codAerolinea, $nombre, $codigoIATA, $descripcion, $codPais)
{
    $consulta = "UPDATE Aerolineas
                 SET nombreAerolinea = ?,
                     codigoIATA = ?,
                     descripcionAerolinea = ?,
                     codPais = ?
                 WHERE codAerolinea = ?";

    $stmt = $conexion->prepare($consulta);

    $stmt->bind_param(
        "sssii",
        $nombre,
        $codigoIATA,
        $descripcion,
        $codPais,
        $codAerolinea
    );

    return $stmt->execute();
}

// BAJA LOGICA

function eliminarAerolinea($conexion, $codAerolinea)
{
    $consulta = "UPDATE Aerolineas
                 SET activoAerolinea = 0,
                     fechaEliminacion = NOW()
                 WHERE codAerolinea = ?";

    $stmt = $conexion->prepare($consulta);

    $stmt->bind_param(
        "i",
        $codAerolinea
    );

    return $stmt->execute();
}

function obtenerPaises($conexion)
{
    $consulta = "SELECT codPais, nombrePais
                 FROM Paises
                 ORDER BY nombrePais";

    return $conexion->query($consulta);
}