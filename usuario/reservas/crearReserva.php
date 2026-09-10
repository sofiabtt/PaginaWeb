<?php

require "../includes/protegerUsuario.php";
include "../../php/conexionBD.php";

$codUsuario = (int) $_SESSION["codUsuario"];
$consulta = $conexion->prepare(
    "SELECT r.codReserva, r.fechaReservae, r.estadoReserva,
            v.origenVuelo, v.destinoVuelo, v.fechaSalidaVuelo,
            v.horaSalidaVuelo, r.precioFinalReserva, a.nombreAerolinea
     FROM Reservas r
     INNER JOIN Vuelos v ON v.codVuelo = r.codVuelo
     INNER JOIN Aerolineas a ON a.codAerolinea = v.codAerolinea
     WHERE r.codUsuario = ? AND r.estadoReserva <> 'cancelada'
     ORDER BY v.fechaSalidaVuelo, v.horaSalidaVuelo"
);
$consulta->bind_param("i", $codUsuario);
$consulta->execute();
$resultado = $consulta->get_result();

?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8"><meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Nuvia - Mis reservas</title>
    <link rel="stylesheet" href="../../css/bootstrap.min.css"><link rel="stylesheet" href="../../css/bootstrap-icons.css"><link rel="stylesheet" href="../../css/estilos-admin.css">
</head>
<body>
    <?php include "../includes/navbarUsuario.php"; ?>
    <main class="contenido-admin">
        <section class="encabezado-contenido"><div><h1>Mis reservas</h1><p>Consultá, confirmá o cancelá tus reservas.</p></div><a class="btn btn-primary" href="../vuelos/buscarVuelos.php">Buscar vuelos</a></section>
        <?php if (isset($_GET["creada"])) { ?><div class="alert alert-success">La reserva se creó y quedó pendiente de pago.</div><?php } ?>
        <?php if (isset($_GET["confirmada"])) { ?><div class="alert alert-success">La reserva se confirmó correctamente.</div><?php } ?>
        <?php if (isset($_GET["cancelada"])) { ?><div class="alert alert-success">La reserva se canceló correctamente.</div><?php } ?>
        <?php if (isset($_GET["error"])) { ?><div class="alert alert-danger">No se pudo realizar la operación. Recordá que solo podés cancelar hasta 72 horas antes.</div><?php } ?>
        <section class="tabla-contenedor">
            <table class="table align-middle">
                <thead><tr><th>Código</th><th>Vuelo</th><th>Salida</th><th>Precio</th><th>Estado</th><th>Acciones</th></tr></thead>
                <tbody>
                    <?php if ($resultado->num_rows === 0) { ?><tr><td colspan="6" class="text-center text-muted py-4">No tenés reservas activas.</td></tr><?php } ?>
                    <?php while ($reserva = $resultado->fetch_assoc()) { ?>
                        <tr>
                            <td><?php echo (int) $reserva["codReserva"]; ?></td>
                            <td><?php echo htmlspecialchars($reserva["origenVuelo"] . " - " . $reserva["destinoVuelo"]); ?><br><small><?php echo htmlspecialchars($reserva["nombreAerolinea"]); ?></small></td>
                            <td><?php echo date("d/m/Y", strtotime($reserva["fechaSalidaVuelo"])) . " " . htmlspecialchars($reserva["horaSalidaVuelo"]); ?></td>
                            <td>$<?php echo number_format((float) $reserva["precioFinalReserva"], 0, ",", "."); ?></td>
                            <td><span class="badge <?php echo $reserva["estadoReserva"] === "confirmada" ? "bg-success" : "bg-warning text-dark"; ?>"><?php echo htmlspecialchars(ucfirst($reserva["estadoReserva"])); ?></span></td>
                            <td class="d-flex gap-2">
                                <?php if ($reserva["estadoReserva"] === "pendiente de pago") { ?>
                                    <form method="POST" action="confirmarReserva.php"><input type="hidden" name="id" value="<?php echo (int) $reserva["codReserva"]; ?>"><button class="btn btn-sm btn-success" type="submit">Confirmar</button></form>
                                <?php } ?>
                                <form method="POST" action="cancelarReserva.php" onsubmit="return confirm('¿Querés cancelar esta reserva?');"><input type="hidden" name="id" value="<?php echo (int) $reserva["codReserva"]; ?>"><button class="btn btn-sm btn-outline-danger" type="submit">Cancelar</button></form>
                            </td>
                        </tr>
                    <?php } ?>
                </tbody>
            </table>
        </section>
    </main>
    <script src="../../js/bootstrap.bundle.min.js"></script>
</body>
</html>
<?php $consulta->close(); $conexion->close(); ?>
