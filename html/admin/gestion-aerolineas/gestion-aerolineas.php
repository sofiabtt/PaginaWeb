<?php

include "../../../php/conexionBD.php";


// OBTENER LAS AEROLÍNEAS

$consulta = "SELECT *
             FROM Aerolineas
             ORDER BY codAerolinea";

$resultado = $conexion->query($consulta);

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
        AeroFly Admin - Aerolíneas
    </title>


    <!-- Bootstrap -->

    <link
        rel="stylesheet"
        href="../../../css/bootstrap.min.css"
    >


    <!-- Bootstrap Icons -->

    <link
        rel="stylesheet"
        href="../../../css/bootstrap-icons.css"
    >


    <!-- CSS administrador -->

    <link
        rel="stylesheet"
        href="../../../css/estilos-admin.css"
    >


    <!-- Favicon -->

    <link
        rel="icon"
        type="image/png"
        href="../../../imagenes/logo.png"
    >

</head>


<body>


    <!-- NAVBAR -->

    <?php include "../includes/navbar-admin.php"; ?>


    <main class="contenido-admin">


        <!-- ENCABEZADO -->

        <section class="encabezado-contenido">

            <div>

                <h1>
                    Gestión de Aerolíneas
                </h1>

                <p>
                    Administra las aerolíneas registradas en el sistema.
                </p>

            </div>


            <a
                href="crear-aerolinea.php"
                class="btn btn-primary"
            >

                <i class="bi bi-plus-lg"></i>

                Crear aerolínea

            </a>

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

        <section class="tabla-contenedor">

            <table class="table align-middle">


                <thead>

                    <tr>

                        <th>
                            Código
                        </th>

                        <th>
                            Aerolínea
                        </th>

                        <th>
                            IATA
                        </th>

                        <th>
                            País
                        </th>

                    </tr>

                </thead>



                <tbody>


                    <?php while ($aerolinea = $resultado->fetch_assoc()) { ?>

                        <tr>


                            <td>

                                <?php
                                echo $aerolinea["codAerolinea"];
                                ?>

                            </td>


                            <td>

                                <?php
                                echo htmlspecialchars(
                                    $aerolinea["nombreAerolinea"]
                                );
                                ?>

                            </td>

                            <td>

                                <?php
                                echo htmlspecialchars(
                                    $aerolinea["codigoIATA"]
                                );
                                ?>

                            </td>


                            <td>

                                <?php
                                echo htmlspecialchars(
                                    $aerolinea["codPais"]
                                );
                                ?>

                            </td>


                            <td>

                                <a
                                    href="modificar-aerolinea.php?id=<?php echo $aerolinea["codAerolinea"]; ?>"
                                    class="btn btn-sm btn-outline-primary"
                                >
                                    <i class="bi bi-pencil"></i>
                                    Modificar
                                </a>


                                <a
                                    href="eliminar-aerolinea.php?id=<?php echo $aerolinea["codAerolinea"]; ?>"
                                    class="btn btn-sm btn-outline-danger"
                                >
                                    <i class="bi bi-trash"></i>
                                    Eliminar
                                </a>

                            </td>


                        </tr>

                    <?php } ?>


                </tbody>

            </table>

        </section>


    </main>


</body>

</html>