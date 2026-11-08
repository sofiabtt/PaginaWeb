<?php

require_once __DIR__ . '/../../includes/verificarRol.php';
verificarRol('administrador');

include "../../php/conexionBD.php";
include "../../php/consultasVuelos.php";


// PAGINACIÓN

require_once __DIR__ . '/../../php/paginacion.php';
require_once __DIR__ . '/../../php/conexionBD.php';
$cantidadVuelos = contarTodosLosVuelos($conexion);

$paginacion = configurarPaginacion($cantidadVuelos);

$pagina = $paginacion["pagina"];
$totalPaginas = $paginacion["totalPaginas"];

$porPagina = $paginacion["porPagina"];
$offset = $paginacion["offset"];

$resultado = obtenerTodosLosVuelosPaginados(
    $conexion,
    $porPagina,
    $offset
);

?>

<!DOCTYPE html>

<html lang="es">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <title>Nuvia - Administrador</title>
    <link rel="icon" type="image/png" href="../../imagenes/logo.png">

    <link rel="stylesheet" href="../../css/bootstrap.min.css">
    <link rel="stylesheet" href="../../css/bootstrap-icons.css">
    <link rel="stylesheet" href="<?= htmlspecialchars(urlApp('css/estilos.css'), ENT_QUOTES, 'UTF-8') ?>?v=20261108">
    <link rel="stylesheet" href="../../css/footer.css">

    <link rel="stylesheet" href="../../css/tablasResponsive.css">
    <link rel="stylesheet" href="../../css/paginacion.css">
</head>

<body class="nuvia-panel nuvia-navbar">
    <?php include __DIR__ . '/../includes/navbarAdmin.php';?>

    <main class="contenido-admin">
        <section class="encabezado-contenido">
            <div>
                <h1>
                    Vuelos
                </h1>
                
                <p>
                    Consultá todos los vuelos registrados
                    en Nuvia.
                </p>
            </div>
        </section>

        <p>
            Cantidad de vuelos registrados:
            <strong>
                <?php echo $cantidadVuelos; ?>
            </strong>
        </p>

        <section class="tabla-contenedor tabla-tarjetas">
            <table class="table align-middle">
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
                    <?php if ($cantidadVuelos === 0) { ?>
                        <tr>
                            <td
                                colspan="9"
                                class="text-center text-muted py-4"
                            >
                                No hay vuelos registrados.
                            </td>
                        </tr>

                    <?php } ?>

                    <?php while ($vuelo = $resultado->fetch_assoc()) { ?>

                        <tr>
                            <td data-label="Código">
                                <?php
                                echo (int) $vuelo["codVuelo"];
                                ?>
                            </td>

                            <td data-label="Aerolínea">
                                <?php
                                echo htmlspecialchars(
                                    $vuelo["nombreAerolinea"]
                                );
                                ?>
                                <br>
                                <small class="text-muted">
                                    <?php
                                    echo htmlspecialchars(
                                        $vuelo["codigoIATA"]
                                    );
                                    ?>
                                </small>
                            </td>

                            <td data-label="Origen">
                                <?php
                                echo htmlspecialchars(
                                    $vuelo["origenVuelo"]
                                );
                                ?>
                            </td>

                            <td data-label="Destino">
                                <?php
                                echo htmlspecialchars(
                                    $vuelo["destinoVuelo"]
                                );
                                ?>
                            </td>

                            <td data-label="Fecha">
                                <?php
                                echo date(
                                    "d/m/Y",
                                    strtotime(
                                        $vuelo["fechaSalidaVuelo"]
                                    )
                                );
                                ?>
                            </td>

                            <td data-label="Hora">
                                <?php
                                echo htmlspecialchars(
                                    $vuelo["horaSalidaVuelo"]
                                );
                                ?>
                            </td>

                            <td data-label="Precio" class="precio-vuelo">
                                <?php
                                echo "$ "
                                    . number_format(
                                        (float) $vuelo["precioVuelo"],
                                        0,
                                        ",",
                                        "."
                                    );
                                ?>
                            </td>

                            <td data-label="Asientos">
                                <?php
                                echo (int)
                                    $vuelo["asientosDisponibles"];

                                ?>
                            </td>

                            <td data-label="Estado">
                                <?php if ((int) $vuelo["activoVuelo"] === 1) { ?>

                                    <span class="badge bg-success">
                                        Activo
                                    </span>

                                <?php } else { ?>

                                    <span class="badge bg-secondary">
                                        Dado de baja
                                    </span>

                                <?php } ?>

                            </td>
                        </tr>

                    <?php } ?>

                </tbody>
            </table>
        </section>

        <?php include __DIR__ . '/../../php/paginacionVista.php'; ?>

    </main>

    <?php include __DIR__ . '/../../includes/footer.php'; ?>

    <script src="<?= htmlspecialchars(urlApp('js/bootstrap.bundle.min.js'), ENT_QUOTES, 'UTF-8') ?>"></script>

</body>

</html>