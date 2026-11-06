<?php

require_once __DIR__ . '/../../includes/verificarRol.php';
verificarRol('administrador');

include "../../php/consultasAerolineas.php";


// PAGINACIÓN

$porPagina = 10;

$totalRegistros = contarAerolineasActivas($conexion);

$totalPaginas = max(1, (int) ceil($totalRegistros / $porPagina));

$pagina = filter_input(INPUT_GET, 'pagina', FILTER_VALIDATE_INT);

$pagina = ($pagina !== false && $pagina !== null && $pagina > 0)
    ? $pagina
    : 1;

$pagina = min($pagina, $totalPaginas);

$offset = ($pagina - 1) * $porPagina;

$resultado = obtenerAerolineasActivasPaginadas(
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

    <title>Nuvia - Gestión de Aerolíneas</title>

    <!-- Bootstrap -->
    <link rel="stylesheet" href="../../css/bootstrap.min.css">

    <!-- Bootstrap Icons -->
    <link rel="stylesheet" href="../../css/bootstrap-icons.css">

    <!-- CSS -->
    <link rel="stylesheet" href="../../css/estilosAdmin.css">
    <link rel="stylesheet" href="../../css/navbar.css">
    <link rel="stylesheet" href="../../css/footer.css">

    <!-- CSS responsive de tablas -->
    <link rel="stylesheet" href="../../css/tablasResponsive.css">

    <!-- Favicon -->
    <link rel="icon" type="image/png" href="../../imagenes/logo.png">

</head>

<body>

    <!-- NAVBAR -->

    <?php include __DIR__ . '/../includes/navbarAdmin.php'; ?>


    <main class="contenido-admin">

        <!-- ENCABEZADO -->

        <section class="encabezado-contenido">

            <div>

                <h1>Gestión de Aerolíneas</h1>

                <p>
                    Administra las aerolíneas registradas en el sistema.
                </p>

            </div>

            <div class="d-flex gap-2">

                <a
                    href="aerolineasInactivas.php"
                    class="btn btn-outline-secondary"
                >
                    <i class="bi bi-archive"></i>
                    Aerolíneas dadas de baja
                </a>

                <a
                    href="crearAerolinea.php"
                    class="btn btn-primary"
                >
                    <i class="bi bi-plus-lg"></i>
                    Crear aerolínea
                </a>

            </div>

        </section>


        <!-- MENSAJES -->

        <?php if (isset($_GET["creada"])) { ?>

            <div class="alert alert-success">
                La aerolínea se creó correctamente.
            </div>

        <?php } ?>


        <?php if (isset($_GET["modificada"])) { ?>

            <div class="alert alert-success">
                La aerolínea se modificó correctamente.
            </div>

        <?php } ?>


        <?php if (isset($_GET["eliminada"])) { ?>

            <div class="alert alert-success">
                La aerolínea se eliminó correctamente.
            </div>

        <?php } ?>


        <!-- TABLA -->

        <section class="tabla-contenedor tabla-tarjetas">

            <table class="table align-middle">

                <thead>

                    <tr>
                        <th scope="col">Código</th>
                        <th scope="col">Aerolínea</th>
                        <th scope="col">IATA</th>
                        <th scope="col">País</th>
                        <th scope="col">Acciones</th>
                    </tr>

                </thead>

                <tbody>

                    <?php while ($aerolinea = $resultado->fetch_assoc()) { ?>

                        <tr>

                            <td data-label="Código">
                                <?php echo (int) $aerolinea["codAerolinea"]; ?>
                            </td>

                            <td data-label="Aerolínea">
                                <?php echo htmlspecialchars($aerolinea["nombreAerolinea"]); ?>
                            </td>

                            <td data-label="IATA">
                                <?php echo htmlspecialchars($aerolinea["codigoIATA"]); ?>
                            </td>

                            <td data-label="País">
                                <?php echo htmlspecialchars($aerolinea["nombrePais"]); ?>
                            </td>

                            <td data-label="Acciones" class="acciones-tabla">

                                <a
                                    href="verAerolinea.php?id=<?php echo (int) $aerolinea["codAerolinea"]; ?>"
                                    class="btn btn-sm btn-outline-secondary"
                                >
                                    <i class="bi bi-eye"></i>
                                    Ver
                                </a>

                                <a
                                    href="modificarAerolinea.php?id=<?php echo (int) $aerolinea["codAerolinea"]; ?>"
                                    class="btn btn-sm btn-outline-primary"
                                >
                                    <i class="bi bi-pencil"></i>
                                    Modificar
                                </a>

                                <a
                                    href="eliminarAerolinea.php?id=<?php echo (int) $aerolinea["codAerolinea"]; ?>"
                                    class="btn btn-sm btn-outline-danger"
                                >
                                    <i class="bi bi-trash"></i>
                                    Eliminar
                                </a>

                            </td>

                        </tr>

                    <?php } ?>

                    <?php if ($totalRegistros === 0) { ?>

                        <tr>
                            <td colspan="5" class="text-center py-4">
                                No hay aerolíneas activas registradas.
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
                aria-label="Paginación de aerolíneas"
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