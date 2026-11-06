<?php
require_once __DIR__ . '/../../includes/verificarRol.php';
verificarRol('administrador');

require_once __DIR__ . '/../../php/conexionBD.php';

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

function enlacePaginaVuelos($pagina) {
    $parametros = $_GET;
    $parametros['pagina'] = $pagina;

    return '?' . http_build_query($parametros);
}

// FILTROS
$busqueda = trim($_GET['busqueda'] ?? '');
$aerolinea = filter_input(INPUT_GET, 'aerolinea', FILTER_VALIDATE_INT) ?: 0;
$estado = $_GET['estado'] ?? 'todos';

if (!in_array($estado, ['todos', 'activos', 'inactivos'], true)) {
    $estado = 'todos';
}

$pagina = max(1, (int) ($_GET['pagina'] ?? 1));
$porPagina = 10;

// AEROLÍNEAS
$listaAerolineas = $conexion->query(
    "SELECT codAerolinea, nombreAerolinea
     FROM aerolineas
     ORDER BY nombreAerolinea"
);

// FILTROS SQL
$condiciones = [];
$tipos = '';
$parametros = [];

if ($busqueda !== '') {
    $condiciones[] = "(
        v.origenVuelo LIKE ?
        OR v.destinoVuelo LIKE ?
        OR CAST(v.codVuelo AS CHAR) LIKE ?
    )";

    $tipos .= 'sss';

    $texto = '%' . $busqueda . '%';

    $parametros[] = $texto;
    $parametros[] = $texto;
    $parametros[] = $texto;
}

if ($aerolinea > 0) {
    $condiciones[] = "a.codAerolinea = ?";
    $tipos .= 'i';
    $parametros[] = $aerolinea;
}

if ($estado === 'activos') {
    $condiciones[] = "v.activoVuelo = 1";
}

if ($estado === 'inactivos') {
    $condiciones[] = "v.activoVuelo = 0";
}

$where = empty($condiciones)
    ? ''
    : 'WHERE ' . implode(' AND ', $condiciones);

$from = "
    FROM vuelos v
    INNER JOIN aerolineas a
        ON v.codAerolinea = a.codAerolinea
";

// CANTIDAD DE VUELOS
$sqlCantidad = "
    SELECT COUNT(*) AS cantidad
    $from
    $where
";

$stmtCantidad = ejecutarConsultaReporte(
    $conexion,
    $sqlCantidad,
    $tipos,
    $parametros
);

$cantidad = (int) $stmtCantidad->get_result()->fetch_assoc()['cantidad'];
$stmtCantidad->close();

$totalPaginas = max(1, (int) ceil($cantidad / $porPagina));
$pagina = min($pagina, $totalPaginas);
$inicio = ($pagina - 1) * $porPagina;

// CONSULTA DE VUELOS
$sql = "
    SELECT
        v.codVuelo,
        v.origenVuelo,
        v.destinoVuelo,
        v.fechaSalidaVuelo,
        v.horaSalidaVuelo,
        v.precioVuelo,
        v.asientosDisponibles,
        v.activoVuelo,
        a.nombreAerolinea
    $from
    $where
    ORDER BY v.fechaSalidaVuelo DESC,
             v.horaSalidaVuelo DESC,
             v.codVuelo DESC
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

$vuelos = $stmt->get_result()->fetch_all(MYSQLI_ASSOC);
$stmt->close();

// CONSULTA COMPLETA PARA IMPRESIÓN

$sqlImpresion = "
    SELECT
        v.codVuelo,
        v.origenVuelo,
        v.destinoVuelo,
        v.fechaSalidaVuelo,
        v.horaSalidaVuelo,
        v.precioVuelo,
        v.asientosDisponibles,
        v.activoVuelo,
        a.nombreAerolinea
    $from
    $where
    ORDER BY v.fechaSalidaVuelo DESC,
             v.horaSalidaVuelo DESC,
             v.codVuelo DESC
";

$stmtImpresion = ejecutarConsultaReporte(
    $conexion,
    $sqlImpresion,
    $tipos,
    $parametros
);

$vuelosImpresion = $stmtImpresion->get_result()->fetch_all(MYSQLI_ASSOC);
$stmtImpresion->close();
?>

<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <title>Nuvia - Reporte de Vuelos</title>

    <link rel="stylesheet" href="../../css/bootstrap.min.css">
    <link rel="stylesheet" href="../../css/bootstrap-icons.css">
    <link rel="stylesheet" href="../../css/navbar.css">
    <link rel="stylesheet" href="../../css/footer.css">
    <link rel="stylesheet" href="../../css/estilosAdmin.css">
    <link rel="stylesheet" href="../../css/reportesResponsive.css">

    <link rel="icon" type="image/png" href="../../imagenes/logo.png">
</head>

<body>

<?php include __DIR__ . '/../includes/navbarAdmin.php'; ?>

<main class="container reporte-contenido">

    <!-- ENCABEZADO -->
    <section class="reporte-encabezado">

        <div>

            <a href="gestionReportes.php"
               class="reporte-volver">

                <i class="bi bi-arrow-left"></i>
                Volver a reportes

            </a>

            <h1>Reporte de Vuelos</h1>

            <p class="reporte-descripcion">
                Consultá los vuelos registrados en Nuvia.
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

            <div class="col-12 col-md-6 col-lg-4">

                <label for="busqueda" class="form-label">
                    Buscar vuelo
                </label>

                <input type="search"
                       id="busqueda"
                       name="busqueda"
                       class="form-control"
                       placeholder="Origen, destino o código"
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

            <div class="col-12 col-md-6 col-lg-3">

                <label for="estado" class="form-label">
                    Estado
                </label>

                <select id="estado"
                        name="estado"
                        class="form-select">

                    <option value="todos"
                        <?= $estado === 'todos' ? 'selected' : '' ?>>
                        Todos
                    </option>

                    <option value="activos"
                        <?= $estado === 'activos' ? 'selected' : '' ?>>
                        Activos
                    </option>

                    <option value="inactivos"
                        <?= $estado === 'inactivos' ? 'selected' : '' ?>>
                        Inactivos
                    </option>

                </select>

            </div>

            <div class="col-12 col-lg-2 d-flex gap-2">

                <button type="submit" class="btn btn-primary">
                    Filtrar
                </button>

                <a href="vuelos.php" class="btn btn-outline-secondary">
                    Limpiar
                </a>

            </div>

        </form>

    </section>

    <!-- TOTAL -->
    <section class="my-4">

        <div class="card">

            <div class="card-body">

                <h2 class="h6">Vuelos encontrados</h2>

                <p class="fs-3 fw-bold mb-0">
                    <?= $cantidad ?>
                </p>

            </div>

        </div>

    </section>

    <!-- TABLA -->
    <section class="tabla-contenedor reporte-tabla reporte-tabla-pantalla">

        <div class="table-responsive">

            <table class="table table-striped align-middle">

                <thead>

                    <tr>
                        <th scope="col">Código</th>
                        <th scope="col">Aerolínea</th>
                        <th scope="col">Origen</th>
                        <th scope="col">Destino</th>
                        <th scope="col">Fecha</th>
                        <th scope="col">Hora</th>
                        <th scope="col">Precio</th>
                        <th scope="col">Asientos</th>
                        <th scope="col">Estado</th>
                    </tr>

                </thead>

                <tbody>

                <?php if (empty($vuelos)): ?>

                    <tr>
                        <td colspan="9" class="text-center py-4">
                            No se encontraron vuelos.
                        </td>
                    </tr>

                <?php else: ?>

                    <?php foreach ($vuelos as $vuelo): ?>

                        <tr>

                            <td data-label="Código">
                                #<?= (int) $vuelo['codVuelo'] ?>
                            </td>

                            <td data-label="Aerolínea">
                                <?= escapar($vuelo['nombreAerolinea']) ?>
                            </td>

                            <td data-label="Origen">
                                <?= escapar($vuelo['origenVuelo']) ?>
                            </td>

                            <td data-label="Destino">
                                <?= escapar($vuelo['destinoVuelo']) ?>
                            </td>

                            <td data-label="Fecha">
                                <?php
                                    $fecha = DateTime::createFromFormat(
                                        '!Y-m-d',
                                        $vuelo['fechaSalidaVuelo']
                                    );
                                ?>

                                <?= $fecha
                                    ? escapar($fecha->format('d/m/Y'))
                                    : escapar($vuelo['fechaSalidaVuelo']) ?>
                            </td>

                            <td data-label="Hora">
                                <?= escapar($vuelo['horaSalidaVuelo']) ?>
                            </td>

                            <td data-label="Precio">
                                $<?= number_format(
                                    (float) $vuelo['precioVuelo'],
                                    2,
                                    ',',
                                    '.'
                                ) ?>
                            </td>

                            <td data-label="Asientos">
                                <?= (int) $vuelo['asientosDisponibles'] ?>
                            </td>

                            <td data-label="Estado">

                                <?php if ((int) $vuelo['activoVuelo'] === 1): ?>

                                    <span class="badge bg-success">
                                        Activo
                                    </span>

                                <?php else: ?>

                                    <span class="badge bg-secondary">
                                        Inactivo
                                    </span>

                                <?php endif; ?>

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

        <nav aria-label="Paginación de vuelos" class="mt-4">

            <ul class="pagination justify-content-center flex-wrap">

                <?php for ($i = 1; $i <= $totalPaginas; $i++): ?>

                    <li class="page-item <?= $i === $pagina ? 'active' : '' ?>">

                        <a class="page-link"
                           href="<?= escapar(enlacePaginaVuelos($i)) ?>"
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
                <strong>Reporte:</strong> Vuelos Registrados
            </p>

            <p>
                <strong>Fecha de emisión:</strong>
                <?= date('d/m/Y H:i') ?> hs
            </p>

            <p>
                <strong>Emitido por:</strong> Administrador
            </p>

            <p>
                <strong>Total de vuelos:</strong>
                <?= $cantidad ?>
            </p>

        </div>

        <table class="table table-bordered align-middle">

            <thead>
                <tr>
                    <th>Código</th>
                    <th>Aerolínea</th>
                    <th>Origen</th>
                    <th>Destino</th>
                    <th>Fecha</th>
                    <th>Hora</th>
                    <th>Precio</th>
                    <th>Asientos</th>
                    <th>Estado</th>
                </tr>
            </thead>

            <tbody>

                <?php if (empty($vuelosImpresion)): ?>

                    <tr>
                        <td colspan="9">
                            No se encontraron vuelos.
                        </td>
                    </tr>

                <?php else: ?>

                    <?php foreach ($vuelosImpresion as $vuelo): ?>

                        <tr>

                            <td>
                                #<?= (int) $vuelo['codVuelo'] ?>
                            </td>

                            <td>
                                <?= escapar($vuelo['nombreAerolinea']) ?>
                            </td>

                            <td>
                                <?= escapar($vuelo['origenVuelo']) ?>
                            </td>

                            <td>
                                <?= escapar($vuelo['destinoVuelo']) ?>
                            </td>

                            <td>
                                <?= escapar($vuelo['fechaSalidaVuelo']) ?>
                            </td>

                            <td>
                                <?= escapar($vuelo['horaSalidaVuelo']) ?>
                            </td>

                            <td>
                                $<?= number_format(
                                    (float) $vuelo['precioVuelo'],
                                    2,
                                    ',',
                                    '.'
                                ) ?>
                            </td>

                            <td>
                                <?= (int) $vuelo['asientosDisponibles'] ?>
                            </td>

                            <td>
                                <?= (int) $vuelo['activoVuelo'] === 1
                                    ? 'Activo'
                                    : 'Inactivo' ?>
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