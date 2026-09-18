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


// APROBAR O RECHAZAR UNA PROMOCIÓN

function cambiarEstadoPromocion(
    $conexion,
    $codPromocion,
    $nuevoEstado
)
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