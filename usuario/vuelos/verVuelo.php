<?php

require "../includes/protegerUsuario.php";
include "../../php/conexionBD.php";

$codVuelo = filter_input(INPUT_GET, "id", FILTER_VALIDATE_INT);
if (!$codVuelo) {
    header("Location: buscarVuelos.php");
    exit();
}

$codUsuario = (int) $_SESSION["codUsuario"];

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

$vuelo = $consulta->get_result()->fetch_assoc();

if (!$vuelo) {
    header("Location: buscarVuelos.php");
    exit();
}
$descuento = (float) ($vuelo["descuentoPromocion"] ?? 0);
$precioFinal = (float) $vuelo["precioVuelo"] * (1 - $descuento / 100);

?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8"><meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Nuvia - Detalle del vuelo</title>
    <link rel="stylesheet" href="../../css/bootstrap.min.css"><link rel="stylesheet" href="../../css/bootstrap-icons.css"><link rel="stylesheet" href="../../css/estilos-admin.css">
</head>
<body>
    <?php include "../includes/navbarUsuario.php"; ?>
    <main class="contenido-admin">
        <section class="encabezado-contenido" style="display: block;">

                    <div>
                        <h1>
                            <?php
                            echo htmlspecialchars(
                                $vuelo["origenVuelo"] . " - " . $vuelo["destinoVuelo"]
                            );
                            ?>
                        </h1>

                        <p>
                            <?php
                            echo htmlspecialchars(
                                $vuelo["nombreAerolinea"]
                            );
                            ?>
                        </p>
                    </div>

                    <a
                        class="btn btn-secondary"
                        href="buscarVuelos.php"
                        style="min-width: 110px; white-space: nowrap; margin-top: 10px;"
                    >
                        <i class="bi bi-arrow-left"></i>
                        Volver
                    </a>

                </section>
                <section
            class="card border-0 shadow-sm mx-auto"
            style="max-width: 850px;"
        >
            <div class="card-body p-4 p-md-5">

                <h3 class="mb-4">
                    Información del viaje
                </h3>

                <div class="row g-4">

                    <div class="col-md-6">
                        <strong>Desde</strong>
                        <p>
                            <?php
                            echo htmlspecialchars(
                                $vuelo["origenVuelo"]
                            );
                            ?>
                        </p>
                    </div>

                    <div class="col-md-6">
                        <strong>Hasta</strong>
                        <p>
                            <?php
                            echo htmlspecialchars(
                                $vuelo["destinoVuelo"]
                            );
                            ?>
                        </p>
                    </div>

                    <div class="col-md-6">
                        <strong>Fecha de salida</strong>
                        <p>
                            <?php
                            echo date(
                                "d/m/Y",
                                strtotime($vuelo["fechaSalidaVuelo"])
                            );
                            ?>
                        </p>
                    </div>

                    <div class="col-md-6">
                        <strong>Hora de salida</strong>
                        <p>
                            <?php
                            echo htmlspecialchars(
                                $vuelo["horaSalidaVuelo"]
                            );
                            ?>
                        </p>
                    </div>

                    <div class="col-md-6">
                        <strong>Aerolínea</strong>
                        <p>
                            <?php
                            echo htmlspecialchars(
                                $vuelo["nombreAerolinea"]
                            );
                            ?>
                        </p>
                    </div>

                    <div class="col-md-6">
                        <strong>Cantidad de pasajeros</strong>
                        <p>
                            <?php
                            echo (int)
                                $vuelo["cantidadPasajerosReserva"];
                            ?>
                        </p>
                    </div>

                    <div class="col-md-6">
                        <strong>Precio pagado</strong>
                        <p class="fw-bold">
                            $
                            <?php
                            echo number_format(
                                (float) $vuelo["precioFinalReserva"],
                                0,
                                ",",
                                "."
                            );
                            ?>
                        </p>
                    </div>

                    <div class="col-md-6">
                        <strong>Estado de la reserva</strong>
                        <p>
                            <span class="badge bg-success">
                                Confirmada
                            </span>
                        </p>
                    </div>

                    <div class="col-md-12">
                        <strong>Promoción</strong>

                        <?php if ($descuento > 0) { ?>

                            <p>
                                <?php
                                echo htmlspecialchars(
                                    $vuelo["descripcionPromocion"]
                                );
                                ?>
                                (<?php echo $descuento; ?>%)
                            </p>

                            <p class="fw-bold">
                                Precio promocional:
                                $
                                <?php
                                echo number_format(
                                    $precioFinal,
                                    0,
                                    ",",
                                    "."
                                );
                                ?>
                            </p>

                        <?php } else { ?>

                            <p>Sin promoción aplicada.</p>

                        <?php } ?>

                    </div>

                </div>

            </div>
        </section>
    </main>
    <script src="../../js/bootstrap.bundle.min.js"></script>
</body>
</html>
<?php $consulta->close(); $conexion->close(); ?>
