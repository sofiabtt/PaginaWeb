<?php

include "conexionBD.php";


// OBTENER HISTORIAL DE COMPRAS DE UN USUARIO

function obtenerHistorialCompras($conexion, $codUsuario)
{
    $consulta = $conexion->prepare(
        "SELECT r.codReserva,
                r.fechaReservae,
                v.origenVuelo,
                v.destinoVuelo,
                v.fechaSalidaVuelo,
                r.precioFinalReserva,
                a.nombreAerolinea
         FROM Reservas r
         INNER JOIN Vuelos v ON v.codVuelo = r.codVuelo
         INNER JOIN Aerolineas a ON a.codAerolinea = v.codAerolinea
         WHERE r.codUsuario = ?
           AND r.estadoReserva = 'confirmada'
         ORDER BY r.fechaReservae DESC, r.codReserva DESC"
    );

    $consulta->bind_param(
        "i",
        $codUsuario
    );

    $consulta->execute();

    return $consulta->get_result();
}
// OBTENER RESERVAS ACTIVAS DE UN USUARIO

function obtenerReservasUsuario($conexion, $codUsuario)
{
    $consulta = $conexion->prepare(
        "SELECT r.codReserva,
                r.fechaReserva,
                r.estadoReserva,
                v.origenVuelo,
                v.destinoVuelo,
                v.fechaSalidaVuelo,
                v.horaSalidaVuelo,
                r.precioFinalReserva,
                a.nombreAerolinea
         FROM Reservas r
         INNER JOIN Vuelos v ON v.codVuelo = r.codVuelo
         INNER JOIN Aerolineas a ON a.codAerolinea = v.codAerolinea
         WHERE r.codUsuario = ?
           AND r.estadoReserva <> 'cancelada'
         ORDER BY v.fechaSalidaVuelo, v.horaSalidaVuelo"
    );

    $consulta->bind_param(
        "i",
        $codUsuario
    );

    $consulta->execute();

    return $consulta->get_result();
}

// OBTENER DETALLE DE UNA RESERVA

function obtenerDetalleReserva($conexion, $codReserva, $codUsuario)
{
    $consulta = $conexion->prepare(
        "SELECT
            r.codReserva,
            r.fechaReserva,
            r.estadoReserva,
            r.precioFinalReserva,
            v.codVuelo,
            v.origenVuelo,
            v.destinoVuelo,
            v.fechaSalidaVuelo,
            v.horaSalidaVuelo,
            a.nombreAerolinea
         FROM Reservas r
         INNER JOIN Vuelos v
            ON v.codVuelo = r.codVuelo
         INNER JOIN Aerolineas a
            ON a.codAerolinea = v.codAerolinea
         WHERE r.codReserva = ?
           AND r.codUsuario = ?"
    );

    $consulta->bind_param(
        "ii",
        $codReserva,
        $codUsuario
    );

    $consulta->execute();

    $resultado = $consulta->get_result();

    return $resultado->fetch_assoc();
}

// CONFIRMAR RESERVA

function confirmarReserva($conexion, $codReserva, $codUsuario)
{
    $consulta = $conexion->prepare(
        "UPDATE Reservas
         SET estadoReserva = 'confirmada'
         WHERE codReserva = ?
           AND codUsuario = ?
           AND estadoReserva = 'pendiente de pago'"
    );

    $consulta->bind_param(
        "ii",
        $codReserva,
        $codUsuario
    );

    $consulta->execute();

    return $consulta->affected_rows === 1;
}

// CANCELAR RESERVA

function cancelarReserva($conexion, $codReserva, $codUsuario)
{
    $conexion->begin_transaction();

    try {

        $consulta = $conexion->prepare(
            "SELECT r.codVuelo,
                    r.estadoReserva,
                    r.cantidadPasajerosReserva,
                    v.fechaSalidaVuelo,
                    v.horaSalidaVuelo
             FROM Reservas r
             INNER JOIN Vuelos v
                ON v.codVuelo = r.codVuelo
             WHERE r.codReserva = ?
               AND r.codUsuario = ?
             FOR UPDATE"
        );

        $consulta->bind_param(
            "ii",
            $codReserva,
            $codUsuario
        );

        $consulta->execute();

        $reserva = $consulta
            ->get_result()
            ->fetch_assoc();


        $salida = $reserva
            ? strtotime(
                $reserva["fechaSalidaVuelo"]
                . " "
                . $reserva["horaSalidaVuelo"]
            )
            : 0;


        if (
            !$reserva ||
            $reserva["estadoReserva"] !== "pendiente de pago" ||
            ($salida - time()) < 72 * 60 * 60
        ) {
            throw new RuntimeException(
                "La reserva no puede cancelarse."
            );
        }


        $cancelar = $conexion->prepare(
            "UPDATE Reservas
             SET estadoReserva = 'cancelada'
             WHERE codReserva = ?
               AND codUsuario = ?"
        );

        $cancelar->bind_param(
            "ii",
            $codReserva,
            $codUsuario
        );

        $cancelar->execute();


        $devolver = $conexion->prepare(
            "UPDATE Vuelos
             SET asientosDisponibles =
                 asientosDisponibles + ?
             WHERE codVuelo = ?"
        );

        $devolver->bind_param(
            "ii",
            $reserva["cantidadPasajerosReserva"],
            $reserva["codVuelo"]
        );

        $devolver->execute();


        $conexion->commit();

        return true;

    } catch (Throwable $e) {

        $conexion->rollback();

        return false;
    }
}

// CREAR RESERVA

function crearReserva($conexion, $codUsuario, $codVuelo, $cantidadPasajeros)
{
    $conexion->begin_transaction();

    try {

        // Bloqueamos el vuelo mientras se crea la reserva
        $consultaVuelo = $conexion->prepare(
            "SELECT precioVuelo, asientosDisponibles
             FROM Vuelos
             WHERE codVuelo = ?
               AND activoVuelo = 1
             FOR UPDATE"
        );

        $consultaVuelo->bind_param(
            "i",
            $codVuelo
        );

        $consultaVuelo->execute();

        $vuelo = $consultaVuelo
            ->get_result()
            ->fetch_assoc();


        if (!$vuelo) {
            throw new RuntimeException(
                "No se encontró el vuelo."
            );
        }


        if ($vuelo["asientosDisponibles"] < $cantidadPasajeros) {
            throw new RuntimeException(
                "No hay suficientes asientos disponibles."
            );
        }


        $precioFinal =
            $vuelo["precioVuelo"]
            * $cantidadPasajeros;

        $estado = "pendiente de pago";


        // Creamos la reserva
        $consulta = $conexion->prepare(
            "INSERT INTO Reservas
            (
                codUsuario,
                codVuelo,
                fechaReserva,
                estadoReserva,
                precioFinalReserva,
                cantidadPasajerosReserva
            )
            VALUES (?, ?, NOW(), ?, ?, ?)"
        );

        $consulta->bind_param(
            "iisdi",
            $codUsuario,
            $codVuelo,
            $estado,
            $precioFinal,
            $cantidadPasajeros
        );

        $consulta->execute();

        $codReserva = $conexion->insert_id;


        // Descontamos los asientos
        $actualizarAsientos = $conexion->prepare(
            "UPDATE Vuelos
             SET asientosDisponibles =
                 asientosDisponibles - ?
             WHERE codVuelo = ?
               AND asientosDisponibles >= ?"
        );

        $actualizarAsientos->bind_param(
            "iii",
            $cantidadPasajeros,
            $codVuelo,
            $cantidadPasajeros
        );

        $actualizarAsientos->execute();


        if ($actualizarAsientos->affected_rows !== 1) {
            throw new RuntimeException(
                "No se pudieron actualizar los asientos."
            );
        }


        $conexion->commit();


        return [
            "ok" => true,
            "codReserva" => $codReserva
        ];


    } catch (Throwable $e) {

        $conexion->rollback();

        return [
            "ok" => false,
            "mensaje" => $e->getMessage()
        ];
    }
}

// OBTENER DETALLE DE VUELO CONFIRMADO DEL USUARIO

function obtenerVueloUsuario($conexion, $codVuelo, $codUsuario)
{
    $consulta = $conexion->prepare(
        "SELECT 
            v.*,
            a.nombreAerolinea,
            p.descripcionPromocion,
            p.descuentoPromocion,
            r.cantidadPasajerosReserva,
            r.precioFinalReserva,
            r.fechaReserva,
            r.estadoReserva
         FROM Vuelos v
         INNER JOIN Aerolineas a
            ON a.codAerolinea = v.codAerolinea
         INNER JOIN Reservas r
            ON r.codVuelo = v.codVuelo
         LEFT JOIN Promociones p
            ON p.codAerolinea = v.codAerolinea
            AND p.estadoPromocion = 'Aprobada'
            AND p.activoPromocion = 1
         WHERE v.codVuelo = ?
           AND r.codUsuario = ?
           AND r.estadoReserva = 'confirmada'
         ORDER BY r.fechaReserva DESC
         LIMIT 1"
    );

    $consulta->bind_param(
        "ii",
        $codVuelo,
        $codUsuario
    );

    $consulta->execute();

    return $consulta
        ->get_result()
        ->fetch_assoc();
}