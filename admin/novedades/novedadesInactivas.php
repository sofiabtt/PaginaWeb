<?php

include "../../php/conexionBD.php";


// OBTENER LAS NOVEDADES DADAS DE BAJA

$consulta = "SELECT *
             FROM Novedades
             WHERE activo = 0
             ORDER BY fechaEliminacion DESC";

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
        Admin - Novedades dadas de baja
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


    <!-- CSS administrador -->

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
                    Novedades dadas de baja
                </h1>

                <p>
                    Consulta las novedades que fueron dadas de baja del sistema.
                </p>

            </div>


            <a
                href="gestionNovedades.php"
                class="btn btn-secondary"
            >

                <i class="bi bi-arrow-left"></i>

                Volver a novedades

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
                            Novedad
                        </th>

                        <th>
                            Publicación
                        </th>

                        <th>
                            Expiración
                        </th>

                        <th>
                            Fecha de baja
                        </th>

                        <th>
                            Acciones
                        </th>

                    </tr>

                </thead>



                <tbody>

                    <?php while ($novedad = $resultado->fetch_assoc()) { ?>

                        <tr>

                            <td>
                                <?php echo $novedad["codNovedad"]; ?>
                            </td>


                            <td class="texto-novedad">

                                <?php
                                echo htmlspecialchars(
                                    $novedad["textoNovedad"]
                                );
                                ?>

                            </td>


                            <td>
                                <?php echo $novedad["fechaPublicacionNovedad"]; ?>
                            </td>


                            <td>
                                <?php echo $novedad["fechaExpiracionNovedad"]; ?>
                            </td>


                            <td>

                                <?php
                                echo date(
                                    "d/m/Y H:i",
                                    strtotime($novedad["fechaEliminacion"])
                                );
                                ?>

                            </td>


                            <td>

                                <a
                                    href="verNovedad.php?id=<?php echo $novedad["codNovedad"]; ?>&origen=inactivas"
                                    class="btn btn-sm btn-outline-secondary"
                                >
                                    <i class="bi bi-eye"></i>
                                    Ver
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

