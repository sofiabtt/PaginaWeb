<?php

require "includes/protegerUsuario.php";
include "../php/consultasReservas.php";

$codUsuario = (int) $_SESSION["codUsuario"];

$resultado = obtenerHistorialCompras($conexion, $codUsuario);

?>
<!DOCTYPE html><html lang="es"><head><meta charset="UTF-8"><meta name="viewport" content="width=device-width, initial-scale=1.0"><title>Nuvia - Historial</title>
<link rel="stylesheet" href="../css/bootstrap.min.css">
<link rel="stylesheet" href="../css/bootstrap-icons.css">
<link rel="stylesheet" href="../css/estilos-admin.css">
<link rel="stylesheet" href="../css/navbar.css">
</head><body>
<?php include ("../includes/navbar.php"); ?>
<main class="contenido-admin"><section class="encabezado-contenido"><div><h1>Historial de compras</h1><p>Reservas que fueron confirmadas.</p></div></section><section class="tabla-contenedor"><table class="table align-middle"><thead><tr><th>Reserva</th><th>Aerolínea</th><th>Vuelo</th><th>Salida</th><th>Importe</th></tr></thead><tbody>
<?php if ($resultado->num_rows === 0) { ?><tr><td colspan="5" class="text-center text-muted py-4">Todavía no tenés compras confirmadas.</td></tr><?php } ?>
<?php while ($compra = $resultado->fetch_assoc()) { ?><tr><td>#<?php echo (int) $compra["codReserva"]; ?></td><td><?php echo htmlspecialchars($compra["nombreAerolinea"]); ?></td><td><?php echo htmlspecialchars($compra["origenVuelo"] . " - " . $compra["destinoVuelo"]); ?></td><td><?php echo date("d/m/Y", strtotime($compra["fechaSalidaVuelo"])); ?></td><td>$<?php echo number_format((float) $compra["precioFinalReserva"], 0, ",", "."); ?></td></tr><?php } ?>
</tbody></table></section></main><script src="../js/bootstrap.bundle.min.js"></script></body></html>
<?php $consulta->close(); 
