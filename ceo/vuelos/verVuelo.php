<?php

    session_start();

    // VERIFICAR QUE SEA CEO

    if (!isset($_SESSION["tipoUsuario"]) || $_SESSION["tipoUsuario"] != "ceo") {

        header("Location: ../../inicioSesion.php");
        exit();

    }


    include "../../php/conexionBD.php";


    // OBTENER EL CÓDIGO DEL VUELO

    $id = $_GET["id"];
    $origen = $_GET["origen"] ?? "";


    // OBTENER EL CEO

    if (!isset($_SESSION["codUsuario"])) {

        echo "No se pudo identificar al CEO.";
        exit();

    }

    $codUsuario = $_SESSION["codUsuario"];


    // BUSCAR EL VUELO Y VERIFICAR QUE PERTENEZCA A SU AEROLÍNEA

    $consulta = "
        SELECT V.*
        FROM Vuelos V
        INNER JOIN Aerolineas A
            ON V.codAerolinea = A.codAerolinea
        WHERE V.codVuelo = ?
          AND A.codUsuario = ?
    ";

    $stmt = $conexion->prepare($consulta);

    $stmt->bind_param("ii", $id, $codUsuario);

    $stmt->execute();

    $resultado = $stmt->get_result();

    $vuelo = $resultado->fetch_assoc();

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
        Nuvia - CEO
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


    <!-- NAVBAR -->

    <?php include "../includes/navbarCeo.php"; ?>


    <main class="contenido-admin">


        <!-- ENCABEZADO -->

        <section class="encabezado-contenido">

            <div>

                <h1>
                    Información del vuelo
                </h1>

            </div>

        </section>



        <!-- INFORMACIÓN -->

        <section class="tabla-contenedor">

            <?php if ($vuelo) { ?>

                <div class="p-4">


                    <h2 class="mb-4">

                        Vuelo
                        <?php
                        echo $vuelo["codVuelo"];
                        ?>

                    </h2>


                    <!-- ESTADO -->

                    <p>

                        <strong>
                            Estado:
                        </strong>


                        <?php if ($vuelo["activoVuelo"] == 1) { ?>

                            <span class="badge bg-success">
                                Activo
                            </span>

                        <?php } else { ?>

                            <span class="badge bg-danger">
                                Dado de baja
                            </span>

                        <?php } ?>

                    </p>


                    <!-- FECHA DE BAJA -->

                    <?php if ($vuelo["activoVuelo"] == 0) { ?>

                        <p>

                            <strong>
                                Fecha de baja:
                            </strong>

                            <?php

                            echo date(
                                "d/m/Y H:i",
                                strtotime(
                                    $vuelo["fechaEliminacion"]
                                )
                            );

                            ?>

                        </p>

                    <?php } ?>



                    <!-- CÓDIGO -->

                    <p>

                        <strong>
                            Código:
                        </strong>

                        <?php
                        echo $vuelo["codVuelo"];
                        ?>

                    </p>



                    <!-- ORIGEN -->

                    <p>

                        <strong>
                            Origen:
                        </strong>

                        <?php
                        echo htmlspecialchars(
                            $vuelo["origenVuelo"]
                        );
                        ?>

                    </p>



                    <!-- DESTINO -->

                    <p>

                        <strong>
                            Destino:
                        </strong>

                        <?php
                        echo htmlspecialchars(
                            $vuelo["destinoVuelo"]
                        );
                        ?>

                    </p>



                    <!-- FECHA DE SALIDA -->

                    <p>

                        <strong>
                            Fecha de salida:
                        </strong>

                        <?php

                        echo date(
                            "d/m/Y",
                            strtotime(
                                $vuelo["fechaSalidaVuelo"]
                            )
                        );

                        ?>

                    </p>



                    <!-- HORA DE SALIDA -->

                    <p>

                        <strong>
                            Hora de salida:
                        </strong>

                        <?php
                        echo htmlspecialchars(
                            $vuelo["horaSalidaVuelo"]
                        );
                        ?>

                    </p>



                    <!-- PRECIO -->

                    <p>

                        <strong>
                            Precio:
                        </strong>

                        $

                        <?php

                        echo number_format(
                            $vuelo["precioVuelo"],
                            0,
                            ",",
                            "."
                        );

                        ?>

                    </p>



                    <!-- ASIENTOS -->

                    <p>

                        <strong>
                            Asientos disponibles:
                        </strong>

                        <?php
                        echo $vuelo["asientosDisponibles"];
                        ?>

                    </p>



                    <div class="mt-4">

                        <a
                            href="<?php echo ($origen == "inactivas") ? "vuelosInactivos.php" : "gestionVuelos.php"; ?>"
                            class="btn btn-secondary"
                        >

                            <i class="bi bi-arrow-left"></i>

                            Volver

                        </a>

                    </div>


                </div>


            <?php } else { ?>


                <div class="alert alert-danger m-4">

                    No se encontró el vuelo.

                </div>


                <div class="m-4">

                    <a
                        href="<?php echo ($origen == "inactivas") ? "vuelosInactivos.php" : "gestionVuelos.php"; ?>"
                        class="btn btn-secondary"
                    >

                        <i class="bi bi-arrow-left"></i>

                        Volver

                    </a>

                </div>


            <?php } ?>

        </section>


    </main>


</body>

</html>