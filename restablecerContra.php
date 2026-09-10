<?php

include "php/conexionBD.php";
$token = $_POST["token"] ?? $_GET["token"] ?? "";
$valido = preg_match('/^[a-f0-9]{64}$/', $token) === 1;
$usuario = null;

if ($valido) {
    $consulta = $conexion->prepare("SELECT codUsuario FROM Usuarios WHERE tokenVerificacion = ? AND fechaVerificacion >= NOW() AND verificado = 1");
    $consulta->bind_param("s", $token); $consulta->execute(); $usuario = $consulta->get_result()->fetch_assoc(); $consulta->close();
}

if ($_SERVER["REQUEST_METHOD"] === "POST" && $usuario) {
    $clave = $_POST["clave"] ?? "";
    if (strlen($clave) >= 8) {
        $hash = password_hash($clave, PASSWORD_DEFAULT);
        $actualizar = $conexion->prepare("UPDATE Usuarios SET claveUsuario = ?, tokenVerificacion = NULL, fechaVerificacion = NOW() WHERE codUsuario = ?");
        $actualizar->bind_param("si", $hash, $usuario["codUsuario"]); $actualizar->execute(); $actualizar->close(); $conexion->close();
        header("Location: inicioSesion.php?clave=actualizada"); exit();
    }
    $error = "La contraseña debe tener al menos 8 caracteres.";
}
?>
<!DOCTYPE html><html lang="es"><head><meta charset="UTF-8"><meta name="viewport" content="width=device-width, initial-scale=1.0"><title>Nuvia - Nueva contraseña</title><link rel="stylesheet" href="css/bootstrap.min.css"><link rel="stylesheet" href="css/estilos.css"></head><body><main class="d-flex justify-content-center align-items-center"><section class="rectangulo-formulario"><h1 class="text-center mb-4 texto-negro">Nueva contraseña</h1>
<?php if (!$usuario) { ?><div class="alert alert-danger">El enlace es inválido o venció.</div><a class="btn btn-primary" href="recuperarContrasena.php">Solicitar otro enlace</a><?php } else { ?>
<?php if (isset($error)) { ?><div class="alert alert-danger"><?php echo $error; ?></div><?php } ?><form method="POST"><input type="hidden" name="token" value="<?php echo htmlspecialchars($token); ?>"><div class="mb-4"><label for="clave" class="form-label texto-negro">Nueva contraseña</label><input type="password" id="clave" name="clave" class="form-control" minlength="8" required></div><button class="btn btn-primary" type="submit">Guardar contraseña</button></form><?php } ?>
</section></main></body></html>
<?php $conexion->close(); ?>