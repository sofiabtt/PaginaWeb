<?php

require_once __DIR__ . '/../../includes/verificarRol.php';
verificarRol('administrador');

include "../../php/conexionBD.php";
include "../../php/consultasVuelos.php";


// PAGINACIÓN

$porPagina = 10;

$cantidadVuelos = contarTodosLosVuelos($conexion);

$totalPaginas = max(1, (int) ceil($cantidadVuelos / $porPagina));

$pagina = filter_input(INPUT_GET, "pagina", FILTER_VALIDATE_INT);

if ($pagina === false || $pagina === null || $pagina < 1) {
    $pagina = 1;
}

if ($pagina > $totalPaginas) {
    $pagina = $totalPaginas;
}

$offset = ($pagina - 1) * $porPagina;

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

    <meta
        name="viewport"
        content="width=device-width, initial-scale=1.0"
    >

    <title>Nuvia - Vuelos</title>

    <link
        rel="icon"
        type="image/png"
        href="<?= htmlspecialchars(urlApp('imagenes/logo.png'), ENT_QUOTES, 'UTF-8') ?>"
    >

    <link
        rel="stylesheet"
        href="../../css/bootstrap.min.css"
    >

    <link
        rel="stylesheet"
        href="../../css/bootstrap-icons.css"
    >

    <link
        rel="stylesheet"
        href="../../css/estilosAdmin.css"
    >

    <link
        rel="stylesheet"
        href="../../css/navbar.css"
    >

    <link
        rel="stylesheet"
        href="../../css/footer.css"
    >

    <link
        rel="stylesheet"
        href="../../css/tablasResponsive.css"
    >

</head>


<body>


    <?php
    include __DIR__ . '/../includes/navbarAdmin.php';
    ?>


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


                            <td
                                data-label="Precio"
                                class="precio-vuelo"
                            >

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


        <!-- PAGINACIÓN -->

        <?php if ($totalPaginas > 1) { ?>

            <nav
                class="paginacion-tablas"
                aria-label="Paginación de vuelos"
            >

                <p>
                    Página <?php echo $pagina; ?>
                    de <?php echo $totalPaginas; ?>
                </p>

                <div class="d-flex justify-content-center gap-2 flex-wrap">

                    <?php if ($pagina > 1) { ?>

                        <a
                            href="?pagina=<?php echo $pagina - 1; ?>"
                            class="btn btn-outline-secondary"
                        >
                            <i class="bi bi-chevron-left"></i>
                            Anterior
                        </a>

                    <?php } ?>


                    <?php if ($pagina < $totalPaginas) { ?>

                        <a
                            href="?pagina=<?php echo $pagina + 1; ?>"
                            class="btn btn-outline-secondary"
                        >
                            Siguiente
                            <i class="bi bi-chevron-right"></i>
                        </a>

                    <?php } ?>

                </div>

            </nav>

        <?php } ?>


    </main>


    <?php include __DIR__ . '/../../includes/footer.php'; ?>

    <script src="<?= htmlspecialchars(urlApp('js/bootstrap.bundle.min.js'), ENT_QUOTES, 'UTF-8') ?>"></script>


</body>

</html>