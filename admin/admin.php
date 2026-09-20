<?php

session_start();

include "../php/conexionBD.php";
include "../php/consultasAerolineas.php";
include "../php/consultasPromociones.php";
include "../php/consultasNovedades.php";
include "../php/consultasCeos.php";
include "../php/consultasActividad.php";


$aerolineas = cantidadAerolineasActivas($conexion);

$promocionesPendientes = cantidadPromocionesPendientes($conexion);

$novedades = cantidadNovedades($conexion);

$ceos = cantidadCeos($conexion);

$resultadoActividad = obtenerActividadesRecientes($conexion);

?>


<!DOCTYPE html>
<html lang="es">

<head>

    <meta charset="UTF-8">

    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <title>Nuvia - Administrador</title>

    <link rel="icon" href="../imagenes/logo.png" type="image/png">

    <!-- Bootstrap -->
    <link rel="stylesheet" href="../css/bootstrap.min.css">

    <!-- Bootstrap Icons -->
    <link rel="stylesheet" href="../css/bootstrap-icons.css">

    <!-- CSS del administrador -->
    <link rel="stylesheet" href="../css/estilos-admin.css">
    <link rel="stylesheet" href="../css/navbar.css">
    <link rel="stylesheet" href="../css/footer.css">

</head>

<body>

    <?php include "includes/navbarAdmin.php"; ?>


    <main class="contenido-admin">


        <!-- BIENVENIDA -->

        <section class="bienvenida-admin">

            <h1>
                Bienvenido, Administrador
            </h1>

            <p>
                Desde este espacio puedes gestionar la información
                general del sistema.
            </p>

        </section>



        <!-- RESUMEN -->

        <section class="resumen-admin">

            <h2>
                Resumen del sistema
            </h2>


            <div class="resumen-grid">


                <article class="resumen-item">

                    <i class="bi bi-airplane"></i>

                    <div>

                        <h3>
                            Aerolíneas activas
                        </h3>

                        <p class="numero-resumen">
                            <?php echo $aerolineas; ?>
                        </p>

                    </div>

                </article>


                <article class="resumen-item">

                    <i class="bi bi-tag"></i>

                    <div>

                        <h3>
                            Promociones pendientes
                        </h3>

                        <p class="numero-resumen">
                            <?php echo $promocionesPendientes; ?>
                        </p>

                    </div>

                </article>


                <article class="resumen-item">

                    <i class="bi bi-newspaper"></i>

                    <div>

                        <h3>
                            Novedades publicadas
                        </h3>

                        <p class="numero-resumen">
                            <?php echo $novedades; ?>
                        </p>

                    </div>

                </article>


                <article class="resumen-item">

                    <i class="bi bi-people"></i>

                    <div>

                        <h3>
                            CEOs registrados
                        </h3>

                        <p class="numero-resumen">
                            <?php echo $ceos; ?>
                        </p>

                    </div>

                </article>


            </div>

        </section>



        <!-- ACTIVIDAD RECIENTE -->

        <section class="actividad-admin">

            <div class="encabezado-seccion">

                <div>

                    <h2>
                        Actividad reciente
                    </h2>

                    <p>
                        Aquí se mostrarán las últimas acciones realizadas
                        en el sistema.
                    </p>

                </div>

            </div>


            <div class="tabla-contenedor">

                <table class="table align-middle">

                    <thead>

                        <tr>

                            <th>
                                Fecha
                            </th>

                            <th>
                                Usuario
                            </th>

                            <th>
                                Acción
                            </th>

                        </tr>

                    </thead>


                    <tbody>

                        <?php while ($actividad = $resultadoActividad->fetch_assoc()) { ?>

                            <tr>

                                <td>
                                    <?php
                                    echo date(
                                        "d/m/Y H:i",
                                        strtotime($actividad["fechaActividad"])
                                    );
                                    ?>
                                </td>

                                <td>
                                    <?php
                                    echo htmlspecialchars(
                                        $actividad["usuarioActividad"]
                                    );
                                    ?>
                                </td>

                                <td>
                                    <?php
                                    echo htmlspecialchars(
                                        $actividad["accionActividad"]
                                    );
                                    ?>
                                </td>

                            </tr>

                        <?php } ?>

                    </tbody>

                </table>

            </div>

        </section>


    </main>


    <?php include "../includes/footer.php"; ?>
    <script src="/PaginaWeb/js/bootstrap.bundle.min.js"></script>

</body>

</html>