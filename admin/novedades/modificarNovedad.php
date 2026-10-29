<?php

require_once __DIR__ . '/../../includes/verificarRol.php';
verificarRol('administrador');

include "../../php/conexionBD.php";
include "../../php/consultasNovedades.php";

$error = "";

//OBTENER NOVEDAD (por GET al entrar, por POST al guardar)
$id = isset($_GET["id"]) ? intval($_GET["id"]) : 0;

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $id = isset($_POST["codNovedad"]) ? intval($_POST["codNovedad"]) : 0;
}

$novedad = ($id > 0) ? obtenerNovedad($conexion, $id) : null;


// Solo se pueden modificar novedades activas
if ($novedad && $novedad["activoNovedad"] != 1) {
    $novedad = null;
}

if ($novedad && $_SERVER["REQUEST_METHOD"] == "POST") {
    $texto = trim($_POST["textoNovedad"] ?? "");
    $fechaPublicacion = $_POST["fechaPublicacionNovedad"] ?? "";
    $fechaExpiracion = $_POST["fechaExpiracionNovedad"] ?? "";

    if (empty($texto) || empty($fechaPublicacion) || empty($fechaExpiracion)) {
        $error = "Todos los campos son obligatorios.";

    } elseif (mb_strlen($texto) > 200) {
        $error = "El texto no puede superar los 200 caracteres.";

    } elseif ($fechaExpiracion < $fechaPublicacion) {
        $error = "La fecha de expiración no puede ser anterior a la fecha de publicación.";

    } elseif ($fechaPublicacion != $novedad["fechaPublicacionNovedad"] && strtotime($fechaPublicacion) < strtotime(date("Y-m-d"))) {
        // Si la fecha de publicación no cambió se respeta la original, aunque ya haya pasado (la novedad ya puede estar vigente).
        $error = "La fecha de publicación no puede ser anterior a la fecha actual.";

    } elseif ($fechaExpiracion < date("Y-m-d")) {
        $error = "La fecha de expiración no puede ser anterior a la fecha actual.";
    }

    if (empty($error)) {
        if (modificarNovedad($conexion, $id, $texto, $fechaPublicacion, $fechaExpiracion)) {
            header("Location: gestionNovedades.php");
            exit;
        }

        $error = "No se pudo modificar la novedad. Intentá nuevamente.";
    }

    // Si hubo error, se vuelven a mostrar los datos ingresados
    $novedad["textoNovedad"] = $texto;
    $novedad["fechaPublicacionNovedad"] = $fechaPublicacion;
    $novedad["fechaExpiracionNovedad"] = $fechaExpiracion;
}

?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <title>Nuvia - Administrador</title>

    <link rel="icon" type="image/png" href="../../imagenes/logo.png">
    <link rel="stylesheet" href="../../css/bootstrap.min.css">
    <link rel="stylesheet" href="../../css/bootstrap-icons.css">
    <link rel="stylesheet" href="../../css/estilos-admin.css">
    <link rel="stylesheet" href="../../css/navbar.css">
</head>

<body>

    <?php include "../includes/navbarAdmin.php"; ?>

    <main class="contenido-admin">
        <section class="encabezado-contenido">

            <div>
                <h1>Modificar Novedad</h1>
                <p>Editá los datos de la novedad.</p>
            </div>

        </section>

        <section class="tabla-contenedor">

            <?php if ($novedad) { ?>
                <form method="POST">

                    <?php if (!empty($error)) { ?>
                        <div class="alert alert-danger" role="alert">
                            <?php echo htmlspecialchars($error); ?>
                        </div>
                    <?php } ?>

                    <input type="hidden" name="codNovedad" value="<?php echo $novedad["codNovedad"]; ?>">

                    <div class="mb-3">
                        <label for="textoNovedad" class="form-label">
                            Novedad
                        </label>

                        <textarea class="form-control" id="textoNovedad" name="textoNovedad" maxlength="200" rows="3" required>
                            <?php echo htmlspecialchars($novedad["textoNovedad"]); ?>
                        </textarea>
                    </div>

                    <div class="mb-3">
                        <label for="fechaPublicacionNovedad" class="form-label">
                            Fecha de publicación
                        </label>

                        <input type="date" class="form-control" id="fechaPublicacionNovedad" name="fechaPublicacionNovedad" value="<?php echo htmlspecialchars($novedad["fechaPublicacionNovedad"]); ?>" required>
                    </div>

                    <div class="mb-3">
                        <label for="fechaExpiracionNovedad" class="form-label">
                            Fecha de expiración
                        </label>

                        <input type="date" class="form-control" id="fechaExpiracionNovedad" name="fechaExpiracionNovedad" value="<?php echo htmlspecialchars($novedad["fechaExpiracionNovedad"]); ?>" required>
                    </div>

                    <button type="submit" class="btn btn-primary">
                        Guardar cambios
                    </button>

                    <a href="gestionNovedades.php" class="btn btn-secondary">
                        Cancelar
                    </a>
                </form>

            <?php } else { ?>

                <div class="alert alert-danger m-4">
                    No se encontró la novedad o ya fue dada de baja.
                </div>

                <div class="m-4">
                    <a href="gestionNovedades.php" class="btn btn-secondary">
                        <i class="bi bi-arrow-left"></i>
                        Volver
                    </a>
                </div>

            <?php } ?>
        </section>
    </main>

    <script src="../../js/bootstrap.bundle.min.js"></script>
    
</body>
</html>