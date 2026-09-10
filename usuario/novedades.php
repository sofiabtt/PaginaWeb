<?php

require "includes/protegerUsuario.php";
include "../php/conexionBD.php";
$resultado = $conexion->query(
    "SELECT textoNovedad, fechaPublicacionNovedad, fechaExpiracionNovedad
     FROM Novedades
     WHERE activoNovedad = 1
       AND fechaPublicacionNovedad <= CURDATE()
       AND fechaExpiracionNovedad >= CURDATE()
     ORDER BY fechaPublicacionNovedad DESC"
);
?>
<!DOCTYPE html><html lang="es"><head><meta charset="UTF-8"><meta name="viewport" content="width=device-width, initial-scale=1.0"><title>Nuvia - Novedades</title><link rel="stylesheet" href="../css/bootstrap.min.css"><link rel="stylesheet" href="../css/bootstrap-icons.css"><link rel="stylesheet" href="../css/estilos-admin.css"></head><body>
<?php include "includes/navbarUsuario.php"; ?>
<main class="contenido-admin"><section class="encabezado-contenido"><div><h1>Novedades</h1><p>Anuncios importantes para pasajeros.</p></div></section><div class="row g-4">
<?php if ($resultado->num_rows === 0) { ?><div class="col-12"><div class="alert alert-info">No hay novedades vigentes.</div></div><?php } ?>
<?php while ($novedad = $resultado->fetch_assoc()) { ?><div class="col-md-6"><article class="card border-0 shadow-sm h-100"><div class="card-body p-4"><i class="bi bi-megaphone fs-3"></i><p class="mt-3 mb-2"><?php echo nl2br(htmlspecialchars($novedad["textoNovedad"])); ?></p><small class="text-muted">Vigente hasta <?php echo date("d/m/Y", strtotime($novedad["fechaExpiracionNovedad"])); ?></small></div></article></div><?php } ?>
</div></main><script src="../js/bootstrap.bundle.min.js"></script></body></html>
<?php $conexion->close(); ?>
