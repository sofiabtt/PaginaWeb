<?php

session_start();

include "../php/consultasCeos.php";
include "../php/consultasAerolineas.php";
include "../php/consultasVuelos.php";
include "../php/consultasPromociones.php";

verificarCeo();

$nombreCEO = $_SESSION["nombreUsuario"];

$codUsuario = obtenerCodCeo();

// DATOS DE LA AEROLÍNEA

$aerolinea = obtenerAerolineaPorCeo($conexion,$codUsuario);

if (!$aerolinea) {

    echo "El CEO no tiene una aerolínea asignada.";
    exit();

}

$codAerolinea = $aerolinea["codAerolinea"];


// CANTIDAD DE VUELOS

$cantidadVuelos = cantidadVuelosPorAerolinea($conexion,$codAerolinea);

// CANTIDAD DE PROMOCIONES

$cantidadPromociones = cantidadPromocionesPorAerolinea($conexion,$codAerolinea);

// CANTIDAD DE PROMOCIONES PENDIENTES

$promocionesPendientes = cantidadPromocionesPendientesPorAerolinea($conexion,$codAerolinea);

// PRÓXIMOS VUELOS

$resultadoProximosVuelos = obtenerProximosVuelos($conexion,$codAerolinea);

?>


<!DOCTYPE html>

<html lang="es">

<head>

    <meta charset="UTF-8">

    <meta
        name="viewport"
        content="width=device-width, initial-scale=1.0"
    >

    <title>Nuvia - CEO</title>

    <link
        rel="icon"
        href="../imagenes/logo.png"
        type="image/png"
    >

    <!-- Bootstrap -->

    <link
        rel="stylesheet"
        href="../css/bootstrap.min.css"
    >

    <!-- Bootstrap Icons -->

    <link
        rel="stylesheet"
        href="../css/bootstrap-icons.css"
    >

    <!-- CSS del administrador -->

    <link
        rel="stylesheet"
        href="../css/estilos-admin.css"
    >

    <link rel="stylesheet" href="/PaginaWeb/css/navbar.css">

</head>


<body>


    <?php include "includes/navbarCeo.php"; ?>


    <main class="contenido-admin">


        <!-- BIENVENIDA -->

        <section class="bienvenida-admin">

            <h1>
                Bienvenido, <?php echo htmlspecialchars($nombreCEO); ?>
            </h1>

            <p>

                Desde este espacio puedes gestionar
                la información de

                <strong>
                    <?php echo htmlspecialchars($aerolinea["nombreAerolinea"]); ?>
                </strong>.

            </p>

        </section>



        <!-- RESUMEN -->

        <section class="resumen-admin">

            <h2>
                Resumen de mi aerolínea
            </h2>


            <div class="resumen-grid">


                <!-- VUELOS -->

                <article class="resumen-item">

                    <i class="bi bi-airplane"></i>

                    <div>

                        <h3>
                            Vuelos
                        </h3>

                        <p class="numero-resumen">

                            <?php
                            echo $cantidadVuelos;
                            ?>

                        </p>

                    </div>

                </article>



                <!-- PROMOCIONES -->

                <article class="resumen-item">

                    <i class="bi bi-tag"></i>

                    <div>

                        <h3>
                            Promociones
                        </h3>

                        <p class="numero-resumen">

                            <?php
                            echo $cantidadPromociones;
                            ?>

                        </p>

                    </div>

                </article>



                <!-- PROMOCIONES PENDIENTES -->

                <article class="resumen-item">

                    <i class="bi bi-hourglass-split"></i>

                    <div>

                        <h3>
                            Promociones pendientes
                        </h3>

                        <p class="numero-resumen">

                            <?php
                            echo $promocionesPendientes;
                            ?>

                        </p>

                    </div>

                </article>



                <!-- AEROLÍNEA -->

                <article class="resumen-item">

                    <i class="bi bi-building"></i>

                    <div>

                        <h3>
                            Aerolínea
                        </h3>

                        <p class="numero-resumen">

                            <?php
                            echo htmlspecialchars(
                                $aerolinea["nombreAerolinea"]
                            );
                            ?>

                        </p>

                    </div>

                </article>


            </div>

        </section>



        <!-- PRÓXIMOS VUELOS -->

        <section class="actividad-admin">

            <div class="encabezado-seccion">

                <div>

                    <h2>
                        Próximos vuelos
                    </h2>

                    <p>
                        Estos son los próximos vuelos programados
                        de tu aerolínea.
                    </p>

                </div>

            </div>


            <div class="tabla-contenedor">

                <table class="table align-middle">

                    <thead>

                        <tr>

                            <th>
                                Código
                            </th>

                            <th>
                                Origen
                            </th>

                            <th>
                                Destino
                            </th>

                            <th>
                                Fecha
                            </th>

                            <th>
                                Hora
                            </th>

                            <th>
                                Asientos disponibles
                            </th>

                        </tr>

                    </thead>


                    <tbody>

                        <?php if ($resultadoProximosVuelos->num_rows > 0) { ?>

                            <?php while ($vuelo = $resultadoProximosVuelos->fetch_assoc()) { ?>

                                <tr>

                                    <td>
                                        <?php
                                        echo $vuelo["codVuelo"];
                                        ?>
                                    </td>

                                    <td>
                                        <?php
                                        echo htmlspecialchars(
                                            $vuelo["origenVuelo"]
                                        );
                                        ?>
                                    </td>

                                    <td>
                                        <?php
                                        echo htmlspecialchars(
                                            $vuelo["destinoVuelo"]
                                        );
                                        ?>
                                    </td>

                                    <td>
                                        <?php
                                        echo date(
                                            "d/m/Y",
                                            strtotime(
                                                $vuelo["fechaSalidaVuelo"]
                                            )
                                        );
                                        ?>
                                    </td>

                                    <td>
                                        <?php
                                        echo htmlspecialchars(
                                            $vuelo["horaSalidaVuelo"]
                                        );
                                        ?>
                                    </td>

                                    <td>
                                        <?php
                                        echo $vuelo["asientosDisponibles"];
                                        ?>
                                    </td>

                                </tr>

                            <?php } ?>

                        <?php } else { ?>

                            <tr>

                                <td
                                    colspan="6"
                                    class="text-center"
                                >
                                    No hay vuelos próximos.
                                </td>

                            </tr>

                        <?php } ?>

                    </tbody>

                </table>

            </div>

        </section>


    </main>


    <script
        src="../js/bootstrap.bundle.min.js">
    </script>


</body>

</html>
