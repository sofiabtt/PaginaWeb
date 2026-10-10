<?php
session_start();

// Control de acceso al panel
if (!isset($_SESSION['codUsuario']) || strtolower($_SESSION['tipoUsuario'] ?? '') !== 'administrador') {
    header("Location: ../inicioSesion.php");
    exit();
}

include "../php/conexionBD.php";
include "../php/consultasAerolineas.php";
include "../php/consultasPromociones.php";
include "../php/consultasNovedades.php";
include "../php/consultasCeos.php";
include "../php/consultasUsuarios.php"; // <-- 1. Agregamos el include
include "../php/consultasActividad.php";

$aerolineas = cantidadAerolineasActivas($conexion);
$promocionesPendientes = cantidadPromocionesPendientes($conexion);
$novedades = cantidadNovedades($conexion);
$ceos = cantidadCeos($conexion);
$usuarios = cantidadUsuarios($conexion); // <-- 2. Usamos la función existente
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

            <h2 class="h4 mb-3 text-dark">Resumen del sistema</h2>

            <div class="row g-3 mb-4">
                <!-- Tarjeta Aerolíneas -->
                <div class="col-md-6 col-lg-3">
                    <a href="aerolineas/gestionAerolineas.php" class="text-decoration-none text-reset">
                        <div class="card border-0 shadow-sm rounded-4 p-3 h-100 d-flex flex-row align-items-center">
                            <div class="me-3 text-primary fs-2">
                                <i class="bi bi-airplane"></i>
                            </div>
                            <div>
                                <div class="text-muted small">Aerolíneas activas</div>
                                <div class="fs-3 fw-bold text-dark"><?php echo $aerolineas; ?></div>
                            </div>
                        </div>
                    </a>
                </div>

                <!-- Tarjeta Usuarios Registrados -->
                <div class="col-md-6 col-lg-3">
                    <a href="reportes/usuarios.php" class="text-decoration-none text-reset">
                        <div class="card border-0 shadow-sm rounded-4 p-3 h-100 d-flex flex-row align-items-center">
                            <div class="me-3 text-primary fs-2">
                                <i class="bi bi-people"></i>
                            </div>
                            <div>
                                <div class="text-muted small">Usuarios registrados</div>
                                <div class="fs-3 fw-bold text-dark"><?php echo $usuarios; ?></div>
                            </div>
                        </div>
                    </a>
                </div>
            </div>


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