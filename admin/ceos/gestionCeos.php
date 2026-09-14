<?php

include "../../php/conexionBD.php";

$consultaAerolineas = $conexion->query("
    SELECT COUNT(*) AS cantidad
    FROM Aerolineas
    WHERE activoAerolinea = 1
");

$cantidadAerolineas = $consultaAerolineas->fetch_assoc()["cantidad"];

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
                    <strong>Debe agregar una aerolínea para crear un CEO</strong>
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

                $consulta = $conexion->query("

                    SELECT
                        u.codUsuario,
                        u.nombreUsuario,
                        u.emailUsuario,
                        u.verificado,
                        a.nombreAerolinea

                    FROM Usuarios u

                    LEFT JOIN Aerolineas a
                        ON a.codCEO = u.codUsuario

                    WHERE u.tipoUsuario = 'ceo'

                    ORDER BY u.nombreUsuario

                ");

                if (!$consulta) {
                    die("Error en la consulta: " . $conexion->error);
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

                            if (
                                $ceo["verificado"] == 0
                            ) {

                            ?>

                                <h6 style="color: gray";>Pendiente</h6>


                            <?php

                            } else {

                            ?>
                                <h6 style="color: green";>Activo</h6>

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