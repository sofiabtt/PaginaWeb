<?php

require "includes/protegerUsuario.php";
include "../php/conexionBD.php";

$codUsuario = (int) $_SESSION["codUsuario"];
$mensaje = null;
$error = null;

if ($_SERVER["REQUEST_METHOD"] === "POST") {
    $nombre = trim($_POST["nombre"] ?? "");
    $telefono = trim($_POST["telefono"] ?? "");
    $claveNueva = $_POST["claveNueva"] ?? "";

    if ($nombre === "" || $telefono === "") {
        $error = "Completá el nombre y el teléfono.";
    } elseif ($claveNueva !== "" && strlen($claveNueva) < 8) {
        $error = "La contraseña debe tener al menos 8 caracteres.";
    } else {
        if ($claveNueva !== "") {
            $hash = password_hash($claveNueva, PASSWORD_DEFAULT);
            $actualizar = $conexion->prepare(
                "UPDATE Usuarios SET nombreUsuario = ?, telefonoUsuario = ?, claveUsuario = ?
                 WHERE codUsuario = ? AND tipoUsuario = 'usuario'"
            );
            $actualizar->bind_param("sssi", $nombre, $telefono, $hash, $codUsuario);
        } else {
            $actualizar = $conexion->prepare(
                "UPDATE Usuarios SET nombreUsuario = ?, telefonoUsuario = ?
                 WHERE codUsuario = ? AND tipoUsuario = 'usuario'"
            );
            $actualizar->bind_param("ssi", $nombre, $telefono, $codUsuario);
        }

        $actualizar->execute();
        $actualizar->close();
        $_SESSION["nombreUsuario"] = $nombre;
        $mensaje = "El perfil se actualizó correctamente.";
    }
}

$consulta = $conexion->prepare(
    "SELECT nombreUsuario, emailUsuario, telefonoUsuario
     FROM Usuarios WHERE codUsuario = ? AND tipoUsuario = 'usuario'"
);
$consulta->bind_param("i", $codUsuario);
$consulta->execute();
$usuario = $consulta->get_result()->fetch_assoc();

?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Nuvia - Mi perfil</title>
    <link rel="stylesheet" href="../css/bootstrap.min.css">
    <link rel="stylesheet" href="../css/bootstrap-icons.css">
    <link rel="stylesheet" href="../css/estilos-admin.css">
    <link rel="icon" href="../imagenes/logo.png" type="image/png">
</head>
<body>
    <?php include "includes/navbarUsuario.php"; ?>
    <main class="contenido-admin">
        <section class="encabezado-contenido">
            <div><h1>Mi perfil</h1><p>Gestioná tu información personal.</p></div>
        </section>

        <?php if ($mensaje) { ?><div class="alert alert-success"><?php echo $mensaje; ?></div><?php } ?>
        <?php if ($error) { ?><div class="alert alert-danger"><?php echo $error; ?></div><?php } ?>

        <section class="perfil-card">
            <form method="POST">
                <div class="mb-3">
                    <label for="nombre" class="form-label">Nombre y apellido</label>
                    <input id="nombre" name="nombre" class="form-control" required
                           value="<?php echo htmlspecialchars($usuario["nombreUsuario"]); ?>">
                </div>
                <div class="mb-3">
                    <label for="email" class="form-label">Email</label>
                    <input id="email" class="form-control" disabled
                           value="<?php echo htmlspecialchars($usuario["emailUsuario"]); ?>">
                </div>
                <div class="mb-3">
                    <label for="telefono" class="form-label">Teléfono</label>
                    <input id="telefono" name="telefono" class="form-control" required
                           value="<?php echo htmlspecialchars($usuario["telefonoUsuario"]); ?>">
                </div>
                <div class="mb-4">
                    <label for="claveNueva" class="form-label">Nueva contraseña (opcional)</label>
                    <input type="password" id="claveNueva" name="claveNueva" class="form-control" minlength="8">
                </div>
                <button class="btn btn-primary" type="submit">Guardar cambios</button>
            </form>
        </section>
    </main>
    <script src="../js/bootstrap.bundle.min.js"></script>
</body>
</html>
<?php $consulta->close(); $conexion->close(); ?>
