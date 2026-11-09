<?php

require_once __DIR__ . '/../../includes/verificarRol.php';
verificarRol('administrador');

require_once __DIR__ . '/../../php/conexionBD.php';

// Se espera que conexionBD.php defina $conexion (mysqli).

function escapar($valor) {
    return htmlspecialchars((string) $valor, ENT_QUOTES, 'UTF-8');
}

function ejecutarConsultaReporte($conexion, $sql, $tipos, $parametros) {
    $stmt = $conexion->prepare($sql);

    if (!$stmt) {
        throw new RuntimeException('Error al preparar la consulta.');
    }

    if ($tipos !== '') {
        $stmt->bind_param($tipos, ...$parametros);
    }

    $stmt->execute();
    return $stmt;
}

function enlacePaginaVentas($pagina) {
    $parametros = $_GET;
    $parametros['pagina'] = $pagina;

    return '?' . http_build_query($parametros);
}

// FILTROS
$busqueda = trim($_GET['busqueda'] ?? '');
$aerolinea = filter_input(INPUT_GET, 'aerolinea', FILTER_VALIDATE_INT) ?: 0;
$desde = $_GET['desde'] ?? '';
$hasta = $_GET['hasta'] ?? '';

$pagina = max(1, (int) ($_GET['pagina'] ?? 1));
$porPagina = 10;

// LISTADO DE AEROLÍNEAS
$listaAerolineas = $conexion->query(
    "SELECT codAerolinea, nombreAerolinea
     FROM aerolineas
     ORDER BY nombreAerolinea"
);

// CONDICIONES
$condiciones = ["LOWER(TRIM(r.estadoReserva)) = 'confirmada'"];
$tipos = '';
$parametros = [];

if ($busqueda !== '') {
    $condiciones[] = "(u.nombreUsuario LIKE ? OR CAST(r.codReserva AS CHAR) LIKE ?)";
    $tipos .= 'ss';

    $texto = '%' . $busqueda . '%';
    $parametros[] = $texto;
    $parametros[] = $texto;
}

if ($aerolinea > 0) {
    $condiciones[] = "a.codAerolinea = ?";
    $tipos .= 'i';
    $parametros[] = $aerolinea;
}

if (preg_match('/^\d{4}-\d{2}-\d{2}$/', $desde)) {
    $condiciones[] = "r.fechaReserva >= ?";
    $tipos .= 's';
    $parametros[] = $desde . ' 00:00:00';
} else {
    $desde = '';
}

if (preg_match('/^\d{4}-\d{2}-\d{2}$/', $hasta)) {
    $condiciones[] = "r.fechaReserva < DATE_ADD(?, INTERVAL 1 DAY)";
    $tipos .= 's';
    $parametros[] = $hasta;
} else {
    $hasta = '';
}

$where = implode(' AND ', $condiciones);

$from = "
    FROM reservas r
    INNER JOIN usuarios u ON r.codUsuario = u.codUsuario
    INNER JOIN vuelos v ON r.codVuelo = v.codVuelo
    INNER JOIN aerolineas a ON v.codAerolinea = a.codAerolinea
";

// TOTALES
$sqlTotales = "
    SELECT
        COUNT(*) AS cantidad,
        COALESCE(SUM(r.precioFinalReserva), 0) AS total
    $from
    WHERE $where
";

$stmtTotales = ejecutarConsultaReporte(
    $conexion,
    $sqlTotales,
    $tipos,
    $parametros
);

$totales = $stmtTotales->get_result()->fetch_assoc();
$stmtTotales->close();

$cantidad = (int) $totales['cantidad'];
$totalVentas = (float) $totales['total'];

$totalPaginas = max(1, (int) ceil($cantidad / $porPagina));
$pagina = min($pagina, $totalPaginas);
$inicio = ($pagina - 1) * $porPagina;

// CONSULTA PAGINADA
$sql = "
    SELECT
        r.codReserva,
        r.fechaReserva,
        r.cantidadPasajerosReserva,
        r.precioFinalReserva,
        u.nombreUsuario,
        a.nombreAerolinea,
        v.codVuelo,
        v.origenVuelo,
        v.destinoVuelo
    $from
    WHERE $where
    ORDER BY r.fechaReserva DESC, r.codReserva DESC
    LIMIT ? OFFSET ?
";

$tiposListado = $tipos . 'ii';
$parametrosListado = array_merge($parametros, [$porPagina, $inicio]);

$stmt = ejecutarConsultaReporte(
    $conexion,
    $sql,
    $tiposListado,
    $parametrosListado
);

$ventas = $stmt->get_result()->fetch_all(MYSQLI_ASSOC);
$stmt->close();

// CONSULTA COMPLETA PARA IMPRESIÓN

$sqlImpresion = "
    SELECT
        r.codReserva,
        r.fechaReserva,
        r.cantidadPasajerosReserva,
        r.precioFinalReserva,
        u.nombreUsuario,
        a.nombreAerolinea,
        v.codVuelo,
        v.origenVuelo,
        v.destinoVuelo
    $from
    WHERE $where
    ORDER BY r.fechaReserva DESC, r.codReserva DESC
";

$stmtImpresion = ejecutarConsultaReporte(
    $conexion,
    $sqlImpresion,
    $tipos,
    $parametros
);

$ventasImpresion = $stmtImpresion->get_result()->fetch_all(MYSQLI_ASSOC);
$stmtImpresion->close();
?>

<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <title>Nuvia - Reporte de Ventas</title>
    <link rel="icon" type="image/png" href="../../imagenes/logo.png">

    <link rel="stylesheet" href="../../css/bootstrap.min.css">
    <link rel="stylesheet" href="../../css/bootstrap-icons.css">

    <link rel="stylesheet" href="<?= htmlspecialchars(urlApp('css/estilos.css'), ENT_QUOTES, 'UTF-8') ?>?v=20261108">
</head>

<body class="nuvia-panel nuvia-navbar">

    <?php include __DIR__ . '/../includes/navbarAdmin.php'; ?>

    <main class="container reporte-contenido">
        <section class="reporte-encabezado">
            <div>
                <a href="gestionReportes.php"
                class="reporte-volver">
                    <i class="bi bi-arrow-left"></i>
                    Volver a reportes
                </a>

                <h1>Reporte de Ventas</h1>

                <p class="reporte-descripcion">
                    Consultá las reservas confirmadas y los ingresos generados.
                </p>
            </div>

            <button type="button"
                    class="btn btn-primary reporte-btn-imprimir"
                    onclick="window.print()">
                <i class="bi bi-printer"></i>
                Imprimir
            </button>
        </section>

        <!-- FILTROS -->
        <section class="reporte-filtros">
            <form method="GET" class="row g-3 align-items-end">
                <div class="col-12 col-md-6 col-lg-3">
                    <label for="busqueda" class="form-label">
                        Buscar
                    </label>

                    <input type="search"
                        id="busqueda"
                        name="busqueda"
                        class="form-control"
                        placeholder="Usuario o reserva"
                        value="<?= escapar($busqueda) ?>">
                </div>

                <div class="col-12 col-md-6 col-lg-3">
                    <label for="aerolinea" class="form-label">
                        Aerolínea
                    </label>

                    <select id="aerolinea"
                            name="aerolinea"
                            class="form-select">

                        <option value="0">Todas</option>

                        <?php while ($a = $listaAerolineas->fetch_assoc()): ?>

                            <option value="<?= (int) $a['codAerolinea'] ?>"
                                <?= $aerolinea === (int) $a['codAerolinea'] ? 'selected' : '' ?>>

                                <?= escapar($a['nombreAerolinea']) ?>

                            </option>

                        <?php endwhile; ?>
                    </select>
                </div>

                <div class="col-12 col-md-6 col-lg-2">
                    <label for="desde" class="form-label">
                        Desde
                    </label>

                    <input type="date"
                        id="desde"
                        name="desde"
                        class="form-control"
                        value="<?= escapar($desde) ?>">
                </div>

                <div class="col-12 col-md-6 col-lg-2">
                    <label for="hasta" class="form-label">
                        Hasta
                    </label>

                    <input type="date"
                        id="hasta"
                        name="hasta"
                        class="form-control"
                        value="<?= escapar($hasta) ?>">
                </div>

                <div class="col-12 col-lg-2 d-flex gap-2">
                    <button type="submit" class="btn btn-primary">
                        Filtrar
                    </button>

                    <a href="ventas.php" class="btn btn-outline-secondary">
                        Limpiar
                    </a>
                </div>
            </form>
        </section>

        <!-- RESUMEN -->
        <section class="row g-3 my-3" aria-label="Resumen de ventas">

            <div class="col-12 col-md-6">
                <div class="card h-100">
                    <div class="card-body">
                        <h2 class="h6">Reservas confirmadas</h2>
                        <p class="fs-3 fw-bold mb-0">
                            <?= $cantidad ?>
                        </p>
                    </div>
                </div>
            </div>

            <div class="col-12 col-md-6">
                <div class="card h-100">
                    <div class="card-body">
                        <h2 class="h6">Importe total vendido</h2>
                        <p class="fs-3 fw-bold mb-0">
                            $<?= number_format($totalVentas, 2, ',', '.') ?>
                        </p>
                    </div>
                </div>
            </div>

        </section>

        <!-- TABLA -->
        <section class="tabla-contenedor reporte-tabla reporte-tabla-pantalla">
            <div class="table-responsive">
                <table class="table table-striped align-middle">
                    <thead>
                        <tr>
                            <th scope="col">Reserva</th>
                            <th scope="col">Fecha</th>
                            <th scope="col">Usuario</th>
                            <th scope="col">Aerolínea</th>
                            <th scope="col">Vuelo</th>
                            <th scope="col">Pasajeros</th>
                            <th scope="col">Importe</th>
                        </tr>
                    </thead>

                    <tbody>

                    <?php if (empty($ventas)): ?>

                        <tr>
                            <td colspan="7" class="text-center py-4">
                                No se encontraron ventas confirmadas.
                            </td>
                        </tr>

                    <?php else: ?>

                        <?php foreach ($ventas as $venta): ?>

                            <tr>
                                <td data-label="Reserva">
                                    #<?= (int) $venta['codReserva'] ?>
                                </td>

                                <td data-label="Fecha">
                                    <?= date('d/m/Y H:i', strtotime($venta['fechaReserva'])) ?>
                                </td>

                                <td data-label="Usuario">
                                    <?= escapar($venta['nombreUsuario']) ?>
                                </td>

                                <td data-label="Aerolínea">
                                    <?= escapar($venta['nombreAerolinea']) ?>
                                </td>

                                <td data-label="Vuelo">
                                    #<?= (int) $venta['codVuelo'] ?>
                                    <br>
                                    <?= escapar($venta['origenVuelo']) ?>
                                    →
                                    <?= escapar($venta['destinoVuelo']) ?>
                                </td>

                                <td data-label="Pasajeros">
                                    <?= (int) $venta['cantidadPasajerosReserva'] ?>
                                </td>

                                <td data-label="Importe">
                                    $<?= number_format(
                                        (float) $venta['precioFinalReserva'],
                                        2,
                                        ',',
                                        '.'
                                    ) ?>
                                </td>
                            </tr>

                        <?php endforeach; ?>
                    <?php endif; ?>

                    </tbody>
                </table>
            </div>
        </section>

        <!-- PAGINACIÓN -->
        <?php if ($totalPaginas > 1): ?>
            <nav aria-label="Paginación de ventas" class="mt-4">
                <ul class="pagination justify-content-center flex-wrap">
                    <?php for ($i = 1; $i <= $totalPaginas; $i++): ?>
                        <li class="page-item <?= $i === $pagina ? 'active' : '' ?>">
                            <a class="page-link"
                            href="<?= escapar(enlacePaginaVentas($i)) ?>"
                            <?= $i === $pagina ? 'aria-current="page"' : '' ?>>

                                <?= $i ?>

                            </a>
                        </li>
                    <?php endfor; ?>
                </ul>
            </nav>

        <?php endif; ?>

        <!-- REPORTE COMPLETO PARA IMPRESIÓN -->
        <section class="reporte-tabla reporte-tabla-impresion">
            <div class="reporte-membrete">
                <h2>Nuvia - Sistema de Reservas de Vuelos</h2>

                <p>
                    <strong>Reporte:</strong> Ventas Confirmadas
                </p>

                <p>
                    <strong>Fecha de emisión:</strong>
                    <?= date('d/m/Y H:i') ?> hs
                </p>

                <p>
                    <strong>Emitido por:</strong> Administrador
                </p>

                <p>
                    <strong>Reservas confirmadas:</strong>
                    <?= $cantidad ?>
                </p>

                <p>
                    <strong>Importe total vendido:</strong>
                    $<?= number_format($totalVentas, 2, ',', '.') ?>
                </p>

            </div>

            <table class="table table-bordered align-middle">

                <thead>
                    <tr>
                        <th>Reserva</th>
                        <th>Fecha</th>
                        <th>Usuario</th>
                        <th>Aerolínea</th>
                        <th>Vuelo</th>
                        <th>Pasajeros</th>
                        <th>Importe</th>
                    </tr>
                </thead>

                <tbody>
                    <?php if (empty($ventasImpresion)): ?>

                        <tr>
                            <td colspan="7">
                                No se encontraron ventas confirmadas.
                            </td>
                        </tr>

                    <?php else: ?>

                        <?php foreach ($ventasImpresion as $venta): ?>

                            <tr>

                                <td>
                                    #<?= (int) $venta['codReserva'] ?>
                                </td>

                                <td>
                                    <?= date('d/m/Y H:i', strtotime($venta['fechaReserva'])) ?>
                                </td>

                                <td>
                                    <?= escapar($venta['nombreUsuario']) ?>
                                </td>

                                <td>
                                    <?= escapar($venta['nombreAerolinea']) ?>
                                </td>

                                <td>
                                    #<?= (int) $venta['codVuelo'] ?>
                                    <br>
                                    <?= escapar($venta['origenVuelo']) ?>
                                    →
                                    <?= escapar($venta['destinoVuelo']) ?>
                                </td>

                                <td>
                                    <?= (int) $venta['cantidadPasajerosReserva'] ?>
                                </td>

                                <td>
                                    $<?= number_format(
                                        (float) $venta['precioFinalReserva'],
                                        2,
                                        ',',
                                        '.'
                                    ) ?>
                                </td>
                            </tr>

                        <?php endforeach; ?>
                    <?php endif; ?>
                    
                </tbody>
            </table>
        </section>

    </main>

    <?php include __DIR__ . '/../../includes/footer.php'; ?>

    <script src="<?= escapar(urlApp('js/bootstrap.bundle.min.js')) ?>"></script>

</body>
</html>