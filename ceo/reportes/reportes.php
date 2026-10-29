<?php

require_once __DIR__ . '/../../includes/verificarRol.php';
verificarRol('ceo');

include "../../php/consultasCeos.php";
include "../../php/consultasAerolineas.php";
include "../../php/consultasReportesCeo.php";

verificarCeo();

$codUsuario = obtenerCodCeo();

$aerolinea = obtenerAerolineaPorCeo($conexion, $codUsuario);

if (!$aerolinea) {

    echo "El CEO no tiene una aerolínea asignada.";
    exit();

}

$codAerolinea = $aerolinea["codAerolinea"];


// FILTROS

$desde = trim($_GET["desde"] ?? "");
$hasta = trim($_GET["hasta"] ?? "");
$estado = trim($_GET["estado"] ?? "");

$hoy = date("Y-m-d");

$estadosValidos = ["confirmada", "pendiente de pago", "cancelada"];

$error = "";

// Validar formato de fechas (YYYY-MM-DD)

foreach (["desde" => $desde, "hasta" => $hasta] as $campo => $valor) {

    if ($valor !== "" && !preg_match('/^\d{4}-\d{2}-\d{2}$/', $valor)) {
        $error = "Las fechas ingresadas no son válidas.";
    }

}

if ($estado !== "" && !in_array($estado, $estadosValidos, true)) {
    $estado = "";
}

if ($error === "") {
    if (($desde !== "" && $desde > $hoy) || ($hasta !== "" && $hasta > $hoy)) {
        $error = "Las fechas no pueden ser posteriores a la fecha actual.";
    }
}

if ($error === "" && $desde !== "" && $hasta !== "" && $hasta < $desde) {
    $error = "La fecha \"hasta\" no puede ser anterior a la fecha \"desde\".";
}

if ($error !== "") {

    // Con filtros inválidos no se consulta con ellos

    $desde = "";
    $hasta = "";

}


// DATOS DEL REPORTE

$resumen = obtenerResumenReservasAerolinea($conexion, $codAerolinea, $desde, $hasta, $estado);

$ventasPorVuelo = obtenerVentasPorVuelo($conexion, $codAerolinea, $desde, $hasta);

$reservas = obtenerReservasAerolinea($conexion, $codAerolinea, $desde, $hasta, $estado);


function claseEstadoReserva($estado)
{
    if ($estado == "confirmada") {
        return "bg-success";
    }

    if ($estado == "cancelada") {
        return "bg-danger";
    }

    return "bg-secondary";
}

?>

<!DOCTYPE html>

<html lang="es">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <title>Nuvia - Reportes</title>

    <link rel="icon" href="../../imagenes/logo.png" type="image/png">
    <link rel="stylesheet" href="../../css/bootstrap.min.css">
    <link rel="stylesheet" href="../../css/bootstrap-icons.css">
    <link rel="stylesheet" href="../../css/estilos-admin.css">
    <link rel="stylesheet" href="/PaginaWeb/css/navbar.css">
    <link rel="stylesheet" href="../../css/footer.css">

    <style>
        @media print {

            nav, .navbar, .btn, form, .d-print-none, footer {
                display: none !important;
            }

            body {
                background: #ffffff !important;
                color: #000000 !important;
                font-size: 11pt;
            }

            .tabla-contenedor {
                box-shadow: none !important;
                border: none !important;
            }

            th, td {
                border: 1px solid #cccccc !important;
            }
        }
    </style>

</head>

<body>

    <?php include "../includes/navbarCeo.php"; ?>

    <main class="contenido-admin">
        <section class="encabezado-contenido">
            <div>

                <h1>Reportes</h1>

                <p>
                    Ventas y reservas de
                    <strong><?php echo htmlspecialchars($aerolinea["nombreAerolinea"]); ?></strong>.
                </p>

            </div>

            <button type="button" class="btn btn-primary d-print-none" onclick="window.print()">
                <i class="bi bi-printer"></i>
                Imprimir reporte
            </button>
        </section>

        <!-- FILTROS -->
        <section class="tabla-contenedor mb-4 d-print-none">
            <form method="GET" class="row g-3 p-3 align-items-end">
                <div class="col-md-3">
                    <label for="desde" class="form-label">
                        Reservas desde
                    </label>

                    <input type="date" class="form-control" id="desde" name="desde" max="<?php echo $hoy; ?>" value="<?php echo htmlspecialchars($desde); ?>">
                </div>

                <div class="col-md-3">
                    <label for="hasta" class="form-label">
                        Reservas hasta
                    </label>

                    <input type="date" class="form-control" id="hasta" name="hasta" max="<?php echo $hoy; ?>" value="<?php echo htmlspecialchars($hasta); ?>">
                </div>

                <div class="col-md-3">
                    <label for="estado" class="form-label">
                        Estado
                    </label>

                    <select class="form-select" id="estado" name="estado">
                        <option value="">Todos</option>

                        <?php foreach ($estadosValidos as $e) { ?>

                            <option value="<?php echo $e; ?>" <?php echo ($estado === $e) ? "selected" : ""; ?>> 
                                <?php echo ucfirst($e); ?>
                            </option>

                        <?php } ?>
                    </select>
                </div>

                <div class="col-md-3">
                    <button type="submit" class="btn btn-primary">
                        <i class="bi bi-funnel"></i>
                        Filtrar
                    </button>

                    <a href="reportes.php" class="btn btn-secondary">
                        Limpiar
                    </a>
                </div>
            </form>
        </section>

        <?php if ($error !== "") { ?>

            <div class="alert alert-danger" role="alert">
                <?php echo htmlspecialchars($error); ?>
            </div>

        <?php } ?>

        <!-- RESUMEN -->
        <section class="resumen-admin">
            <h2>Resumen</h2>
            <div class="resumen-grid">
                <article class="resumen-item">

                    <i class="bi bi-journal-text"></i>

                    <div>
                        <h3>Reservas</h3>
                        <p class="numero-resumen">
                            <?php echo (int) $resumen["totalReservas"]; ?>
                        </p>
                    </div>

                </article>

                <article class="resumen-item">

                    <i class="bi bi-check-circle"></i>

                    <div>
                        <h3>Confirmadas</h3>
                        <p class="numero-resumen">
                            <?php echo (int) $resumen["confirmadas"]; ?>
                        </p>
                    </div>

                </article>

                <article class="resumen-item">

                    <i class="bi bi-people"></i>

                    <div>
                        <h3>Pasajeros</h3>
                        <p class="numero-resumen">
                            <?php echo (int) $resumen["pasajeros"]; ?>
                        </p>
                    </div>

                </article>

                <article class="resumen-item">

                    <i class="bi bi-cash-coin"></i>

                    <div>
                        <h3>Ingresos</h3>
                        <p class="numero-resumen">
                            $<?php echo number_format((float) $resumen["ingresos"], 2, ",", "."); ?>
                        </p>
                    </div>

                </article>
            </div>
        </section>


        <!-- VENTAS POR VUELO -->
        <section class="tabla-contenedor mb-4">

            <h2 class="h5 p-3 mb-0">Ventas confirmadas por vuelo</h2>

            <table class="table align-middle">
                <thead>
                    <tr>
                        <th>Vuelo</th>
                        <th>Ruta</th>
                        <th>Salida</th>
                        <th>Reservas</th>
                        <th>Pasajeros</th>
                        <th>Ingresos</th>
                    </tr>
                </thead>

                <tbody>

                    <?php if ($ventasPorVuelo->num_rows === 0) { ?>

                        <tr>
                            <td colspan="6" class="text-center text-muted">
                                No hay ventas confirmadas para los filtros seleccionados.
                            </td>
                        </tr>

                    <?php } ?>

                    <?php while ($fila = $ventasPorVuelo->fetch_assoc()) { ?>

                        <tr>
                            <td>#<?php echo $fila["codVuelo"]; ?></td>
                            <td>
                                <?php echo htmlspecialchars($fila["origenVuelo"]); ?>
                                <i class="bi bi-arrow-right"></i>
                                <?php echo htmlspecialchars($fila["destinoVuelo"]); ?>
                            </td>
                            <td>
                                <?php echo date("d/m/Y", strtotime($fila["fechaSalidaVuelo"])); ?>
                            </td>
                            <td><?php echo (int) $fila["reservas"]; ?></td>
                            <td><?php echo (int) $fila["pasajeros"]; ?></td>
                            <td>
                                $<?php echo number_format((float) $fila["ingresos"], 2, ",", "."); ?>
                            </td>
                        </tr>

                    <?php } ?>
                </tbody>
            </table>

        </section>


        <!-- DETALLE DE RESERVAS -->
        <section class="tabla-contenedor">

            <h2 class="h5 p-3 mb-0">Detalle de reservas</h2>

            <table class="table align-middle">
                <thead>
                    <tr>
                        <th>Reserva</th>
                        <th>Fecha de reserva</th>
                        <th>Vuelo</th>
                        <th>Pasajeros</th>
                        <th>Estado</th>
                        <th>Importe</th>
                    </tr>
                </thead>

                <tbody>

                    <?php if ($reservas->num_rows === 0) { ?>

                        <tr>
                            <td colspan="6" class="text-center text-muted">
                                No hay reservas para los filtros seleccionados.
                            </td>
                        </tr>

                    <?php } ?>

                    <?php while ($r = $reservas->fetch_assoc()) { ?>

                        <tr>
                            <td>#<?php echo $r["codReserva"]; ?></td>
                            <td>
                                <?php echo date("d/m/Y H:i", strtotime($r["fechaReserva"])); ?>
                            </td>
                            <td>
                                <?php echo htmlspecialchars($r["origenVuelo"]); ?>
                                <i class="bi bi-arrow-right"></i>
                                <?php echo htmlspecialchars($r["destinoVuelo"]); ?>
                                <br>
                                <small class="text-muted">
                                    <?php echo date("d/m/Y", strtotime($r["fechaSalidaVuelo"])); ?>
                                    <?php echo htmlspecialchars($r["horaSalidaVuelo"]); ?>
                                </small>
                            </td>
                            <td><?php echo (int) $r["cantidadPasajerosReserva"]; ?></td>
                            <td>
                                <span class="badge <?php echo claseEstadoReserva($r["estadoReserva"]); ?>">
                                    <?php echo ucfirst(htmlspecialchars($r["estadoReserva"])); ?>
                                </span>
                            </td>
                            <td>
                                $<?php echo number_format((float) $r["precioFinalReserva"], 2, ",", "."); ?>
                            </td>
                        </tr>

                    <?php } ?>

                </tbody>
            </table>
        </section>
    </main>

    <?php include "../../includes/footer.php"; ?>
    <script src="../../js/bootstrap.bundle.min.js"></script>

    <script>
        const desde = document.getElementById("desde");
        const hasta = document.getElementById("hasta");

        function sincronizarFechas() {
            hasta.min = desde.value || "";
            desde.max = hasta.value || "<?php echo $hoy; ?>";
        }

        desde.addEventListener("change", sincronizarFechas);
        hasta.addEventListener("change", sincronizarFechas);
        sincronizarFechas();
    </script>
</body>
</html>
