<?php

session_start();

include "../../php/consultasUsuarios.php";


// VERIFICAR ADMINISTRADOR

if (
    !isset($_SESSION["codUsuario"]) ||
    !isset($_SESSION["tipoUsuario"]) ||
    $_SESSION["tipoUsuario"] !== "administrador"
) {
    header("Location: ../../inicioSesion.php");
    exit();
}


// PAGINACIÓN

$porPagina = 10;

$pagina = isset($_GET["pagina"])
    ? (int) $_GET["pagina"]
    : 1;

if ($pagina < 1) {
    $pagina = 1;
}


$totalUsuarios = cantidadUsuarios($conexion);

$totalPaginas = (int) ceil(
    $totalUsuarios / $porPagina
);


if (
    $totalPaginas > 0 &&
    $pagina > $totalPaginas
) {
    $pagina = $totalPaginas;
}


$inicio = ($pagina - 1) * $porPagina;


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
        href="/PaginaWeb/imagenes/logo.png"
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
        href="../../css/estilos-admin.css"
    >

    <link
        rel="stylesheet"
        href="../../css/navbar.css"
    >

</head>


<body>


<?php
include "../includes/navbarAdmin.php";
?>


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


    <p>

        Cantidad de usuarios registrados:

        <strong>
            <?php echo $totalUsuarios; ?>
        </strong>

    </p>


    <section class="tabla-contenedor">


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


            <?php if ($resultado->num_rows === 0) { ?>

                <tr>

                    <td
                        colspan="5"
                        class="text-center text-muted py-4"
                    >
                        No hay usuarios registrados.
                    </td>

                </tr>

            <?php } ?>


            <?php while ($usuario = $resultado->fetch_assoc()) { ?>

                <tr>

                    <td>

                        <?php
                        echo (int) $usuario["codUsuario"];
                        ?>

                    </td>


                    <td>

                        <?php
                        echo htmlspecialchars(
                            $usuario["nombreUsuario"]
                        );
                        ?>

                    </td>


                    <td>

                        <?php
                        echo htmlspecialchars(
                            $usuario["emailUsuario"]
                        );
                        ?>

                    </td>


                    <td>

                        <?php
                        echo htmlspecialchars(
                            $usuario["telefonoUsuario"]
                        );
                        ?>

                    </td>


                    <td>

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


        <?php if ($totalPaginas > 1) { ?>

            <nav
                class="mt-4"
                aria-label="Paginación de usuarios"
            >

                <ul class="pagination justify-content-center">


                    <li
                        class="page-item
                        <?php
                        echo $pagina <= 1
                            ? "disabled"
                            : "";
                        ?>"
                    >

                        <a
                            class="page-link"
                            href="?pagina=<?php echo $pagina - 1; ?>"
                        >
                            Anterior
                        </a>

                    </li>


                    <?php for (
                        $i = 1;
                        $i <= $totalPaginas;
                        $i++
                    ) { ?>

                        <li
                            class="page-item
                            <?php
                            echo $i === $pagina
                                ? "active"
                                : "";
                            ?>"
                        >

                            <a
                                class="page-link"
                                href="?pagina=<?php echo $i; ?>"
                            >
                                <?php echo $i; ?>
                            </a>

                        </li>

                    <?php } ?>


                    <li
                        class="page-item
                        <?php
                        echo $pagina >= $totalPaginas
                            ? "disabled"
                            : "";
                        ?>"
                    >

                        <a
                            class="page-link"
                            href="?pagina=<?php echo $pagina + 1; ?>"
                        >
                            Siguiente
                        </a>

                    </li>


                </ul>

            </nav>

        <?php } ?>


    </section>


</main>


<script src="../../js/bootstrap.bundle.min.js"></script>


</body>

</html>