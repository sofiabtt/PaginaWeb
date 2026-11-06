<?php

include "conexionBD.php";


// REGISTRAR ACTIVIDAD

function registrarActividad($conexion, $usuarioActividad, $accionActividad)
{
    $consulta = "INSERT INTO actividad
                 (usuarioActividad, accionActividad)
                 VALUES (?, ?)";

    $stmt = $conexion->prepare($consulta);

    $stmt->bind_param(
        "ss",
        $usuarioActividad,
        $accionActividad
    );

    return $stmt->execute();
}


// OBTENER ACTIVIDADES DE LOS ÚLTIMOS 5 DÍAS

function obtenerActividadesRecientes($conexion)
{
    $consulta = "SELECT *
                 FROM actividad
                 WHERE fechaActividad >= NOW() - INTERVAL 5 DAY
                 ORDER BY fechaActividad DESC";

    return $conexion->query($consulta);
}

// CONTAR ACTIVIDADES DEL HISTORIAL

function cantidadActividadesHistorial($conexion, $busqueda = "", $fechaDesde = "", $fechaHasta = "")
{
    $consulta = "SELECT COUNT(*) AS total
                 FROM actividad
                 WHERE (? = '' OR usuarioActividad LIKE CONCAT('%', ?, '%'))
                 AND (? = '' OR fechaActividad >= ?)
                 AND (? = '' OR fechaActividad < DATE_ADD(?, INTERVAL 1 DAY))";

    $stmt = $conexion->prepare($consulta);

    $stmt->bind_param(
        "ssssss",
        $busqueda,
        $busqueda,
        $fechaDesde,
        $fechaDesde,
        $fechaHasta,
        $fechaHasta
    );

    $stmt->execute();

    $resultado = $stmt->get_result()->fetch_assoc();

    return (int) $resultado["total"];
}


// OBTENER ACTIVIDADES DEL HISTORIAL CON PAGINACIÓN

function obtenerHistorialActividades(
    $conexion,
    $limite,
    $inicio,
    $busqueda = "",
    $fechaDesde = "",
    $fechaHasta = ""
) {
    $consulta = "SELECT *
                 FROM actividad
                 WHERE (? = '' OR usuarioActividad LIKE CONCAT('%', ?, '%'))
                 AND (? = '' OR fechaActividad >= ?)
                 AND (? = '' OR fechaActividad < DATE_ADD(?, INTERVAL 1 DAY))
                 ORDER BY fechaActividad DESC
                 LIMIT ? OFFSET ?";

    $stmt = $conexion->prepare($consulta);

    $stmt->bind_param(
        "ssssssii",
        $busqueda,
        $busqueda,
        $fechaDesde,
        $fechaDesde,
        $fechaHasta,
        $fechaHasta,
        $limite,
        $inicio
    );

    $stmt->execute();

    return $stmt->get_result();
}