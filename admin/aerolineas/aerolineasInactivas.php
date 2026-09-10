<?php

include "../../php/conexionBD.php";


// OBTENER LAS AEROLÍNEAS DADAS DE BAJA

$consulta = "SELECT *
             FROM Aerolineas
             WHERE activoAerolinea = 0
             ORDER BY fechaEliminacionAerolinea DESC";

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
        Nuvia - Administrador
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
                    Aerolíneas dadas de baja
                </h1>

                <p>
                    Consulta las aerolíneas que fueron dadas de baja del sistema.
                </p>

            </div>


            <a
                href="gestionAerolineas.php"
                class="btn btn-secondary"
            >

                <i class="bi bi-arrow-left"></i>

                Volver a aerolíneas

            </a>

        </section>



        <!-- TABLA -->

        <section class="tabla-contenedor">

            <table class="table align-middle">


                <thead>

                    <tr>

                        <th>Código</th>

                        <th>Aerolínea</th>

                        <th>IATA</th>

                        <th>País</th>

                        <th>Fecha de baja</th>

                        <th>Acciones</th>

                    </tr>

                </thead>



                <tbody>

                    <?php while ($aerolinea = $resultado->fetch_assoc()) { ?>

                        <tr>

                            <td>
                                <?php echo $aerolinea["codAerolinea"]; ?>
                            </td>

                            <td>
                                <?php echo htmlspecialchars($aerolinea["nombreAerolinea"]); ?>
                            </td>

                            <td>
                                <?php echo htmlspecialchars($aerolinea["codigoIATA"]); ?>
                            </td>

                            <td>
                                <?php echo htmlspecialchars($aerolinea["codPais"]); ?>
                            </td>

                            <td>
                                <?php
                                echo date(
                                    "d/m/Y H:i",
                                    strtotime($aerolinea["fechaEliminacion"])
                                );
                                ?>
                            </td>

                            <td> 
                                <a
                                    href="verAerolinea.php?id=<?php echo $aerolinea["codAerolinea"]; ?>&origen=inactivas"
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

