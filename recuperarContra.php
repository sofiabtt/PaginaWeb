<!DOCTYPE html>
<html lang="es"><head><meta charset="UTF-8"><meta name="viewport" content="width=device-width, initial-scale=1.0"><title>Nuvia - Recuperar contraseña</title><link rel="stylesheet" href="css/bootstrap.min.css"><link rel="stylesheet" href="css/estilos.css"><link rel="icon" href="imagenes/logo.png" type="image/png"></head><body>
<header class="barra-superior navbar navbar-expand-lg"><a class="navbar-brand" href="home.php"><img src="imagenes/logo.png" alt="Logo" width="60" height="60"><span class="fw-bold fs-4">Nuvia</span></a></header>
<main class="d-flex justify-content-center align-items-center"><section class="rectangulo-formulario"><h1 class="text-center mb-4 texto-negro">Recuperar contraseña</h1>
<?php if (isset($_GET["enviado"])) { ?><div class="alert alert-success">Si el correo está registrado, enviamos un enlace para cambiar la contraseña.</div><?php } ?>
<form action="php/enviarRecuperacion.php" method="POST"><div class="mb-4"><label for="email" class="form-label texto-negro">Correo electrónico</label><input type="email" id="email" name="email" class="form-control" required></div><div class="d-flex justify-content-between"><a href="inicioSesion.php" class="btn btn-outline-primary">Volver</a><button class="btn btn-primary" type="submit">Enviar enlace</button></div></form>
</section></main></body></html>
