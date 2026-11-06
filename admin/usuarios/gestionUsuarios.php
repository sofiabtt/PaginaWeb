<?php

require_once __DIR__ . '/../../includes/verificarRol.php';
verificarRol('administrador');

include "../../php/consultasUsuarios.php";


// PAGINACIÓN

$porPagina = 10;

$totalUsuarios = contarUsuariosRegistrados($conexion);

$totalPaginas = max(1, (int) ceil($totalUsuarios / $porPagina));

$pagina = filter_input(INPUT_GET, "pagina", FILTER_VALIDATE_INT);

if ($pagina === false || $pagina === null || $pagina < 1) {
    $pagina = 1;
}

if ($pagina > $totalPaginas) {
    $pagina = $totalPaginas;
}

$inicio = ($pagina - 1) * $porPagina;


// OBTENER USUARIOS

$resultado = obtenerUsuariosPaginados(
    $conexion,
    $porPagina,
    $inicio
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

    <title>Nuvia - Usuarios</title>

    <link
        rel="icon"
        type="image/png"
        href="../../imagenes/logo.png"
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

    <!-- CSS TABLAS RESPONSIVE -->

    <link
        rel="stylesheet"
        href="../../css/tablasResponsive.css"
    >

</head>


<body>


    <?php include __DIR__ . '/../includes/navbarAdmin.php'; ?>


    <main class="contenido-admin">


        <section class="encabezado-contenido">

            <div>

                <h1>
                    Usuarios
                </h1>

                <p>
                    Consultá los usuarios registrados
                    en Nuvia.
                </p>

            </div>

        </section>


        <!-- CANTIDAD DE USUARIOS -->

        <p>

            Cantidad de usuarios registrados:

            <strong>
                <?php echo $totalUsuarios; ?>
            </strong>

        </p>


        <!-- TABLA RESPONSIVE -->

        <section class="tabla-contenedor tabla-tarjetas">


            <table class="table align-middle">


                <thead>

                    <tr>

                        <th>Código</th>

                        <th>Nombre</th>

                        <th>Email</th>

                        <th>Teléfono</th>

                        <th>Estado</th>

                    </tr>

                </thead>


                <tbody>


                    <!-- SIN USUARIOS -->

                    <?php if ($totalUsuarios === 0) { ?>

                        <tr>

                            <td
                                colspan="5"
                                class="text-center text-muted py-4"
                            >

                                No hay usuarios registrados.

                            </td>

                        </tr>

                    <?php } ?>


                    <!-- LISTADO DE USUARIOS -->

                    <?php while ($usuario = $resultado->fetch_assoc()) { ?>

                        <tr>


                            <!-- CÓDIGO -->

                            <td data-label="Código">

                                <?php
                                echo (int) $usuario["codUsuario"];
                                ?>

                            </td>


                            <!-- NOMBRE -->

                            <td data-label="Nombre">

                                <?php

                                echo htmlspecialchars(
                                    $usuario["nombreUsuario"]
                                );

                                ?>

                            </td>


                            <!-- EMAIL -->

                            <td data-label="Email">

                                <?php

                                echo htmlspecialchars(
                                    $usuario["emailUsuario"]
                                );

                                ?>

                            </td>


                            <!-- TELÉFONO -->

                            <td data-label="Teléfono">

                                <?php

                                echo htmlspecialchars(
                                    $usuario["telefonoUsuario"] ?? ""
                                );

                                ?>

                            </td>


                            <!-- ESTADO -->

                            <td data-label="Estado">

                                <?php if ((int) $usuario["verificado"] === 1) { ?>

                                    <span class="badge bg-success">

                                        Verificado

                                    </span>

                                <?php } else { ?>

                                    <span class="badge bg-warning text-dark">

                                        Pendiente

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
                aria-label="Paginación de usuarios"
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