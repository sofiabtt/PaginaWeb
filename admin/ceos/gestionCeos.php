<?php

require_once __DIR__ . '/../../includes/verificarRol.php';
verificarRol('administrador');

include "../../php/consultasAerolineas.php";
include "../../php/consultasCeos.php";


// CANTIDAD DE AEROLÍNEAS ACTIVAS

$cantidadAerolineas = cantidadAerolineasActivas($conexion);


// PAGINACIÓN DE CEOs

require_once __DIR__ . '/../../php/paginacion.php';

$cantidadCeos = contarCeosActivos($conexion);

$paginacion = configurarPaginacion($cantidadCeos);

$pagina = $paginacion["pagina"];
$totalPaginas = $paginacion["totalPaginas"];

$porPagina = $paginacion["porPagina"];
$offset = $paginacion["offset"];


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
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <title> Nuvia - Administrador </title>

    <link rel="icon" href="../../imagenes/logo.png" type="image/png">

    <link rel="stylesheet" href="../../css/bootstrap.min.css">
    <link rel="stylesheet" href="../../css/bootstrap-icons.css">

    <link rel="stylesheet" href="../../css/estilosAdmin.css">
    <link rel="stylesheet" href="../../css/navbar.css">
    <link rel="stylesheet" href="../../css/footer.css">
    <link rel="stylesheet" href="../../css/tablasResponsive.css">
    <link rel="stylesheet" href="../../css/paginacion.css">

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

        <?php include __DIR__ . '/../../php/paginacionVista.php'; ?>

    </main>

    <?php include __DIR__ . '/../../includes/footer.php'; ?>

    <script src="<?= htmlspecialchars(urlApp('js/bootstrap.bundle.min.js'), ENT_QUOTES, 'UTF-8') ?>"></script>

</body>

</html>