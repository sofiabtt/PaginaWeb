<?php

session_start();

if (
    !isset($_SESSION["tipoUsuario"]) ||
    $_SESSION["tipoUsuario"] !== "administrador"
) {

    header("Location: ../../inicioSesion.php");

    exit();

}

include "../../php/conexionBD.php";
include "../../php/consultasUsuarios.php";

$porPagina = 10;

$pagina = max(1, filter_input(INPUT_GET,"pagina",FILTER_VALIDATE_INT) ?: 1);

$inicio = ($pagina - 1) * $porPagina;

// CANTIDAD TOTAL DE USUARIOS

$total = cantidadUsuarios($conexion);

$totalPaginas = max(1,(int) ceil($total / $porPagina));


if ($pagina > $totalPaginas) {

    $pagina = $totalPaginas;

    $inicio = ($pagina - 1) * $porPagina;

}

// OBTENER USUARIOS

$resultado = obtenerUsuariosPaginados($conexion,$porPagina,$inicio);

?>


<!DOCTYPE html>

<html lang="es">

<head>

    <meta charset="UTF-8">

    <meta
        name="viewport"
        content="width=device-width, initial-scale=1.0"
    >

    <title>
        Nuvia - Reporte de usuarios
    </title>


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
        href="../../css/estilos-admin.css"
    >


    <!-- Favicon -->

    <link
        rel="icon"
        type="image/png"
        href="../../imagenes/logo.png"
    >

</head>


<body>


    <!-- NAVBAR -->

    <?php include "../includes/navbarAdmin.php"; ?>


    <main class="contenido-admin">


        <!-- ENCABEZADO -->

        <section class="encabezado-contenido">

            <div>

                <h1>
                    Reporte de usuarios
                </h1>

                <p>
                    <?php echo $total; ?>
                    pasajeros registrados en el sistema.
                </p>

            </div>


            <a
                class="btn btn-secondary"
                href="../reportes.php"
            >

                <i class="bi bi-arrow-left"></i>

                Volver

            </a>

        </section>



        <!-- TABLA -->

        <section class="tabla-contenedor">

            <table class="table align-middle">

                <thead>

                    <tr>

                        <th>
                            Código
                        </th>

                        <th>
                            Nombre
                        </th>

                        <th>
                            Email
                        </th>

                        <th>
                            Teléfono
                        </th>

                        <th>
                            Estado
                        </th>

                    </tr>

                </thead>


                <tbody>


                    <?php if ($resultado->num_rows === 0) { ?>

                        <tr>

                            <td
                                colspan="5"
                                class="text-center text-muted py-4"
                            >

                                No hay pasajeros registrados.

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

                                <span
                                    class="badge <?php echo (int) $usuario["verificado"] === 1
                                        ? "bg-success"
                                        : "bg-warning text-dark"; ?>"
                                >

                                    <?php
                                    echo (int) $usuario["verificado"] === 1
                                        ? "Verificado"
                                        : "Pendiente";
                                    ?>

                                </span>

                            </td>

                        </tr>

                    <?php } ?>


                </tbody>

            </table>

        </section>



        <!-- PAGINACIÓN -->

        <?php if ($totalPaginas > 1) { ?>

            <nav
                aria-label="Páginas del reporte"
            >

                <ul
                    class="pagination justify-content-center mt-4"
                >


                    <?php for ($i = 1; $i <= $totalPaginas; $i++) { ?>

                        <li
                            class="page-item <?php echo $i === $pagina
                                ? "active"
                                : ""; ?>"
                        >

                            <a
                                class="page-link"
                                href="?pagina=<?php echo $i; ?>"
                            >

                                <?php echo $i; ?>

                            </a>

                        </li>

                    <?php } ?>


                </ul>

            </nav>

        <?php } ?>


    </main>


    <script src="../../js/bootstrap.bundle.min.js"></script>

</body>

</html>


<?php

$conexion->close();

?>