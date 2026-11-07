<?php
require_once __DIR__ . '/../../includes/verificarRol.php';
verificarRol('administrador');
?>

<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <title>Nuvia - Gestión de Reportes</title>
    <link rel="icon" type="image/png" href="../../imagenes/logo.png">

    <link rel="stylesheet" href="../../css/bootstrap.min.css">
    <link rel="stylesheet" href="../../css/bootstrap-icons.css">

    <link rel="stylesheet" href="../../css/navbar.css">
    <link rel="stylesheet" href="../../css/footer.css">
    <link rel="stylesheet" href="../../css/estilosAdmin.css">
    <link rel="stylesheet" href="../../css/reportesResponsive.css">
</head>

<body>
    <?php include __DIR__ . '/../includes/navbarAdmin.php'; ?>

    <main class="container reporte-contenido">
        <section class="reporte-encabezado">
            <div>
                <h1>Gestión de Reportes</h1>

                <p class="reporte-descripcion">
                    Consultá los reportes y estadísticas del sistema.
                </p>
            </div>
        </section>

        <!-- REPORTES DISPONIBLES -->
        <section class="reportes-grid" aria-label="Reportes disponibles">

            <!-- VENTAS -->
            <article class="reporte-card">
                <div class="reporte-card-icono">
                    <i class="bi bi-graph-up-arrow" aria-hidden="true"></i>
                </div>

                <h2>Reporte de Ventas</h2>

                <p>
                    Consultá las reservas confirmadas, los importes
                    de las ventas y los ingresos generados.
                </p>

                <a href="ventas.php" class="btn btn-primary">
                    <i class="bi bi-file-earmark-bar-graph" aria-hidden="true"></i>
                    Ver reporte
                </a>
            </article>

            <!-- VUELOS -->
            <article class="reporte-card">
                <div class="reporte-card-icono">
                    <i class="bi bi-airplane" aria-hidden="true"></i>
                </div>

                <h2>Reporte de Vuelos</h2>

                <p>
                    Consultá los vuelos registrados, sus aerolíneas,
                    destinos, fechas y disponibilidad.
                </p>

                <a href="vuelos.php" class="btn btn-primary">
                    <i class="bi bi-file-earmark-bar-graph" aria-hidden="true"></i>
                    Ver reporte
                </a>
            </article>

            <!-- USUARIOS -->
            <article class="reporte-card">
                <div class="reporte-card-icono">
                    <i class="bi bi-people" aria-hidden="true"></i>
                </div>

                <h2>Reporte de Usuarios</h2>

                <p>
                    Consultá los usuarios registrados en Nuvia,
                    sus roles y el estado de sus cuentas.
                </p>

                <a href="usuarios.php" class="btn btn-primary">
                    <i class="bi bi-file-earmark-bar-graph" aria-hidden="true"></i>
                    Ver reporte
                </a>
            </article>
        </section>
    </main>

    <?php include __DIR__ . '/../../includes/footer.php'; ?>

    <script src="<?= htmlspecialchars(urlApp('js/bootstrap.bundle.min.js'), ENT_QUOTES, 'UTF-8') ?>"></script>

</body>

</html>