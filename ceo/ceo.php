<?php

session_start();

ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

include "../php/conexionBD.php";


// VERIFICAR QUE SEA CEO

if (!isset($_SESSION["tipoUsuario"]) || $_SESSION["tipoUsuario"] != "ceo") {

    header("Location: ../inicioSesion.php");
    exit();

}


// DATOS DEL CEO

$nombreCEO = $_SESSION["nombreUsuario"];

$codAerolinea = $_SESSION["codAerolinea"];


// DATOS DE LA AEROLÍNEA

$consultaAerolinea = $conexion->prepare(
    "SELECT nombreAerolinea
     FROM Aerolineas
     WHERE codAerolinea = ?"
);

$consultaAerolinea->bind_param("i", $codAerolinea);

$consultaAerolinea->execute();

$resultadoAerolinea = $consultaAerolinea->get_result();

$aerolinea = $resultadoAerolinea->fetch_assoc();


// CANTIDAD DE VUELOS

$consultaVuelos = $conexion->prepare(
    "SELECT COUNT(*) AS cantidad
     FROM Vuelos
     WHERE codAerolinea = ?"
);

$consultaVuelos->bind_param("i", $codAerolinea);

$consultaVuelos->execute();

$resultadoVuelos = $consultaVuelos->get_result();

$vuelos = $resultadoVuelos->fetch_assoc();


// CANTIDAD DE PROMOCIONES

$consultaPromociones = $conexion->prepare(
    "SELECT COUNT(*) AS cantidad
     FROM Promociones
     WHERE codAerolinea = ?"
);

$consultaPromociones->bind_param("i", $codAerolinea);

$consultaPromociones->execute();

$resultadoPromociones = $consultaPromociones->get_result();

$promociones = $resultadoPromociones->fetch_assoc();


// CANTIDAD DE PROMOCIONES PENDIENTES

$consultaPendientes = $conexion->prepare(
    "SELECT COUNT(*) AS cantidad
     FROM Promociones
     WHERE codAerolinea = ?
     AND estadoPromocion = 'Pendiente'"
);

$consultaPendientes->bind_param("i", $codAerolinea);

$consultaPendientes->execute();

$resultadoPendientes = $consultaPendientes->get_result();

$promocionesPendientes = $resultadoPendientes->fetch_assoc();

// PRÓXIMOS VUELOS

$consultaProximosVuelos = $conexion->prepare(
    "SELECT codVuelo, origenVuelo, destinoVuelo,
            fechaSalidaVuelo, horaSalidaVuelo, asientosDisponibles
     FROM Vuelos
     WHERE codAerolinea = ?
     ORDER BY fechaSalidaVuelo ASC, horaSalidaVuelo ASC
     LIMIT 5"
);

$consultaProximosVuelos->bind_param("i", $codAerolinea);

$consultaProximosVuelos->execute();

$resultadoProximosVuelos = $consultaProximosVuelos->get_result();


// CERRAR CONEXIONES

$consultaAerolinea->close();
$consultaVuelos->close();
$consultaPromociones->close();
$consultaPendientes->close();
$consultaProximosVuelos->close();

$conexion->close();

?>


<!DOCTYPE html>
<html lang="es">

<head>

    <meta charset="UTF-8">

    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <title>CEO</title>

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
                            echo $vuelos["cantidad"];
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
                            echo $promociones["cantidad"];
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
                            echo $promocionesPendientes["cantidad"];
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
                                        strtotime($vuelo["fechaSalidaVuelo"])
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

