<?php

require_once "php/conexionBD.php";

$fechaActual = date("Y-m-d");

$consulta = "
    SELECT
        codNovedad,
        textoNovedad,
        fechaPublicacionNovedad,
        fechaExpiracionNovedad
    FROM Novedades
    WHERE fechaPublicacionNovedad <= '$fechaActual'
      AND fechaExpiracionNovedad >= '$fechaActual'
    ORDER BY fechaPublicacionNovedad DESC
";

$resultado = $conexion->query($consulta);

if (!$resultado) {
    die("Error al consultar las novedades: " . $conexion->error);
}

?>

<!DOCTYPE html>
<html lang="es">

<head>

    <meta charset="UTF-8">

    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <title>Novedades</title>

    <link rel="icon" href="imagenes/logo.png" type="image/png">

    <link rel="stylesheet" href="css/bootstrap.min.css">

    <link rel="stylesheet" href="css/footer.css">

</head>

<body>

    <main class="container py-5">

        <h1 class="mb-4">
            Novedades
        </h1>

        <?php if ($resultado->num_rows > 0) { ?>

            <div class="row g-4">

                <?php while ($novedad = $resultado->fetch_assoc()) { ?>

                    <div class="col-12 col-md-6 col-lg-4">

                        <article class="card h-100 shadow-sm">

                            <div class="card-body">

                                <p class="card-text">
                                    <?php echo htmlspecialchars($novedad["textoNovedad"]); ?>
                                </p>

                                <small class="text-muted">
                                    Publicada:
                                    <?php
                                    echo date(
                                        "d/m/Y",
                                        strtotime($novedad["fechaPublicacionNovedad"])
                                    );
                                    ?>
                                </small>

                            </div>

                        </article>

                    </div>

                <?php } ?>

            </div>

        <?php } else { ?>

            <div class="alert alert-info">

                No hay novedades vigentes en este momento.

            </div>

        <?php } ?>

    </main>

    <?php include "includes/footer.php"; ?>

    <script src="js/bootstrap.bundle.min.js"></script>

</body>

</html>