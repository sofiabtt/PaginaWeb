<?php

include "../../php/conexionBD.php";


// OBTENER EL CÓDIGO DE LA AEROLÍNEA

$id = $_GET["id"];


// BUSCAR LA AEROLÍNEA

$consulta = "SELECT *
             FROM Aerolineas
             WHERE codAerolinea = ?";

$stmt = $conexion->prepare($consulta);

$stmt->bind_param("i", $id);

$stmt->execute();

$resultado = $stmt->get_result();

$aerolinea = $resultado->fetch_assoc();

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
        Ver aerolínea
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
                    Información de la aerolínea
                </h1>

            </div>

        </section>


        <section class="tabla-contenedor">

            <?php if ($aerolinea) { ?>

                <div class="p-4">

                    <h2 class="mb-4">
                        <?php
                        echo htmlspecialchars(
                            $aerolinea["nombreAerolinea"]
                        );
                        ?>
                    </h2>


                    <p>

                        <strong>
                            Código:
                        </strong>

                        <?php
                        echo $aerolinea["codAerolinea"];
                        ?>

                    </p>


                    <p>

                        <strong>
                            Código IATA:
                        </strong>

                        <?php
                        echo htmlspecialchars(
                            $aerolinea["codigoIATA"]
                        );
                        ?>

                    </p>


                    <p>

                        <strong>
                            País:
                        </strong>

                        <?php
                        echo htmlspecialchars(
                            $aerolinea["codPais"]
                        );
                        ?>

                    </p>


                    <hr>


                    <h5>
                        Descripción
                    </h5>


                    <p>

                        <?php
                        echo nl2br(
                            htmlspecialchars(
                                $aerolinea["descripcionAerolinea"]
                            )
                        );
                        ?>

                    </p>


                    <div class="mt-4">

                        <a
                            href="gestionAerolineas.php"
                            class="btn btn-secondary"
                        >

                            <i class="bi bi-arrow-left"></i>

                            Volver

                        </a>

                    </div>

                </div>


            <?php } else { ?>

                <div class="alert alert-danger m-4">

                    No se encontró la aerolínea.

                </div>

                <div class="m-4">

                    <a
                        href="gestionAerolineas.php"
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