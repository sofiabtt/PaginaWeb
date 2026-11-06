<?php
require_once __DIR__ . '/../../includes/verificarRol.php';
verificarRol('administrador');

include "../../php/conexionBD.php";
include "../../php/consultasNovedades.php";

// PAGINACIÓN

$porPagina = 10;

$totalRegistros = contarNovedadesActivas($conexion);
$totalPaginas = max(1, (int) ceil($totalRegistros / $porPagina));

$pagina = filter_input(INPUT_GET, 'pagina', FILTER_VALIDATE_INT);

$pagina = ($pagina !== false && $pagina !== null && $pagina > 0)
    ? $pagina
    : 1;

$pagina = min($pagina, $totalPaginas);

$offset = ($pagina - 1) * $porPagina;

$resultado = obtenerNovedadesActivasPaginadas(
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

    <link rel="stylesheet" href="../../css/bootstrap.min.css">

    <link rel="stylesheet" href="../../css/bootstrap-icons.css">

    <link rel="stylesheet" href="../../css/estilosAdmin.css">

    <link rel="stylesheet" href="../../css/navbar.css">

    <link rel="stylesheet" href="../../css/footer.css">

    <link rel="stylesheet" href="../../css/tablasResponsive.css">

    <link rel="icon" type="image/png" href="../../imagenes/logo.png">

</head>

<body>

    <!-- NAVBAR -->

    <?php include __DIR__ . '/../includes/navbarAdmin.php'; ?>


    <!-- CONTENIDO -->

    <main class="contenido-admin">

        <!-- ENCABEZADO -->

        <section class="encabezado-contenido">

            <div>

                <h1>
                    Gestión de Novedades
                </h1>

                <p>
                    Administra las novedades publicadas.
                </p>

            </div>

            <div>

                <a
                    href="novedadesInactivas.php"
                    class="btn btn-outline-secondary"
                >
                    <i class="bi bi-archive"></i>
                    Novedades dadas de baja
                </a>

                <a
                    href="crearNovedad.php"
                    class="btn btn-primary"
                >
                    <i class="bi bi-plus-lg"></i>
                    Crear novedad
                </a>

            </div>

        </section>


        <!-- TABLA -->

        <section class="tabla-contenedor tabla-tarjetas">

            <table class="table align-middle">

                <thead>

                    <tr>

                        <th scope="col">
                            Código
                        </th>

                        <th scope="col">
                            Novedad
                        </th>

                        <th scope="col">
                            Publicación
                        </th>

                        <th scope="col">
                            Expiración
                        </th>

                        <th scope="col">
                            Acciones
                        </th>

                    </tr>

                </thead>


                <tbody>

                    <?php while ($novedad = $resultado->fetch_assoc()) { ?>

                        <tr>

                            <!-- CÓDIGO -->

                            <td data-label="Código">

                                <?php
                                echo (int) $novedad["codNovedad"];
                                ?>

                            </td>


                            <!-- NOVEDAD -->

                            <td
                                class="texto-novedad"
                                data-label="Novedad"
                            >

                                <?php
                                echo htmlspecialchars(
                                    $novedad["textoNovedad"]
                                );
                                ?>

                            </td>


                            <!-- PUBLICACIÓN -->

                            <td data-label="Publicación">

                                <?php
                                echo htmlspecialchars(
                                    $novedad["fechaPublicacionNovedad"]
                                );
                                ?>

                            </td>


                            <!-- EXPIRACIÓN -->

                            <td data-label="Expiración">

                                <?php
                                echo htmlspecialchars(
                                    $novedad["fechaExpiracionNovedad"]
                                );
                                ?>

                            </td>


                            <!-- ACCIONES -->

                            <td
                                data-label="Acciones"
                                class="acciones-tabla"
                            >

                                <a
                                    href="verNovedad.php?id=<?php echo (int) $novedad["codNovedad"]; ?>"
                                    class="btn btn-sm btn-outline-secondary"
                                >
                                    <i class="bi bi-eye"></i>
                                    Ver
                                </a>


                                <a
                                    href="modificarNovedad.php?id=<?php echo (int) $novedad["codNovedad"]; ?>"
                                    class="btn btn-sm btn-outline-primary"
                                >
                                    <i class="bi bi-pencil"></i>
                                    Modificar
                                </a>


                                <a
                                    href="eliminarNovedad.php?id=<?php echo (int) $novedad["codNovedad"]; ?>"
                                    class="btn btn-sm btn-outline-danger"
                                >
                                    <i class="bi bi-trash"></i>
                                    Eliminar
                                </a>

                            </td>

                        </tr>

                    <?php } ?>


                    <!-- SIN REGISTROS -->

                    <?php if ($totalRegistros === 0) { ?>

                        <tr>

                            <td
                                colspan="5"
                                class="text-center py-4"
                            >
                                No hay novedades activas registradas.
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
                aria-label="Paginación de novedades"
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