<?php

include "conexionBD.php";


// CREAR AEROLÍNEA

function crearAerolinea($conexion, $nombre, $iata, $descripcion, $pais)
{
    $consulta = "INSERT INTO aerolineas
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
    $consulta = "SELECT aerolineas.*,
                        paises.nombrePais
                 FROM aerolineas
                 INNER JOIN paises
                    ON aerolineas.codPais = paises.codPais
                 WHERE aerolineas.activoAerolinea = 1
                 ORDER BY aerolineas.codAerolinea";

    return $conexion->query($consulta);
}



// OBTENER AEROLÍNEAS INACTIVAS

function obtenerAerolineasInactivas($conexion)
{
    $consulta = "SELECT aerolineas.*,
                        paises.nombrePais
                 FROM aerolineas
                 INNER JOIN paises
                    ON aerolineas.codPais = paises.codPais
                 WHERE aerolineas.activoAerolinea = 0
                 ORDER BY aerolineas.fechaEliminacion DESC";

    return $conexion->query($consulta);
}


// OBTENER UNA AEROLÍNEA

function obtenerAerolinea($conexion, $codAerolinea)
{
    $consulta = "SELECT aerolineas.*,
                        paises.nombrePais
                 FROM aerolineas
                 INNER JOIN paises
                    ON aerolineas.codPais = paises.codPais
                 WHERE aerolineas.codAerolinea = ?";

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
    $consulta = "UPDATE aerolineas
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
    $consulta = "UPDATE aerolineas
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
                 FROM paises
                 ORDER BY nombrePais";

    return $conexion->query($consulta);
}


// ASIGNAR CEO A UNA AEROLÍNEA

function asignarCeoAerolinea($conexion, $codUsuario, $codAerolinea)
{
    $consulta = "UPDATE aerolineas
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
                 FROM aerolineas
                 WHERE activoAerolinea = 1";

    $resultado = $conexion->query($consulta);

    return $resultado->fetch_assoc()["cantidad"];
}

// OBTENER AEROLÍNEA DE UN CEO

function obtenerAerolineaPorCeo($conexion, $codUsuario)
{
    $consulta = "SELECT codAerolinea,
                        nombreAerolinea
                 FROM aerolineas
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