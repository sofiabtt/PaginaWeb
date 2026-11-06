<?php

require_once __DIR__ . '/../includes/verificarRol.php';
verificarRol('administrador');

include "../php/conexionBD.php";
include "../php/consultasAerolineas.php";
include "../php/consultasPromociones.php";
include "../php/consultasNovedades.php";
include "../php/consultasCeos.php";
include "../php/consultasUsuarios.php";
include "../php/consultasActividad.php";

// DATOS DEL RESUMEN

$aerolineas = cantidadAerolineasActivas($conexion);
$promocionesPendientes = cantidadPromocionesPendientes($conexion);
$novedades = cantidadNovedades($conexion);
$ceos = cantidadCeos($conexion);
$usuarios = cantidadUsuarios($conexion);

// ACTIVIDADES DE LOS ÚLTIMOS 5 DÍAS

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

    <!-- CSS -->
    <link rel="stylesheet" href="../css/estilosAdmin.css">
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


        <!-- RESUMEN DEL SISTEMA -->

        <section class="resumen-admin">

            <h2 class="h4 mb-3 text-dark">
                Resumen del sistema
            </h2>

            <div class="row g-3 mb-4">

                <!-- TARJETA AEROLÍNEAS -->

                <div class="col-12 col-md-6 col-lg">

                    <a href="aerolineas/gestionAerolineas.php"
                       class="text-decoration-none text-reset">

                        <div class="card border-0 shadow-sm rounded-4 p-3 h-100 d-flex flex-row align-items-center">

                            <div class="me-3 text-dark fs-2">
                                <i class="bi bi-airplane"></i>
                            </div>

                            <div>

                                <div class="text-muted small">
                                    Aerolíneas activas
                                </div>

                                <div class="fs-3 fw-bold text-dark">
                                    <?= $aerolineas ?>
                                </div>

                            </div>

                        </div>

                    </a>

                </div>


                <!-- TARJETA USUARIOS -->

                <div class="col-12 col-md-6 col-lg">

                    <a href="reportes/usuarios.php"
                       class="text-decoration-none text-reset">

                        <div class="card border-0 shadow-sm rounded-4 p-3 h-100 d-flex flex-row align-items-center">

                            <div class="me-3 text-dark fs-2">
                                <i class="bi bi-people"></i>
                            </div>

                            <div>

                                <div class="text-muted small">
                                    Usuarios registrados
                                </div>

                                <div class="fs-3 fw-bold text-dark">
                                    <?= $usuarios ?>
                                </div>

                            </div>

                        </div>

                    </a>

                </div>


                <!-- TARJETA PROMOCIONES PENDIENTES -->

                <div class="col-12 col-md-6 col-lg">

                    <a href="promociones/gestionPromociones.php"
                       class="text-decoration-none text-reset">

                        <div class="card border-0 shadow-sm rounded-4 p-3 h-100 d-flex flex-row align-items-center">

                            <div class="me-3 text-dark fs-2">
                                <i class="bi bi-tag"></i>
                            </div>

                            <div>

                                <div class="text-muted small">
                                    Promociones pendientes
                                </div>

                                <div class="fs-3 fw-bold text-dark">
                                    <?= $promocionesPendientes ?>
                                </div>

                            </div>

                        </div>

                    </a>

                </div>


                <!-- TARJETA NOVEDADES -->

                <div class="col-12 col-md-6 col-lg">

                    <a href="novedades/gestionNovedades.php"
                       class="text-decoration-none text-reset">

                        <div class="card border-0 shadow-sm rounded-4 p-3 h-100 d-flex flex-row align-items-center">

                            <div class="me-3 text-dark fs-2">
                                <i class="bi bi-newspaper"></i>
                            </div>

                            <div>

                                <div class="text-muted small">
                                    Novedades
                                </div>

                                <div class="fs-3 fw-bold text-dark">
                                    <?= $novedades ?>
                                </div>

                            </div>

                        </div>

                    </a>

                </div>


                <!-- TARJETA CEOS -->

                <div class="col-12 col-md-6 col-lg">

                    <a href="ceos/gestionCeos.php"
                       class="text-decoration-none text-reset">

                        <div class="card border-0 shadow-sm rounded-4 p-3 h-100 d-flex flex-row align-items-center">

                            <div class="me-3 text-dark fs-2">
                                <i class="bi bi-person-badge"></i>
                            </div>

                            <div>

                                <div class="text-muted small">
                                    CEOs registrados
                                </div>

                                <div class="fs-3 fw-bold text-dark">
                                    <?= $ceos ?>
                                </div>

                            </div>

                        </div>

                    </a>

                </div>

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
                        Aquí se muestran las actividades realizadas
                        durante los últimos 5 días.
                    </p>

                </div>

            </div>


            <!-- TABLA DE ACTIVIDADES -->

            <div class="tabla-contenedor">

                <table class="table align-middle tabla-actividad">

                    <thead>

                        <tr>
                            <th>Fecha</th>
                            <th>Usuario</th>
                            <th>Acción</th>
                        </tr>

                    </thead>

                    <tbody>

                        <?php if ($resultadoActividad->num_rows > 0) { ?>

                            <?php while ($actividad = $resultadoActividad->fetch_assoc()) { ?>

                                <tr>

                                    <td data-label="Fecha">

                                        <?php
                                        echo date(
                                            "d/m/Y H:i",
                                            strtotime($actividad["fechaActividad"])
                                        );
                                        ?>

                                    </td>

                                    <td data-label="Usuario">

                                        <?php
                                        echo htmlspecialchars(
                                            $actividad["usuarioActividad"],
                                            ENT_QUOTES,
                                            "UTF-8"
                                        );
                                        ?>

                                    </td>

                                    <td data-label="Acción">

                                        <?php
                                        echo htmlspecialchars(
                                            $actividad["accionActividad"],
                                            ENT_QUOTES,
                                            "UTF-8"
                                        );
                                        ?>

                                    </td>

                                </tr>

                            <?php } ?>

                        <?php } else { ?>

                            <tr>

                                <td colspan="3" class="text-center py-4">
                                    No se registraron actividades durante los últimos 5 días.
                                </td>

                            </tr>

                        <?php } ?>

                    </tbody>

                </table>

            </div>


            <!-- BOTÓN HISTORIAL COMPLETO -->

            <div class="mt-3 text-end">

                <a href="actividad/historialActividad.php"
                   class="btn btn-outline-dark rounded-3">

                    Ver historial completo

                    <i class="bi bi-arrow-right ms-2"></i>

                </a>

            </div>

        </section>

    </main>


    <!-- FOOTER -->

    <?php include __DIR__ . '/../includes/footer.php'; ?>

    <script src="<?= htmlspecialchars(urlApp('js/bootstrap.bundle.min.js'), ENT_QUOTES, 'UTF-8') ?>"></script>

</body>

</html>