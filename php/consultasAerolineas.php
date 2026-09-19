<?php

include "conexionBD.php";


// CREAR AEROLÍNEA

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


// MODIFICAR UNA AEROLÍNEA

function modificarAerolinea(
    $conexion,
    $codAerolinea,
    $nombre,
    $codigoIATA,
    $descripcion,
    $codPais
)
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


// BAJA LÓGICA

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

// OBTENER PAÍSES

function obtenerPaises($conexion)
{
    $consulta = "SELECT codPais, nombrePais
                 FROM Paises
                 ORDER BY nombrePais";

    return $conexion->query($consulta);
}


// ASIGNAR CEO A UNA AEROLÍNEA

function asignarCeoAerolinea($conexion, $codUsuario, $codAerolinea)
{
    $consulta = "UPDATE Aerolineas
                 SET codUsuario = ?
                 WHERE codAerolinea = ?
                 AND activoAerolinea = 1";

    $stmt = $conexion->prepare($consulta);

    $stmt->bind_param(
        "ii",
        $codUsuario,
        $codAerolinea
    );

    return $stmt->execute();
}


// CANTIDAD DE AEROLÍNEAS ACTIVAS

function cantidadAerolineasActivas($conexion)
{
    $consulta = "SELECT COUNT(*) AS cantidad
                 FROM Aerolineas
                 WHERE activoAerolinea = 1";

    $resultado = $conexion->query($consulta);

    return $resultado->fetch_assoc()["cantidad"];
}

// OBTENER AEROLÍNEA DE UN CEO

function obtenerAerolineaPorCeo($conexion, $codUsuario)
{
function obtenerAerolineaPorCeo($conexion, $codUsuario)
{
    $consulta = "SELECT codAerolinea,
                        nombreAerolinea
                 FROM Aerolineas
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