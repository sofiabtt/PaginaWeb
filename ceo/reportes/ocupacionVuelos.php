<?php
require_once __DIR__ . '/../../includes/verificarRol.php';
verificarRol('ceo');
require_once __DIR__ . '/../../php/conexionBD.php';
require_once __DIR__ . '/../../php/consultasCeos.php';
require_once __DIR__ . '/../../php/consultasAerolineas.php';
require_once __DIR__ . '/../../php/consultasReportesCeo.php';
require_once __DIR__ . '/../../php/consultasReservas.php';
verificarCeo();
$codUsuario = obtenerCodCeo();
$aerolinea = obtenerAerolineaPorCeo($conexion, $codUsuario);
if (!$aerolinea) { exit('El CEO no tiene una aerolínea asignada.'); }
$codAerolinea = (int) $aerolinea['codAerolinea'];

function fechaReporteValida($valor) {
    if ($valor === '') return true;
    $fecha = DateTimeImmutable::createFromFormat('!Y-m-d', $valor);
    return $fecha !== false && $fecha->format('Y-m-d') === $valor;
}
function hReporte($valor) { return htmlspecialchars((string) $valor, ENT_QUOTES, 'UTF-8'); }

$desde = trim((string) ($_GET['desde'] ?? ''));
$hasta = trim((string) ($_GET['hasta'] ?? ''));
$error = '';
if (!fechaReporteValida($desde) || !fechaReporteValida($hasta)) {
    $error = 'Ingresá fechas válidas.';
} elseif ($desde !== '' && $hasta !== '' && $desde > $hasta) {
    $error = 'La fecha desde no puede ser posterior a la fecha hasta.';
}
$imprimir = ($_GET['imprimir'] ?? '') === '1';
$filas = [];
if ($error === '') {
    // Libera los asientos de reservas pendientes vencidas antes del cálculo.
    if (!cancelarReservasVencidas($conexion)) {
        $error = 'No se pudieron actualizar las reservas vencidas. Intentá nuevamente.';
    } else {
        $resultado = obtenerOcupacionVuelosAerolinea($conexion, $codAerolinea, $desde, $hasta);
        while ($fila = $resultado->fetch_assoc()) {
            $fila['confirmados'] = (int) $fila['confirmados'];
            $fila['pendientes'] = (int) $fila['pendientes'];
            $fila['asientosDisponibles'] = (int) $fila['asientosDisponibles'];
            $fila['capacidad'] = $fila['confirmados'] + $fila['pendientes'] + $fila['asientosDisponibles'];
            $fila['ocupacion'] = $fila['capacidad'] > 0
                ? $fila['confirmados'] * 100 / $fila['capacidad'] : 0;
            $filas[] = $fila;
        }
    }
}
$total = count($filas);
$porPagina = 10;
$totalPaginas = max(1, (int) ceil($total / $porPagina));
$pagina = max(1, min($totalPaginas, (int) ($_GET['pagina'] ?? 1)));
$visibles = $imprimir ? $filas : array_slice($filas, ($pagina - 1) * $porPagina, $porPagina);
$confirmadosTotal = array_sum(array_column($filas, 'confirmados'));
$pendientesTotal = array_sum(array_column($filas, 'pendientes'));
$disponiblesTotal = array_sum(array_column($filas, 'asientosDisponibles'));
$capacidadTotal = $confirmadosTotal + $pendientesTotal + $disponiblesTotal;
$ocupacionGlobal = $capacidadTotal > 0 ? $confirmadosTotal * 100 / $capacidadTotal : 0;

function enlaceOcupacion($cambios) {
    $params = array_merge($_GET, $cambios);
    return '?' . hReporte(http_build_query($params));
}
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Nuvia - Ocupación de vuelos</title>
    <link rel="icon" href="<?= hReporte(urlApp('imagenes/logo.png')) ?>" type="image/png">
    <link rel="stylesheet" href="<?= hReporte(urlApp('css/bootstrap.min.css')) ?>">
    <link rel="stylesheet" href="<?= hReporte(urlApp('css/bootstrap-icons.css')) ?>">
    <link rel="stylesheet" href="<?= hReporte(urlApp('css/estilos.css')) ?>">

</head>
<body class="nuvia-panel nuvia-navbar">
<?php if (!$imprimir): include __DIR__ . '/../includes/navbarCeo.php'; endif; ?>
<main class="contenido-admin container py-4" id="contenido">
    <div class="no-imprimir mb-3">
        <a href="gestionReportes.php" class="btn btn-outline-secondary"><i class="bi bi-arrow-left"></i> Volver a reportes</a>
    </div>
    <h1>Reporte de ocupación de vuelos</h1>
    <p>Aerolínea: <strong><?= hReporte($aerolinea['nombreAerolinea']) ?></strong></p>
    <p>La ocupación cuenta únicamente pasajeros con reserva confirmada. Las reservas pendientes bloquean asientos, pero se muestran aparte.</p>

    <?php if (!$imprimir): ?>
    <form method="GET" class="row g-3 align-items-end no-imprimir mb-4">
        <div class="col-md-4"><label for="desde" class="form-label">Salida desde</label>
            <input type="date" class="form-control" id="desde" name="desde" value="<?= hReporte($desde) ?>"></div>
        <div class="col-md-4"><label for="hasta" class="form-label">Salida hasta</label>
            <input type="date" class="form-control" id="hasta" name="hasta" value="<?= hReporte($hasta) ?>"></div>
        <div class="col-md-4 d-flex gap-2 flex-wrap">
            <button class="btn btn-primary" type="submit">Filtrar</button>
            <a class="btn btn-outline-secondary" href="ocupacionVuelos.php">Limpiar</a>
            <?php if ($error === ''): ?><a class="btn btn-outline-primary" href="<?= enlaceOcupacion(['imprimir' => 1, 'pagina' => null]) ?>" target="_blank" rel="noopener">Imprimir reporte</a><?php endif; ?>
        </div>
    </form>
    <?php else: ?>
        <p>Período de salida: <?= hReporte($desde !== '' ? $desde : 'Sin inicio') ?> al <?= hReporte($hasta !== '' ? $hasta : 'Sin fin') ?></p>
        <p class="no-imprimir"><a href="<?= enlaceOcupacion(['imprimir' => null]) ?>">Volver a la vista normal</a> · Usá Ctrl+P para imprimir.</p>
    <?php endif; ?>

    <?php if ($error !== ''): ?>
        <div class="alert alert-danger" role="alert"><?= hReporte($error) ?></div>
    <?php else: ?>
        <section class="row g-3 mb-4" aria-label="Resumen de ocupación">
            <div class="col-md-3"><div class="card p-3"><strong>Vuelos</strong><span><?= $total ?></span></div></div>
            <div class="col-md-3"><div class="card p-3"><strong>Asientos confirmados</strong><span><?= $confirmadosTotal ?></span></div></div>
            <div class="col-md-3"><div class="card p-3"><strong>Asientos pendientes</strong><span><?= $pendientesTotal ?></span></div></div>
            <div class="col-md-3"><div class="card p-3"><strong>Ocupación global</strong><span><?= number_format($ocupacionGlobal, 1, ',', '.') ?> %</span></div></div>
        </section>
        <p><strong>Capacidad total estimada:</strong> <?= $capacidadTotal ?> asientos · <strong>Disponibles:</strong> <?= $disponiblesTotal ?></p>
        <section aria-labelledby="tituloTabla">
            <h2 id="tituloTabla">Ocupación por vuelo</h2>
            <?php if (!$filas): ?>
                <p>No se encontraron vuelos para los filtros seleccionados.</p>
            <?php else: ?>
                <div class="tabla-contenedor tabla-tarjetas tabla-ocupacion">
                    <table class="table table-striped align-middle">
                        <thead><tr><th scope="col">Vuelo</th><th scope="col">Ruta</th><th scope="col">Salida</th><th scope="col">Capacidad</th><th scope="col">Confirmados</th><th scope="col">Pendientes</th><th scope="col">Disponibles</th><th scope="col">Ocupación</th></tr></thead>
                        <tbody>
                        <?php foreach ($visibles as $vuelo): ?>
                            <tr>
                                <td data-label="Vuelo">#<?= (int) $vuelo['codVuelo'] ?></td>
                                <td data-label="Ruta"><?= hReporte($vuelo['origenVuelo']) ?> → <?= hReporte($vuelo['destinoVuelo']) ?></td>
                                <td data-label="Salida"><?= hReporte(date('d/m/Y', strtotime($vuelo['fechaSalidaVuelo']))) ?> <?= hReporte($vuelo['horaSalidaVuelo']) ?></td>
                                <td data-label="Capacidad"><?= $vuelo['capacidad'] ?></td>
                                <td data-label="Confirmados"><?= $vuelo['confirmados'] ?></td>
                                <td data-label="Pendientes"><?= $vuelo['pendientes'] ?></td>
                                <td data-label="Disponibles"><?= $vuelo['asientosDisponibles'] ?></td>
                                <td data-label="Ocupación"><?= number_format($vuelo['ocupacion'], 1, ',', '.') ?> %</td>
                            </tr>
                        <?php endforeach; ?>
                        </tbody>
                    </table>
                </div>
                <?php if (!$imprimir && $totalPaginas > 1): ?>

                    <nav class="paginacion-tablas" aria-label="Paginación de vuelos">

                        <p>Página <?= $pagina ?> de <?= $totalPaginas ?></p>

                        <div class="d-flex justify-content-center gap-2 flex-wrap">

                            <?php if ($pagina > 1): ?>
                                <a
                                    class="btn btn-outline-secondary"
                                    href="<?= enlaceOcupacion(['pagina' => $pagina - 1]) ?>#tituloTabla"
                                >
                                    <i class="bi bi-chevron-left"></i>
                                    Anterior
                                </a>
                            <?php endif; ?>

                            <?php if ($pagina < $totalPaginas): ?>
                                <a
                                    class="btn btn-outline-secondary"
                                    href="<?= enlaceOcupacion(['pagina' => $pagina + 1]) ?>#tituloTabla"
                                >
                                    Siguiente
                                    <i class="bi bi-chevron-right"></i>
                                </a>
                            <?php endif; ?>

                        </div>

                    </nav>

                <?php endif; ?>
            <?php endif; ?>
        </section>
    <?php endif; ?>
</main>
<?php if (!$imprimir): include __DIR__ . '/../../includes/footer.php'; endif; ?>
<script src="<?= hReporte(urlApp('js/bootstrap.bundle.min.js')) ?>"></script>
</body>
</html>