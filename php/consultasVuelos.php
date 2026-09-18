<?php

include "conexionBD.php";


// CREAR VUELO

function crearVuelo($conexion,$codAerolinea,$origen,$destino,
$fecha,$hora,$precio,$asientos)
{
    $consulta = "INSERT INTO Vuelos
                 (
                     codAerolinea,
                     origenVuelo,
                     destinoVuelo,
                     fechaSalidaVuelo,
                     horaSalidaVuelo,
                     precioVuelo,
                     asientosDisponibles
                 )
                 VALUES (?, ?, ?, ?, ?, ?, ?)";

    $stmt = $conexion->prepare($consulta);

    $stmt->bind_param(
        "issssdi",
        $codAerolinea,
        $origen,
        $destino,
        $fecha,
        $hora,
        $precio,
        $asientos
    );

    return $stmt->execute();
}


// OBTENER VUELOS DE UNA AEROLÍNEA

function obtenerVuelosPorAerolinea($conexion,$codAerolinea)
{
    $consulta = $conexion->prepare("
        SELECT *
        FROM Vuelos
        WHERE codAerolinea = ?
          AND activoVuelo = 1
        ORDER BY
            fechaSalidaVuelo ASC,
            horaSalidaVuelo ASC
    ");

    $consulta->bind_param(
        "i",
        $codAerolinea
    );

    $consulta->execute();

    return $consulta->get_result();
}


// OBTENER VUELOS DADOS DE BAJA

function obtenerVuelosInactivos($conexion,$codAerolinea)
{
    $consulta = $conexion->prepare("
        SELECT *
        FROM Vuelos
        WHERE activoVuelo = 0
          AND codAerolinea = ?
        ORDER BY fechaEliminacion DESC
    ");

    $consulta->bind_param(
        "i",
        $codAerolinea
    );

    $consulta->execute();

    return $consulta->get_result();
}


// OBTENER UN VUELO

function obtenerVuelo($conexion,$codVuelo,$codAerolinea)
{
    $consulta = $conexion->prepare("
        SELECT
            codVuelo,
            codAerolinea,
            origenVuelo,
            destinoVuelo,
            fechaSalidaVuelo,
            horaSalidaVuelo,
            precioVuelo,
            asientosDisponibles,
            activoVuelo,
            fechaEliminacion

        FROM Vuelos

        WHERE codVuelo = ?
          AND codAerolinea = ?
    ");

    $consulta->bind_param(
        "ii",
        $codVuelo,
        $codAerolinea
    );

    $consulta->execute();

    $resultado = $consulta->get_result();

    return $resultado->fetch_assoc();
}


// MODIFICAR VUELO

function modificarVuelo($conexion,$codVuelo,$codAerolinea,$origen,$destino,
$fecha,$hora,$precio,$asientos)
{
    $consulta = $conexion->prepare("
        UPDATE Vuelos

        SET
            origenVuelo = ?,
            destinoVuelo = ?,
            fechaSalidaVuelo = ?,
            horaSalidaVuelo = ?,
            precioVuelo = ?,
            asientosDisponibles = ?

        WHERE codVuelo = ?
          AND codAerolinea = ?
          AND activoVuelo = 1
    ");

    $consulta->bind_param(
        "ssssdiii",
        $origen,
        $destino,
        $fecha,
        $hora,
        $precio,
        $asientos,
        $codVuelo,
        $codAerolinea
    );

    return $consulta->execute();
}


// BAJA LÓGICA

function eliminarVuelo($conexion,$codVuelo,$codAerolinea)
{
    $consulta = $conexion->prepare("
        UPDATE Vuelos

        SET
            activoVuelo = 0,
            fechaEliminacion = NOW()

        WHERE codVuelo = ?
          AND codAerolinea = ?
          AND activoVuelo = 1
    ");

    $consulta->bind_param(
        "ii",
        $codVuelo,
        $codAerolinea
    );

    return $consulta->execute();
}


// OBTENER AEROPUERTOS

function obtenerAeropuertos($conexion)
{
    $consulta = "
        SELECT
            a.codigoIATA,
            a.nombreAeropuerto,
            c.nombreCiudad,
            p.nombrePais

        FROM Aeropuertos a

        INNER JOIN Ciudades c
            ON a.codCiudad = c.codCiudad

        INNER JOIN Paises p
            ON c.codPais = p.codPais

        ORDER BY
            p.nombrePais,
            c.nombreCiudad
    ";

    return $conexion->query($consulta);
}

// OBTENER CÓDIGO DEL VUELO

function obtenerCodVuelo()
{
    if (!isset($_GET["id"])) {

        echo "Vuelo no especificado.";
        exit();

    }

    return intval($_GET["id"]);
}

// CANTIDAD DE VUELOS DE UNA AEROLÍNEA

function cantidadVuelosPorAerolinea(
    $conexion,
    $codAerolinea
)
{
    $consulta = $conexion->prepare("
        SELECT COUNT(*) AS cantidad

        FROM Vuelos

        WHERE codAerolinea = ?
          AND activoVuelo = 1
    ");

    $consulta->bind_param(
        "i",
        $codAerolinea
    );

    $consulta->execute();

    $resultado = $consulta->get_result();

    return $resultado->fetch_assoc()["cantidad"];
}


// OBTENER PRÓXIMOS VUELOS

function obtenerProximosVuelos(
    $conexion,
    $codAerolinea
)
{
    $consulta = $conexion->prepare("
        SELECT
            codVuelo,
            origenVuelo,
            destinoVuelo,
            fechaSalidaVuelo,
            horaSalidaVuelo,
            asientosDisponibles

        FROM Vuelos

        WHERE codAerolinea = ?
          AND activoVuelo = 1

        ORDER BY
            fechaSalidaVuelo ASC,
            horaSalidaVuelo ASC

        LIMIT 5
    ");

    $consulta->bind_param(
        "i",
        $codAerolinea
    );

    $consulta->execute();

    return $consulta->get_result();
}