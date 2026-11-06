<?php

require_once __DIR__ . '/../../includes/verificarRol.php';
verificarRol('administrador');

include "../../php/consultasAerolineas.php";
include "../../php/consultasCeos.php";


// CANTIDAD DE AEROLÍNEAS ACTIVAS

$cantidadAerolineas = cantidadAerolineasActivas($conexion);


// PAGINACIÓN DE CEOs

$porPagina = 10;

$cantidadCeos = contarCeosActivos($conexion);

$totalPaginas = max(1, (int) ceil($cantidadCeos / $porPagina));

$pagina = filter_input(INPUT_GET, "pagina", FILTER_VALIDATE_INT);

if ($pagina === false || $pagina === null || $pagina < 1) {
    $pagina = 1;
}

if ($pagina > $totalPaginas) {
    $pagina = $totalPaginas;
}

$offset = ($pagina - 1) * $porPagina;


// OBTENER CEOs DE LA PÁGINA ACTUAL

$consulta = obtenerCeosPaginados(
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

    <title>Nuvia - Administrador</title>


    <!-- Bootstrap -->

    <link
        rel="stylesheet"
        href="../../css/bootstrap.min.css"
    >


    <!-- Bootstrap Icons -->

    <link
        rel="stylesheet"
        href="../../css/bootstrap-icons.css"
    >


    <!-- CSS del administrador -->

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


    <!-- CSS TABLAS RESPONSIVE -->

    <link
        rel="stylesheet"
        href="../../css/tablasResponsive.css"
    >


    <!-- Favicon -->

    <link
        rel="icon"
        type="image/png"
        href="../../imagenes/logo.png"
    >

</head>


<body>

    <?php include __DIR__ . '/../includes/navbarAdmin.php'; ?>


    <main class="contenido-admin">


        <section class="encabezado-contenido">

            <div>

                <h1>
                    Gestión de CEOs
                </h1>

                <p>
                    Administra los CEOs registrados en el sistema.
                </p>

            </div>


            <?php if ($cantidadAerolineas > 0) { ?>

                <a
                    href="agregarCeo.php"
                    class="btn btn-primary"
                >

                    <i class="bi bi-plus-lg"></i>

                    Agregar CEO

                </a>

            <?php } else { ?>

                <span
                    class="text-muted"
                    title="Primero debe existir al menos una aerolínea activa"
                >

                    <strong>
                        Debe agregar una aerolínea para crear un CEO
                    </strong>

                </span>

            <?php } ?>

        </section>


        <!-- TABLA DE CEOs -->

        <section class="tabla-contenedor tabla-tarjetas">

            <table class="table align-middle">

                <thead>

                    <tr>

                        <th>
                            Nombre
                        </th>

                        <th>
                            Email
                        </th>

                        <th>
                            Aerolínea
                        </th>

                        <th>
                            Estado
                        </th>

                        <th>
                            Acciones
                        </th>

                    </tr>

                </thead>


                <tbody>


                    <!-- SIN CEOs -->

                    <?php if ($cantidadCeos === 0) { ?>

                        <tr>

                            <td
                                colspan="5"
                                class="text-center text-muted py-4"
                            >

                                No hay CEOs registrados.

                            </td>

                        </tr>

                    <?php } ?>


                    <!-- CEOs -->

                    <?php while ($ceo = $consulta->fetch_assoc()) { ?>

                        <tr>


                            <!-- NOMBRE -->

                            <td data-label="Nombre">

                                <?php

                                echo htmlspecialchars(
                                    $ceo["nombreUsuario"]
                                );

                                ?>

                            </td>


                            <!-- EMAIL -->

                            <td data-label="Email">

                                <?php

                                echo htmlspecialchars(
                                    $ceo["emailUsuario"]
                                );

                                ?>

                            </td>


                            <!-- AEROLÍNEA -->

                            <td data-label="Aerolínea">

                                <?php

                                echo $ceo["nombreAerolinea"]

                                    ? htmlspecialchars(
                                        $ceo["nombreAerolinea"]
                                    )

                                    : "Sin aerolínea";

                                ?>

                            </td>


                            <!-- ESTADO -->

                            <td data-label="Estado">

                                <?php if ($ceo["verificado"] == 0) { ?>

                                    <span class="badge bg-warning text-dark">

                                        Pendiente

                                    </span>

                                <?php } else { ?>

                                    <span class="badge bg-success">

                                        Activo

                                    </span>

                                <?php } ?>

                            </td>


                            <!-- ACCIONES -->

                            <td data-label="Acciones">

                                <div class="acciones-tabla">

                                    <a
                                        href="eliminarCeo.php?id=<?php echo (int) $ceo["codUsuario"]; ?>"
                                        class="btn btn-sm btn-danger"
                                        aria-label="Eliminar CEO <?php echo htmlspecialchars($ceo["nombreUsuario"], ENT_QUOTES, 'UTF-8'); ?>"
                                    >

                                        <i class="bi bi-trash"></i>

                                    </a>

                                </div>

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
                aria-label="Paginación de CEOs"
            >

                <p>

                    Página <?php echo $pagina; ?>
                    de <?php echo $totalPaginas; ?>

                </p>


                <div class="d-flex justify-content-center gap-2 flex-wrap">


                    <!-- ANTERIOR -->

                    <?php if ($pagina > 1) { ?>

                        <a
                            href="?pagina=<?php echo $pagina - 1; ?>"
                            class="btn btn-outline-secondary"
                        >

                            <i class="bi bi-chevron-left"></i>

                            Anterior

                        </a>

                    <?php } ?>


                    <!-- SIGUIENTE -->

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


    <!-- FOOTER -->

    <?php include __DIR__ . '/../../includes/footer.php'; ?>


    <!-- BOOTSTRAP JS -->

    <script src="<?= htmlspecialchars(urlApp('js/bootstrap.bundle.min.js'), ENT_QUOTES, 'UTF-8') ?>"></script>

</body>

</html>