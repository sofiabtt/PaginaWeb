<?php

include "../../php/conexionBD.php";


// OBTENER EL CÓDIGO DE LA NOVEDAD

$id = $_GET["id"];

$origen = $_GET["origen"] ?? "";


// BUSCAR LA NOVEDAD

$consulta = "SELECT *
             FROM Novedades
             WHERE codNovedad = ?";

$stmt = $conexion->prepare($consulta);

$stmt->bind_param("i", $id);

$stmt->execute();

$resultado = $stmt->get_result();

$novedad = $resultado->fetch_assoc();

?>

<!DOCTYPE html>

<html lang="es">

<head>

    <meta charset="UTF-8">

    <meta
        name="viewport"
        content="width=device-width, initial-scale=1.0"
    >

    <title>
        Nuvia - Administrador
    </title>


    <link
        rel="stylesheet"
        href="../../css/bootstrap.min.css"
    >


    <link
        rel="stylesheet"
        href="../../css/bootstrap-icons.css"
    >


    <link
        rel="stylesheet"
        href="../../css/estilos-admin.css"
    >


    <link
        rel="icon"
        type="image/png"
        href="../../imagenes/logo.png"
    >

</head>


<body>

    <?php include "../includes/navbarAdmin.php"; ?>


    <main class="contenido-admin">

        <section class="encabezado-contenido">

            <div>

                <h1>
                    Información de la novedad
                </h1>

            </div>

        </section>


        <section class="tabla-contenedor">

            <?php if ($novedad) { ?>

                <div class="p-4">

                    <h2 class="mb-4">
                        Novedad #<?php echo $novedad["codNovedad"]; ?>
                    </h2>


                    <p>

                        <strong>
                            Fecha de publicación:
                        </strong>

                        <?php
                        echo $novedad["fechaPublicacionNovedad"];
                        ?>

                    </p>


                    <p>

                        <strong>
                            Fecha de expiración:
                        </strong>

                        <?php
                        echo $novedad["fechaExpiracionNovedad"];
                        ?>

                    </p>


                    <hr>


                    <h5>
                        Novedad
                    </h5>


                    <p>

                        <?php
                        echo nl2br(
                            htmlspecialchars(
                                $novedad["textoNovedad"]
                            )
                        );
                        ?>

                    </p>


                    <div class="mt-4">

                        <a
                            href="<?php echo ($origen == "inactivas") ? "novedadesInactivas.php" : "gestionNovedades.php"; ?>"
                            class="btn btn-secondary"
                        >

                            <i class="bi bi-arrow-left"></i>

                            Volver

                        </a>

                    </div>

                </div>


            <?php } else { ?>

                <div class="alert alert-danger m-4">

                    No se encontró la novedad.

                </div>

                <div class="m-4">

                    <a
                        href="<?php echo ($origen == "inactivas") ? "novedadesInactivas.php" : "gestionNovedades.php"; ?>"
                        class="btn btn-secondary"
                    >

                        Volver

                    </a>

                </div>

            <?php } ?>

        </section>


    </main>


</body>

</html>

