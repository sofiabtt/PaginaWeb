<?php

require_once __DIR__ . '/../../includes/verificarRol.php';
require_once __DIR__ . '/../../php/conexionBD.php';

verificarRol('ceo');

require_once __DIR__ . '/../../php/consultasCeos.php';
require_once __DIR__ . '/../../php/consultasAerolineas.php';

verificarCeo();

$codUsuario = obtenerCodCeo();

$aerolinea = obtenerAerolineaPorCeo($conexion, $codUsuario);

if (!$aerolinea) {
    echo "El CEO no tiene una aerolínea asignada.";
    exit();
}

?>

<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <title>Nuvia - Gestión de Reportes CEO</title>

    <link rel="icon" type="image/png"
        href="<?= htmlspecialchars(urlApp('imagenes/logo.png'), ENT_QUOTES, 'UTF-8') ?>">

    <link rel="stylesheet"
        href="<?= htmlspecialchars(urlApp('css/bootstrap.min.css'), ENT_QUOTES, 'UTF-8') ?>">

    <link rel="stylesheet"
        href="<?= htmlspecialchars(urlApp('css/bootstrap-icons.css'), ENT_QUOTES, 'UTF-8') ?>">

    <link rel="stylesheet"
        href="<?= htmlspecialchars(urlApp('css/estilos.css'), ENT_QUOTES, 'UTF-8') ?>">

</head>

<body class="nuvia-panel nuvia-navbar">

    <?php include __DIR__ . '/../includes/navbarCeo.php'; ?>

    <main class="container reporte-contenido">

        <section class="reporte-encabezado">

            <div>

                <h1>Gestión de Reportes</h1>

                <p class="reporte-descripcion">
                    Consultá los reportes y estadísticas de
                    <?= htmlspecialchars($aerolinea["nombreAerolinea"], ENT_QUOTES, 'UTF-8') ?>.
                </p>

            </div>

        </section>

        <!-- REPORTES DISPONIBLES -->

        <section class="reportes-grid" aria-label="Reportes disponibles">

            <!-- REPORTE DE VENTAS -->

            <article class="reporte-card">

                <div class="reporte-card-icono">
                    <i class="bi bi-graph-up-arrow" aria-hidden="true"></i>
                </div>

                <h2>Reporte de Ventas</h2>

                <p>
                    Consultá las reservas confirmadas, los pasajeros
                    y los ingresos generados por los vuelos de tu aerolínea.
                </p>

                <a href="ventas.php" class="btn btn-primary">
                    <i class="bi bi-file-earmark-bar-graph" aria-hidden="true"></i>
                    Ver reporte
                </a>

            </article>

            <!-- REPORTE DE OCUPACIÓN -->

            <article class="reporte-card">

                <div class="reporte-card-icono">
                    <i class="bi bi-airplane" aria-hidden="true"></i>
                </div>

                <h2>Reporte de Ocupación de Vuelos</h2>

                <p>
                    Consultá la cantidad de asientos ocupados,
                    los asientos disponibles y el porcentaje de
                    ocupación de los vuelos de tu aerolínea.
                </p>

                <a href="ocupacionVuelos.php" class="btn btn-primary">
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
