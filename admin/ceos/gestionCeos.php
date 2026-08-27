<?php

include "../../php/conexionBD.php";

?>

<!DOCTYPE html>
<html lang="es">

<head>

    <meta charset="UTF-8">

    <meta
        name="viewport"
        content="width=device-width, initial-scale=1.0"
    >

    <title>Admin - CEOs</title>


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


    <!-- =========================
         NAVBAR
    ========================== -->

    <?php include "../includes/navbarAdmin.php"; ?>



    <!-- =========================
         CONTENIDO
    ========================== -->

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


            <a
                href="agregar-ceo.php"
                class="btn btn-primary"
            >

                <i class="bi bi-plus-lg"></i>

                Agregar CEO

            </a>

        </section>



        <!-- =========================
             TABLA
        ========================== -->

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


                // =========================
                // OBTENER CEOs
                // =========================

                $consulta = $conexion->query("

                    SELECT

                        u.codUsuario,

                        u.nombreUsuario,

                        u.emailUsuario,

                        u.verificado,

                        u.debeCambiarClave,

                        a.nombreAerolinea

                    FROM Usuarios u

                    LEFT JOIN Aerolineas a

                        ON u.codAerolinea = a.codAerolinea

                    WHERE u.tipoUsuario = 'ceo'

                    ORDER BY u.nombreUsuario

                ");


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

                            if (
                                $ceo["debeCambiarClave"] == 1
                            ) {

                            ?>

                                <span
                                    class="badge bg-warning text-dark"
                                >

                                    Pendiente

                                </span>


                            <?php

                            } else {

                            ?>

                                <span
                                    class="badge bg-success"
                                >

                                    Activo

                                </span>


                            <?php

                            }

                            ?>


                        </td>



                        <!-- ACCIONES -->

                        <td>


                            <a
                                href="editar-ceo.php?id=<?php echo $ceo["codUsuario"]; ?>"
                                class="btn btn-sm btn-warning"
                            >

                                <i class="bi bi-pencil"></i>

                            </a>


                            <a
                                href="eliminar-ceo.php?id=<?php echo $ceo["codUsuario"]; ?>"
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