<?php

include "../../php/conexionBD.php";
$error = "";

if ($_SERVER["REQUEST_METHOD"] == "POST") {

    $texto = $_POST["textoNovedad"];
    $fechaPublicacion = $_POST["fechaPublicacionNovedad"];
    $fechaExpiracion = $_POST["fechaExpiracionNovedad"];

    if (empty($texto) || empty($fechaPublicacion) || empty($fechaExpiracion)) {
    die("Todos los campos son obligatorios.");
    }
    if ($fechaExpiracion < $fechaPublicacion) {
    $error = "La fecha de expiración no puede ser anterior a la fecha de publicación.";
}

   if (empty($error)) {
    $sql = "INSERT INTO Novedades
        (textoNovedad, fechaPublicacionNovedad, fechaExpiracionNovedad)
        VALUES (?, ?, ?)";

    $stmt = $conexion->prepare($sql);

    $stmt->bind_param(
    "sss",
    $texto,
    $fechaPublicacion,
    $fechaExpiracion
);

if ($stmt->execute()) {
    header("Location: gestionNovedades.php");
    exit;
}

    
}
}

?>

<!DOCTYPE html>
<html lang="es">

<head>

    <meta charset="UTF-8">

    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <title>AeroFly Admin - Crear Novedad</title>

    <link rel="stylesheet" href="../../css/bootstrap.min.css">

    <link rel="stylesheet" href="../../css/bootstrap-icons.css">

    <link rel="stylesheet" href="../../css/estilos-admin.css">

    <link rel="icon" type="image/png" href="../../imagenes/logo.png">

</head>

<body>

    <?php include "../includes/navbarAdmin.php"; ?>

    <main class="contenido-admin">

        <section class="encabezado-contenido">

            <div>
                <h1>Crear Novedad</h1>
                <p>Completa los datos de la nueva novedad.</p>
            </div>

        </section>

        <section class="tabla-contenedor">

            <form method="POST">
                <?php if (!empty($error)) { ?>
                      <div class="alert alert-danger" role="alert">
                        <?php echo $error; ?>
                    </div>
                <?php } ?>

                <div class="mb-3">

                    <label for="textoNovedad" class="form-label">
                        Novedad
                    </label>

                    <textarea
                        class="form-control"
                        id="textoNovedad"
                        name="textoNovedad"
                        maxlength="200"
                        required>
                    </textarea>

                </div>

                <div class="mb-3">

                    <label for="fechaPublicacionNovedad" class="form-label">
                        Fecha de publicación
                    </label>

                    <input
                        type="date"
                        class="form-control"
                        id="fechaPublicacionNovedad"
                        name="fechaPublicacionNovedad"
                        required>

                </div>

                <div class="mb-3">

                    <label for="fechaExpiracionNovedad" class="form-label">
                        Fecha de expiración
                    </label>

                    <input
                        type="date"
                        class="form-control"
                        id="fechaExpiracionNovedad"
                        name="fechaExpiracionNovedad"
                        required>

                </div>

                <button type="submit" class="btn btn-primary">
                    Crear novedad
                </button>

            </form>

        </section>

    </main>

    <script src="../../js/bootstrap.bundle.min.js"></script>

</body>

</html>