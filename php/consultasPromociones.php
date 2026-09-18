<?php

include "conexionBD.php";


// OBTENER PROMOCIONES ACTIVAS

function obtenerPromocionesActivas($conexion)
{
    $consulta = "SELECT
                        p.codPromocion,
                        p.descripcionPromocion,
                        p.descuentoPromocion,
                        p.estadoPromocion,
                        a.nombreAerolinea,
                        u.nombreUsuario AS nombreCeo

                 FROM Promociones p

                 INNER JOIN Aerolineas a
                    ON p.codAerolinea = a.codAerolinea

                 LEFT JOIN Usuarios u
                    ON a.codUsuario = u.codUsuario

                 WHERE p.activoPromocion = 1

                 ORDER BY
                    CASE
                        WHEN p.estadoPromocion = 'Pendiente' THEN 0
                        ELSE 1
                    END,
                    p.codPromocion DESC";

    return $conexion->query($consulta);
}


// OBTENER PROMOCIONES DE UNA AEROLÍNEA

function obtenerPromocionesPorAerolinea($conexion,$codAerolinea)
{
    $consulta = $conexion->prepare("
        SELECT
            codPromocion,
            descripcionPromocion,
            descuentoPromocion,
            estadoPromocion

        FROM Promociones

        WHERE codAerolinea = ?
          AND activoPromocion = 1

        ORDER BY codPromocion DESC
    ");

    $consulta->bind_param(
        "i",
        $codAerolinea
    );

    $consulta->execute();

    return $consulta->get_result();
}


// OBTENER UNA PROMOCIÓN DE UNA AEROLÍNEA

function obtenerPromocion($conexion,$codPromocion,$codAerolinea)
{
    $consulta = $conexion->prepare("
        SELECT
            codPromocion,
            descripcionPromocion,
            descuentoPromocion,
            estadoPromocion

        FROM Promociones

        WHERE codPromocion = ?
          AND codAerolinea = ?
          AND activoPromocion = 1
    ");

    $consulta->bind_param(
        "ii",
        $codPromocion,
        $codAerolinea
    );

    $consulta->execute();

    $resultado = $consulta->get_result();

    return $resultado->fetch_assoc();
}


// CREAR PROMOCIÓN

function crearPromocion($conexion,$descripcion,$descuento,$codAerolinea)
{
    $estadoPromocion = "Pendiente";
    $activoPromocion = 1;

    $consulta = $conexion->prepare("
        INSERT INTO Promociones
        (
            descripcionPromocion,
            descuentoPromocion,
            codAerolinea,
            estadoPromocion,
            activoPromocion
        )

        VALUES (?, ?, ?, ?, ?)
    ");

    $consulta->bind_param(
        "sdisi",
        $descripcion,
        $descuento,
        $codAerolinea,
        $estadoPromocion,
        $activoPromocion
    );

    return $consulta->execute();
}


// MODIFICAR PROMOCIÓN

function modificarPromocion($conexion,$codPromocion,$codAerolinea,$descripcion,$descuento)
{
    $consulta = $conexion->prepare("
        UPDATE Promociones

        SET
            descripcionPromocion = ?,
            descuentoPromocion = ?,
            estadoPromocion = 'Pendiente'

        WHERE codPromocion = ?
          AND codAerolinea = ?
          AND activoPromocion = 1
    ");

    $consulta->bind_param(
        "sdii",
        $descripcion,
        $descuento,
        $codPromocion,
        $codAerolinea
    );

    return $consulta->execute();
}


// BAJA LÓGICA DE PROMOCIÓN

function eliminarPromocion($conexion, $codPromocion, $codAerolinea)
{
    $consulta = $conexion->prepare("
        UPDATE Promociones

        SET
            activoPromocion = 0,
            fechaEliminacionPromocion = NOW()

        WHERE codPromocion = ?
          AND codAerolinea = ?
          AND activoPromocion = 1
    ");

    $consulta->bind_param(
        "ii",
        $codPromocion,
        $codAerolinea
    );

    return $consulta->execute();
}


// APROBAR O RECHAZAR UNA PROMOCIÓN

function cambiarEstadoPromocion($conexion,$codPromocion,$nuevoEstado)
{
    $consulta = "UPDATE Promociones
                 SET estadoPromocion = ?
                 WHERE codPromocion = ?
                 AND activoPromocion = 1";

    $stmt = $conexion->prepare($consulta);

    $stmt->bind_param(
        "si",
        $nuevoEstado,
        $codPromocion
    );

    return $stmt->execute();
}


// CANTIDAD DE PROMOCIONES PENDIENTES

function cantidadPromocionesPendientes($conexion)
{
    $consulta = "SELECT COUNT(*) AS cantidad
                 FROM Promociones
                 WHERE estadoPromocion = 'Pendiente'";

    $resultado = $conexion->query($consulta);

    return $resultado->fetch_assoc()["cantidad"];
}

// CANTIDAD DE PROMOCIONES DE UNA AEROLÍNEA

function cantidadPromocionesPorAerolinea(
    $conexion,
    $codAerolinea
)
{
    $consulta = $conexion->prepare("
        SELECT COUNT(*) AS cantidad

        FROM Promociones

        WHERE codAerolinea = ?
    ");

    $consulta->bind_param(
        "i",
        $codAerolinea
    );

    $consulta->execute();

    $resultado = $consulta->get_result();

    return $resultado->fetch_assoc()["cantidad"];
}


// CANTIDAD DE PROMOCIONES PENDIENTES DE UNA AEROLÍNEA

function cantidadPromocionesPendientesPorAerolinea(
    $conexion,
    $codAerolinea
)
{
    $consulta = $conexion->prepare("
        SELECT COUNT(*) AS cantidad

        FROM Promociones

        WHERE codAerolinea = ?
          AND estadoPromocion = 'Pendiente'
    ");

    $consulta->bind_param(
        "i",
        $codAerolinea
    );

    $consulta->execute();

    $resultado = $consulta->get_result();

    return $resultado->fetch_assoc()["cantidad"];
}