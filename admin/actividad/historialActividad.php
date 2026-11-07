<?php

require_once __DIR__ . '/../../includes/verificarRol.php';
verificarRol('administrador');

require_once __DIR__ . '/../../php/conexionBD.php';
require_once __DIR__ . '/../../php/consultasActividad.php';

// FILTROS

$busqueda = trim($_GET['buscar'] ?? '');
$fechaDesde = $_GET['desde'] ?? '';
$fechaHasta = $_GET['hasta'] ?? '';

if (!preg_match('/^\d{4}-\d{2}-\d{2}$/', $fechaDesde)) {
    $fechaDesde = '';
}

if (!preg_match('/^\d{4}-\d{2}-\d{2}$/', $fechaHasta)) {
    $fechaHasta = '';
}

// PAGINACIÓN

require_once __DIR__ . '/../../php/paginacion.php';

$totalActividades = cantidadActividadesHistorial(
    $conexion,
    $busqueda,
    $fechaDesde,
    $fechaHasta
);

$paginacion = configurarPaginacion($totalActividades);

$pagina = $paginacion["pagina"];
$totalPaginas = $paginacion["totalPaginas"];

$porPagina = $paginacion["porPagina"];
$inicio = $paginacion["offset"];

// OBTENER ACTIVIDADES

$resultadoActividad = obtenerHistorialActividades(
    $conexion,
    $porPagina,
    $inicio,
    $busqueda,
    $fechaDesde,
    $fechaHasta
);

?>

<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">

    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <title>Nuvia - Historial de Actividad</title>

    <link rel="icon" href="../../imagenes/logo.png" type="image/png">

    <link rel="stylesheet" href="../../css/bootstrap.min.css">
    <link rel="stylesheet" href="../../css/bootstrap-icons.css">

    <link rel="stylesheet" href="../../css/estilosAdmin.css">
    <link rel="stylesheet" href="../../css/navbar.css">
    <link rel="stylesheet" href="../../css/footer.css">
    <link rel="stylesheet" href="../../css/reportesResponsive.css">
    <link rel="stylesheet" href="../../css/paginacion.css">
    
</head>

<body>

    <?php include __DIR__ . '/../includes/navbarAdmin.php'; ?>

    <main class="contenido-admin">

        <section class="bienvenida-admin">

            <a href="../admin.php" class="reporte-volver">
                <i class="bi bi-arrow-left"></i>
                Volver al Panel
            </a>

            <h1>Historial de actividad</h1>

            <p>
                Consultá todas las acciones registradas
                en el sistema.
            </p>

        </section>

        <section class="actividad-admin">

            <h2 class="h4 mb-3">Buscar actividades</h2>

            <!-- FILTROS -->

            <form method="GET" class="row g-3 mb-4">

                <div class="col-12 col-md-4">

                    <label for="buscar" class="form-label">
                        Usuario
                    </label>

                    <input
                        type="search"
                        id="buscar"
                        name="buscar"
                        class="form-control"
                        placeholder="Buscar usuario"
                        value="<?= htmlspecialchars($busqueda, ENT_QUOTES, 'UTF-8') ?>"
                    >

                </div>

                <div class="col-12 col-md-3">

                    <label for="desde" class="form-label">
                        Desde
                    </label>

                    <input
                        type="date"
                        id="desde"
                        name="desde"
                        class="form-control"
                        value="<?= htmlspecialchars($fechaDesde, ENT_QUOTES, 'UTF-8') ?>"
                    >

                </div>

                <div class="col-12 col-md-3">

                    <label for="hasta" class="form-label">
                        Hasta
                    </label>

                    <input
                        type="date"
                        id="hasta"
                        name="hasta"
                        class="form-control"
                        value="<?= htmlspecialchars($fechaHasta, ENT_QUOTES, 'UTF-8') ?>"
                    >

                </div>

                <div class="col-12 col-md-2 d-flex align-items-end gap-2">

                    <button type="submit" class="btn btn-dark">
                        Buscar
                    </button>

                    <a href="historialActividad.php"
                       class="btn btn-outline-secondary">
                        Limpiar
                    </a>

                </div>

            </form>

            <p class="text-muted">
                Total de actividades encontradas:
                <strong><?= $totalActividades ?></strong>
            </p>

            <!-- TABLA -->

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

                        <?php if ($resultadoActividad->num_rows > 0): ?>

                            <?php while ($actividad = $resultadoActividad->fetch_assoc()): ?>

                                <tr>

                                    <td data-label="Fecha">
                                        <?= date(
                                            'd/m/Y H:i',
                                            strtotime($actividad['fechaActividad'])
                                        ) ?>
                                    </td>

                                    <td data-label="Usuario">
                                        <?= htmlspecialchars(
                                            $actividad['usuarioActividad'],
                                            ENT_QUOTES,
                                            'UTF-8'
                                        ) ?>
                                    </td>

                                    <td data-label="Acción">
                                        <?= htmlspecialchars(
                                            $actividad['accionActividad'],
                                            ENT_QUOTES,
                                            'UTF-8'
                                        ) ?>
                                    </td>

                                </tr>

                            <?php endwhile; ?>

                        <?php else: ?>

                            <tr>
                                <td colspan="3" class="text-center py-4">
                                    No se encontraron actividades.
                                </td>
                            </tr>

                        <?php endif; ?>

                    </tbody>

                </table>

            </div>

            <?php include __DIR__ . '/../../php/paginacionVista.php'; ?>

        </section>

    </main>

    <?php include __DIR__ . '/../../includes/footer.php'; ?>

    <script src="../../js/bootstrap.bundle.min.js"></script>

</body>

</html>