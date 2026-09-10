<?php

require "../includes/protegerUsuario.php";
include "../../php/conexionBD.php";

$codVuelo = filter_input(INPUT_GET, "id", FILTER_VALIDATE_INT);
if (!$codVuelo) {
    header("Location: buscarVuelos.php");
    exit();
}

$consulta = $conexion->prepare(
    "SELECT v.*, a.nombreAerolinea,
            p.descripcionPromocion, p.descuentoPromocion
     FROM Vuelos v
     INNER JOIN Aerolineas a ON a.codAerolinea = v.codAerolinea
     LEFT JOIN Promociones p
       ON p.codAerolinea = v.codAerolinea
      AND p.estadoPromocion = 'Aprobada'
      AND p.activoPromocion = 1
     WHERE v.codVuelo = ? AND v.activoVuelo = 1
     LIMIT 1"
);
$consulta->bind_param("i", $codVuelo);
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
        <section class="encabezado-contenido">
            <div><h1><?php echo htmlspecialchars($vuelo["origenVuelo"] . " - " . $vuelo["destinoVuelo"]); ?></h1><p><?php echo htmlspecialchars($vuelo["nombreAerolinea"]); ?></p></div>
            <a class="btn btn-secondary" href="buscarVuelos.php"><i class="bi bi-arrow-left"></i> Volver</a>
        </section>
        <?php if (isset($_GET["error"])) { ?><div class="alert alert-danger">No fue posible reservar ese vuelo. Comprobá que todavía tenga asientos.</div><?php } ?>
        <section class="card border-0 shadow-sm mx-auto" style="max-width: 760px;">
            <div class="card-body p-4 p-md-5">
                <div class="row g-4">
                    <div class="col-md-6"><strong>Fecha y hora</strong><p><?php echo date("d/m/Y", strtotime($vuelo["fechaSalidaVuelo"])) . " " . htmlspecialchars($vuelo["horaSalidaVuelo"]); ?></p></div>
                    <div class="col-md-6"><strong>Asientos disponibles</strong><p><?php echo (int) $vuelo["asientosDisponibles"]; ?></p></div>
                    <div class="col-md-6"><strong>Precio</strong><p>$<?php echo number_format((float) $vuelo["precioVuelo"], 0, ",", "."); ?></p></div>
                    <div class="col-md-6"><strong>Promoción aplicable</strong>
                        <?php if ($descuento > 0) { ?>
                            <p><?php echo htmlspecialchars($vuelo["descripcionPromocion"]); ?> (<?php echo $descuento; ?>%)</p>
                            <p class="fw-bold">Precio promocional: $<?php echo number_format($precioFinal, 0, ",", "."); ?></p>
                        <?php } else { ?><p>Sin promoción vigente.</p><?php } ?>
                    </div>
                </div>
                <form method="POST" action="../reservas/crearReserva.php" class="mt-3">
                    <input type="hidden" name="codVuelo" value="<?php echo (int) $vuelo["codVuelo"]; ?>">
                    <button class="btn btn-primary" type="submit" <?php echo (int) $vuelo["asientosDisponibles"] < 1 ? "disabled" : ""; ?>>Reservar vuelo</button>
                </form>
            </div>
        </section>
    </main>
    <script src="../../js/bootstrap.bundle.min.js"></script>
</body>
</html>
<?php $consulta->close(); $conexion->close(); ?>
