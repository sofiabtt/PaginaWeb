<?php

include "conexionBD.php";


// CREAR NOVEDAD

function crearNovedad(
    $conexion,
    $texto,
    $fechaPublicacion,
    $fechaExpiracion
)
{
    $consulta = "INSERT INTO Novedades
                 (
                     textoNovedad,
                     fechaPublicacionNovedad,
                     fechaExpiracionNovedad
                 )
                 VALUES (?, ?, ?)";

    $stmt = $conexion->prepare($consulta);

    $stmt->bind_param(
        "sss",
        $texto,
        $fechaPublicacion,
        $fechaExpiracion
    );

    return $stmt->execute();
}


// OBTENER NOVEDADES ACTIVAS

function obtenerNovedadesActivas($conexion)
{
    $consulta = "SELECT *
                 FROM Novedades
                 WHERE activoNovedad = 1
                 ORDER BY codNovedad";

    return $conexion->query($consulta);
}


// OBTENER NOVEDADES INACTIVAS

function obtenerNovedadesInactivas($conexion)
{
    $consulta = "SELECT *
                 FROM Novedades
                 WHERE activoNovedad = 0
                 ORDER BY fechaEliminacion DESC";

    return $conexion->query($consulta);
}


// OBTENER UNA NOVEDAD

function obtenerNovedad($conexion, $codNovedad)
{
    $consulta = "SELECT *
                 FROM Novedades
                 WHERE codNovedad = ?";

    $stmt = $conexion->prepare($consulta);

    $stmt->bind_param(
        "i",
        $codNovedad
    );

    $stmt->execute();

    $resultado = $stmt->get_result();

    return $resultado->fetch_assoc();
}


// MODIFICAR UNA NOVEDAD

function modificarNovedad(
    $conexion,
    $codNovedad,
    $texto,
    $fechaPublicacion,
    $fechaExpiracion
)
{
    $consulta = "UPDATE Novedades
                 SET textoNovedad = ?,
                     fechaPublicacionNovedad = ?,
                     fechaExpiracionNovedad = ?
                 WHERE codNovedad = ?";

    $stmt = $conexion->prepare($consulta);

    $stmt->bind_param(
        "sssi",
        $texto,
        $fechaPublicacion,
        $fechaExpiracion,
        $codNovedad
    );

    return $stmt->execute();
}


// BAJA LÓGICA

function eliminarNovedad($conexion, $codNovedad)
{
    $consulta = "UPDATE Novedades
                 SET activoNovedad = 0,
                     fechaEliminacion = NOW()
                 WHERE codNovedad = ?";

    $stmt = $conexion->prepare($consulta);

    $stmt->bind_param(
        "i",
        $codNovedad
    );

    return $stmt->execute();
}

// CANTIDAD DE NOVEDADES

function cantidadNovedades($conexion)
{
    $consulta = "SELECT COUNT(*) AS cantidad
                 FROM Novedades";

    $resultado = $conexion->query($consulta);

    return $resultado->fetch_assoc()["cantidad"];
}

// OBTENER NOVEDADES VIGENTES PARA USUARIO

function obtenerNovedadesVigentes($conexion)
{
    $consulta = "
        SELECT textoNovedad,
               fechaPublicacionNovedad,
               fechaExpiracionNovedad
        FROM Novedades
        WHERE activoNovedad = 1
          AND fechaPublicacionNovedad <= CURDATE()
          AND fechaExpiracionNovedad >= CURDATE()
        ORDER BY fechaPublicacionNovedad DESC
    ";

    return $conexion->query($consulta);
}