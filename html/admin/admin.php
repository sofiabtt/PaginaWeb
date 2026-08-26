<?php
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

include "../../php/conexionBD.php";


// CANTIDAD DE AEROLÍNEAS

$consultaAerolineas = "SELECT COUNT(*) AS cantidad
                       FROM Aerolineas";

$resultadoAerolineas = $conexion->query($consultaAerolineas);

$aerolineas = $resultadoAerolineas->fetch_assoc();


// CANTIDAD DE PROMOCIONES PENDIENTES

$consultaPromociones = "SELECT COUNT(*) AS cantidad
                        FROM Promociones
                        WHERE estadoPromocion = 'Pendiente'";

$resultadoPromociones = $conexion->query($consultaPromociones);

$promocionesPendientes = $resultadoPromociones->fetch_assoc();


// CANTIDAD DE NOVEDADES

$consultaNovedades = "SELECT COUNT(*) AS cantidad
                      FROM Novedades";

$resultadoNovedades = $conexion->query($consultaNovedades);

$novedades = $resultadoNovedades->fetch_assoc();

// CANTIDAD DE CEOs

$consultaCEOs = "SELECT COUNT(*) AS cantidad
                 FROM Usuarios
                 WHERE tipoUsuario = 'ceo'";

$resultadoCEOs = $conexion->query($consultaCEOs);

$ceos = $resultadoCEOs->fetch_assoc();

// ACTIVIDAD RECIENTE

$consultaActividad = "SELECT *
                      FROM Actividad
                      ORDER BY fechaActividad DESC
                      LIMIT 10";

$resultadoActividad = $conexion->query($consultaActividad);

?>


<!DOCTYPE html>
<html lang="es">

<head>

    <meta charset="UTF-8">

    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <title>Administrador</title>

    <link rel="icon" href="../../imagenes/logo.png" type="image/png">

    <!-- Bootstrap -->
    <link rel="stylesheet" href="../../css/bootstrap.min.css">

    <!-- Bootstrap Icons -->
    <link rel="stylesheet" href="../../css/bootstrap-icons.css">

    <!-- CSS del administrador -->
    <link rel="stylesheet" href="../../css/estilos-admin.css">

</head>

<body>

    <?php include "includes/navbar-admin.php"; ?>

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



        <!--RESUMEN-->

        <section class="resumen-admin">

            <h2>
                Resumen del sistema
            </h2>


            <div class="resumen-grid">


                <article class="resumen-item">

                    <i class="bi bi-airplane"></i>

                    <div>

                        <h3>
                            Aerolíneas
                        </h3>

                        <p class="numero-resumen">
                            <?php echo $aerolineas["cantidad"]; ?>
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
                            <?php echo $promocionesPendientes["cantidad"]; ?>
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
                            <?php echo $novedades["cantidad"]; ?>
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
                            <?php echo $ceos["cantidad"]; ?>
                        </p>

                    </div>

                </article>


            </div>

        </section>



        <!--ACTIVIDAD RECIENTE-->

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


            <!--
                Esta tabla queda vacía.
                Más adelante PHP podrá cargar los registros
                provenientes de la base de datos.
            -->

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

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/js/bootstrap.bundle.min.js"></script>

</body>

</html>

