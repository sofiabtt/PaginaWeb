<?php
require_once __DIR__ . '/../../includes/verificarRol.php';
verificarRol('ceo');
require_once __DIR__ . '/../../php/conexionBD.php';
require_once __DIR__ . '/../../php/consultasCeos.php';
require_once __DIR__ . '/../../php/consultasAerolineas.php';
require_once __DIR__ . '/../../php/consultasReportesCeo.php';
verificarCeo();
$codUsuario = obtenerCodCeo();
$aerolinea = obtenerAerolineaPorCeo($conexion, $codUsuario);
if (!$aerolinea) { exit('El CEO no tiene una aerolínea asignada.'); }
$codAerolinea = (int) $aerolinea['codAerolinea'];

function hVentas($valor) { return htmlspecialchars((string) $valor, ENT_QUOTES, 'UTF-8'); }
function fechaVentasValida($valor) {
    if ($valor === '') return true;
    $fecha = DateTimeImmutable::createFromFormat('!Y-m-d', $valor);
    return $fecha !== false && $fecha->format('Y-m-d') === $valor;
}
function enlaceVentas($cambios) {
    return '?' . hVentas(http_build_query(array_merge($_GET, $cambios)));
}
function paginarVentas($datos, $parametro, $imprimir) {
    $total = count($datos);
    $paginas = max(1, (int) ceil($total / 10));
    $pagina = max(1, min($paginas, (int) ($_GET[$parametro] ?? 1)));
    return [$imprimir ? $datos : array_slice($datos, ($pagina - 1) * 10, 10), $pagina, $paginas];
}
function controlesVentas($pagina, $paginas, $parametro, $ancla) {
    if ($paginas <= 1) return;
    ?>

    <nav class="paginacion-tablas" aria-label="Paginación">

        <p>Página <?= $pagina ?> de <?= $paginas ?></p>

        <div class="d-flex justify-content-center gap-2 flex-wrap">

            <?php if ($pagina > 1): ?>
                <a
                    class="btn btn-outline-secondary"
                    href="<?= enlaceVentas([$parametro => $pagina - 1]) ?>#<?= hVentas($ancla) ?>"
                >
                    <i class="bi bi-chevron-left"></i>
                    Anterior
                </a>
            <?php endif; ?>

            <?php if ($pagina < $paginas): ?>
                <a
                    class="btn btn-outline-secondary"
                    href="<?= enlaceVentas([$parametro => $pagina + 1]) ?>#<?= hVentas($ancla) ?>"
                >
                    Siguiente
                    <i class="bi bi-chevron-right"></i>
                </a>
            <?php endif; ?>

        </div>

    </nav>

    <?php
}
$desde = trim((string) ($_GET['desde'] ?? ''));
$hasta = trim((string) ($_GET['hasta'] ?? ''));
$estado = (string) ($_GET['estado'] ?? '');
$estados = ['confirmada', 'pendiente de pago', 'cancelada'];
$error = '';
if (!fechaVentasValida($desde) || !fechaVentasValida($hasta)) {
    $error = 'Ingresá fechas válidas.';
} elseif ($desde !== '' && $hasta !== '' && $desde > $hasta) {
    $error = 'La fecha desde no puede ser posterior a la fecha hasta.';
} elseif ($estado !== '' && !in_array($estado, $estados, true)) {
    $error = 'El estado seleccionado no es válido.';
}
$imprimir = ($_GET['imprimir'] ?? '') === '1';
$resumen = ['totalReservas' => 0, 'confirmadas' => 0, 'pasajeros' => 0, 'ingresos' => 0];
$reservas = [];
$ventasPorVuelo = [];
if ($error === '') {
    $resumen = obtenerResumenReservasAerolinea($conexion, $codAerolinea, $desde, $hasta, $estado);
    $resultado = obtenerReservasAerolinea($conexion, $codAerolinea, $desde, $hasta, $estado);
    while ($fila = $resultado->fetch_assoc()) $reservas[] = $fila;
    // La tabla de ventas por vuelo muestra solamente reservas confirmadas.
    // Si se filtra por pendientes o canceladas, no corresponde mostrar ventas.
    if ($estado === '' || $estado === 'confirmada') {
        $resultado = obtenerVentasPorVuelo($conexion, $codAerolinea, $desde, $hasta);
        while ($fila = $resultado->fetch_assoc()) $ventasPorVuelo[] = $fila;
    }
}
[$reservasVisibles, $paginaReservas, $paginasReservas] = paginarVentas($reservas, 'paginaReservas', $imprimir);
[$vuelosVisibles, $paginaVuelos, $paginasVuelos] = paginarVentas($ventasPorVuelo, 'paginaVuelos', $imprimir);
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Nuvia - Reporte de ventas</title>
    <link rel="icon" href="<?= hVentas(urlApp('imagenes/logo.png')) ?>" type="image/png">
    <link rel="stylesheet" href="<?= hVentas(urlApp('css/bootstrap.min.css')) ?>">
    <link rel="stylesheet" href="<?= hVentas(urlApp('css/bootstrap-icons.css')) ?>">
    <link rel="stylesheet" href="<?= hVentas(urlApp('css/estilos.css')) ?>">


</head>
<body class="nuvia-panel nuvia-navbar">
<?php if (!$imprimir): include __DIR__ . '/../includes/navbarCeo.php'; endif; ?>
<main class="contenido-admin container py-4" id="contenido">
    <div class="no-imprimir mb-3"><a href="gestionReportes.php" class="btn btn-outline-secondary"><i class="bi bi-arrow-left"></i> Volver a reportes</a></div>
    <h1>Reporte de ventas</h1>
    <p>Aerolínea: <strong><?= hVentas($aerolinea['nombreAerolinea']) ?></strong></p>
    <p>Los ingresos se calculan a partir de las reservas confirmadas.</p>

    <?php if (!$imprimir): ?>
    <form method="GET" class="row g-3 align-items-end mb-4 no-imprimir">
        <div class="col-md-3"><label class="form-label" for="desde">Reserva desde</label><input class="form-control" type="date" name="desde" id="desde" value="<?= hVentas($desde) ?>"></div>
        <div class="col-md-3"><label class="form-label" for="hasta">Reserva hasta</label><input class="form-control" type="date" name="hasta" id="hasta" value="<?= hVentas($hasta) ?>"></div>
        <div class="col-md-3"><label class="form-label" for="estado">Estado</label><select class="form-select" id="estado" name="estado">
            <option value="">Todos</option>
            <?php foreach ($estados as $opcion): ?><option value="<?= hVentas($opcion) ?>" <?= $estado === $opcion ? 'selected' : '' ?>><?= hVentas(ucfirst($opcion)) ?></option><?php endforeach; ?>
        </select></div>
        <div class="col-md-3 d-flex gap-2 flex-wrap"><button class="btn btn-primary" type="submit">Filtrar</button><a class="btn btn-outline-secondary" href="ventas.php">Limpiar</a>
            <?php if ($error === ''): ?><a class="btn btn-outline-primary" href="<?= enlaceVentas(['imprimir' => 1, 'paginaReservas' => null, 'paginaVuelos' => null]) ?>" target="_blank" rel="noopener">Imprimir reporte</a><?php endif; ?>
        </div>
    </form>
    <?php else: ?>
        <p>Período de reservas: <?= hVentas($desde !== '' ? $desde : 'Sin inicio') ?> al <?= hVentas($hasta !== '' ? $hasta : 'Sin fin') ?> · Estado: <?= hVentas($estado !== '' ? $estado : 'Todos') ?></p>
        <p class="no-imprimir"><a href="<?= enlaceVentas(['imprimir' => null]) ?>">Volver</a> · Usá Ctrl+P para imprimir.</p>
    <?php endif; ?>

    <?php if ($error !== ''): ?>
        <div class="alert alert-danger" role="alert"><?= hVentas($error) ?></div>
    <?php else: ?>
        <section class="row g-3 mb-4" aria-label="Resumen de ventas">
            <div class="col-md-3"><div class="card p-3"><strong>Reservas</strong><span><?= (int) $resumen['totalReservas'] ?></span></div></div>
            <div class="col-md-3"><div class="card p-3"><strong>Confirmadas</strong><span><?= (int) $resumen['confirmadas'] ?></span></div></div>
            <div class="col-md-3"><div class="card p-3"><strong>Pasajeros confirmados</strong><span><?= (int) $resumen['pasajeros'] ?></span></div></div>
            <div class="col-md-3"><div class="card p-3"><strong>Ingresos</strong><span>$<?= number_format((float) $resumen['ingresos'], 2, ',', '.') ?></span></div></div>
        </section>

        <section class="mb-5" aria-labelledby="tituloPorVuelo">
            <h2 id="tituloPorVuelo">Ventas confirmadas por vuelo</h2>
            <?php if (!$ventasPorVuelo): ?><p>No hay ventas confirmadas para los filtros seleccionados.</p>
            <?php else: ?>
            <div class="tabla-contenedor tabla-tarjetas tabla-reporte-ventas"><table class="table table-striped align-middle">
                <thead><tr><th scope="col">Vuelo</th><th scope="col">Ruta</th><th scope="col">Salida</th><th scope="col">Reservas</th><th scope="col">Pasajeros</th><th scope="col">Ingresos</th></tr></thead>
                <tbody><?php foreach ($vuelosVisibles as $vuelo): ?><tr>
                    <td data-label="Vuelo">#<?= (int) $vuelo['codVuelo'] ?></td>
                    <td data-label="Ruta"><?= hVentas($vuelo['origenVuelo']) ?> → <?= hVentas($vuelo['destinoVuelo']) ?></td>
                    <td data-label="Salida"><?= hVentas(date('d/m/Y', strtotime($vuelo['fechaSalidaVuelo']))) ?></td>
                    <td data-label="Reservas"><?= (int) $vuelo['reservas'] ?></td>
                    <td data-label="Pasajeros"><?= (int) $vuelo['pasajeros'] ?></td>
                    <td data-label="Ingresos">$<?= number_format((float) $vuelo['ingresos'], 2, ',', '.') ?></td>
                </tr><?php endforeach; ?></tbody>
            </table></div>
            <?php if (!$imprimir) controlesVentas($paginaVuelos, $paginasVuelos, 'paginaVuelos', 'tituloPorVuelo'); ?>
            <?php endif; ?>
        </section>

        <section aria-labelledby="tituloReservas">
            <h2 id="tituloReservas">Detalle de reservas</h2>
            <?php if (!$reservas): ?><p>No hay reservas para los filtros seleccionados.</p>
            <?php else: ?>
            <div class="tabla-contenedor tabla-tarjetas tabla-reporte-ventas"><table class="table table-striped align-middle">
                <thead><tr><th scope="col">Reserva</th><th scope="col">Fecha de reserva</th><th scope="col">Vuelo</th><th scope="col">Pasajeros</th><th scope="col">Estado</th><th scope="col">Importe</th></tr></thead>
                <tbody><?php foreach ($reservasVisibles as $reserva): ?><tr>
                    <td data-label="Reserva">#<?= (int) $reserva['codReserva'] ?></td>
                    <td data-label="Fecha de reserva"><?= hVentas(date('d/m/Y H:i', strtotime($reserva['fechaReserva']))) ?></td>
                    <td data-label="Vuelo">#<?= (int) $reserva['codVuelo'] ?> · <?= hVentas($reserva['origenVuelo']) ?> → <?= hVentas($reserva['destinoVuelo']) ?></td>
                    <td data-label="Pasajeros"><?= (int) $reserva['cantidadPasajerosReserva'] ?></td>
                    <td data-label="Estado"><?= hVentas(ucfirst($reserva['estadoReserva'])) ?></td>
                    <td data-label="Importe">$<?= number_format((float) $reserva['precioFinalReserva'], 2, ',', '.') ?></td>
                </tr><?php endforeach; ?></tbody>
            </table></div>
            <?php if (!$imprimir) controlesVentas($paginaReservas, $paginasReservas, 'paginaReservas', 'tituloReservas'); ?>
            <?php endif; ?>
        </section>
    <?php endif; ?>
</main>
<?php if (!$imprimir): include __DIR__ . '/../../includes/footer.php'; endif; ?>
<script src="<?= hVentas(urlApp('js/bootstrap.bundle.min.js')) ?>"></script>
</body>
</html>