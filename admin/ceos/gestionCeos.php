<?php

session_start();

include "../../php/consultasAerolineas.php";
include "../../php/consultasCeos.php";

// CANTIDAD DE AEROLÍNEAS ACTIVAS

$cantidadAerolineas = cantidadAerolineasActivas($conexion);

// OBTENER CEOs

$consulta = obtenerCeos($conexion);

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
        href="../../css/estilos-admin.css"
    >

    <link rel="stylesheet" href="../../css/navbar.css">

    <!-- Favicon -->

    <link
        rel="icon"
        type="image/png"
        href="../../imagenes/logo.png"
    >

</head>


<body>

    <?php include "../includes/navbarAdmin.php"; ?>


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


        <section class="tabla-contenedor">

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


                <?php

                if (!$consulta) {

                    die(
                        "Error en la consulta: "
                        . $conexion->error
                    );

                }


                while ($ceo = $consulta->fetch_assoc()) {

                ?>

                    <tr>


                        <!-- NOMBRE -->

                        <td>

                            <?php

                            echo htmlspecialchars(
                                $ceo["nombreUsuario"]
                            );

                            ?>

                        </td>


                        <!-- EMAIL -->

                        <td>

                            <?php

                            echo htmlspecialchars(
                                $ceo["emailUsuario"]
                            );

                            ?>

                        </td>


                        <!-- AEROLÍNEA -->

                        <td>

                            <?php

                            echo $ceo["nombreAerolinea"]

                                ? htmlspecialchars(
                                    $ceo["nombreAerolinea"]
                                )

                                : "Sin aerolínea";

                            ?>

                        </td>


                        <!-- ESTADO -->

                        <td>

                            <?php

                            if ($ceo["verificado"] == 0) {

                            ?>

                                <span class="badge bg-warning text-dark">

                                    Pendiente

                                </span>

                            <?php

                            } else {

                            ?>

                                <span class="badge bg-success">

                                    Activo

                                </span>

                            <?php

                            }

                            ?>

                        </td>


                        <!-- ACCIONES -->

                        <td>

                            <a
                                href="eliminarCeo.php?id=<?php echo $ceo["codUsuario"]; ?>"
                                class="btn btn-sm btn-danger"
                            >

                                <i class="bi bi-trash"></i>

                            </a>

                        </td>


                    </tr>

                <?php

                }

                ?>


                </tbody>

            </table>

        </section>


    </main>


    <script src="../../js/bootstrap.bundle.min.js"></script>

</body>

</html>