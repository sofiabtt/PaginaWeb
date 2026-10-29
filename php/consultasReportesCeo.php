<?php

include "conexionBD.php";

function filtrosReporteCeo($codAerolinea, $desde, $hasta, $estado)
{
    $where = " WHERE v.codAerolinea = ? ";
    $tipos = "i";
    $params = [$codAerolinea];

    if ($desde !== "") {
        $where .= " AND DATE(r.fechaReserva) >= ? ";
        $tipos .= "s";
        $params[] = $desde;
    }

    if ($hasta !== "") {
        $where .= " AND DATE(r.fechaReserva) <= ? ";
        $tipos .= "s";
        $params[] = $hasta;
    }

    if ($estado !== "") {
        $where .= " AND r.estadoReserva = ? ";
        $tipos .= "s";
        $params[] = $estado;
    }

    return [$where, $tipos, $params];
}


// RESUMEN GENERAL DE RESERVAS DE LA AEROLÍNEA

function obtenerResumenReservasAerolinea($conexion, $codAerolinea, $desde = "", $hasta = "", $estado = "")
{
    list($where, $tipos, $params) = filtrosReporteCeo($codAerolinea, $desde, $hasta, $estado);

    $consulta = "
        SELECT
            COUNT(r.codReserva) AS totalReservas,
            COALESCE(SUM(CASE WHEN r.estadoReserva = 'confirmada' THEN 1 ELSE 0 END), 0) AS confirmadas,
            COALESCE(SUM(CASE WHEN r.estadoReserva = 'confirmada' THEN r.cantidadPasajerosReserva ELSE 0 END), 0) AS pasajeros,
            COALESCE(SUM(CASE WHEN r.estadoReserva = 'confirmada' THEN r.precioFinalReserva ELSE 0 END), 0) AS ingresos
        FROM reservas r
        INNER JOIN vuelos v ON v.codVuelo = r.codVuelo
        $where
    ";

    $stmt = $conexion->prepare($consulta);
    $stmt->bind_param($tipos, ...$params);
    $stmt->execute();

    return $stmt->get_result()->fetch_assoc();
}


// DETALLE DE RESERVAS DE LA AEROLÍNEA

function obtenerReservasAerolinea($conexion, $codAerolinea, $desde = "", $hasta = "", $estado = "")
{
    list($where, $tipos, $params) = filtrosReporteCeo($codAerolinea, $desde, $hasta, $estado);

    $consulta = "
        SELECT
            r.codReserva,
            r.fechaReserva,
            r.estadoReserva,
            r.cantidadPasajerosReserva,
            r.precioFinalReserva,
            v.codVuelo,
            v.origenVuelo,
            v.destinoVuelo,
            v.fechaSalidaVuelo,
            v.horaSalidaVuelo
        FROM reservas r
        INNER JOIN vuelos v ON v.codVuelo = r.codVuelo
        $where
        ORDER BY r.fechaReserva DESC
    ";

    $stmt = $conexion->prepare($consulta);
    $stmt->bind_param($tipos, ...$params);
    $stmt->execute();

    return $stmt->get_result();
}


// VENTAS CONFIRMADAS AGRUPADAS POR VUELO

function obtenerVentasPorVuelo($conexion, $codAerolinea, $desde = "", $hasta = "")
{
    list($where, $tipos, $params) = filtrosReporteCeo($codAerolinea, $desde, $hasta, "confirmada");

    $consulta = "
        SELECT
            v.codVuelo,
            v.origenVuelo,
            v.destinoVuelo,
            v.fechaSalidaVuelo,
            COUNT(r.codReserva) AS reservas,
            SUM(r.cantidadPasajerosReserva) AS pasajeros,
            SUM(r.precioFinalReserva) AS ingresos
        FROM reservas r
        INNER JOIN vuelos v ON v.codVuelo = r.codVuelo
        $where
        GROUP BY v.codVuelo, v.origenVuelo, v.destinoVuelo, v.fechaSalidaVuelo
        ORDER BY ingresos DESC
    ";

    $stmt = $conexion->prepare($consulta);
    $stmt->bind_param($tipos, ...$params);
    $stmt->execute();

    return $stmt->get_result();
}
