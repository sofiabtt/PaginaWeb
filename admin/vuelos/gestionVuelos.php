<?php

session_start();

include "../../php/conexionBD.php";
include "../../php/consultasVuelos.php";


// VERIFICAR ADMINISTRADOR

if (
    !isset($_SESSION["codUsuario"]) ||
    !isset($_SESSION["tipoUsuario"]) ||
    $_SESSION["tipoUsuario"] !== "administrador"
) {

    header("Location: ../../inicioSesion.php");
    exit();
}


$resultado = obtenerTodosLosVuelos($conexion);

$cantidadVuelos = $resultado->num_rows;

?>

<!DOCTYPE html>

<html lang="es">

<head>

    <meta charset="UTF-8">

    <meta
        name="viewport"
        content="width=device-width, initial-scale=1.0"
    >

    <title>Nuvia - Vuelos</title>

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
                    Vuelos
                </h1>

                <p>
                    Consultá todos los vuelos registrados
                    en Nuvia.
                </p>

            </div>

        </section>


        <p>

            Cantidad de vuelos registrados:

            <strong>
                <?php echo $cantidadVuelos; ?>
            </strong>

        </p>


        <section class="tabla-contenedor">


            <table class="table align-middle">


                <thead>

                    <tr>

                        <th>Código</th>

                        <th>Aerolínea</th>

                        <th>Origen</th>

                        <th>Destino</th>

                        <th>Fecha</th>

                        <th>Hora</th>

                        <th>Precio</th>

                        <th>Asientos</th>

                        <th>Estado</th>

                    </tr>

                </thead>


                <tbody>


                    <?php if ($cantidadVuelos === 0) { ?>


                        <tr>

                            <td
                                colspan="9"
                                class="text-center text-muted py-4"
                            >

                                No hay vuelos registrados.

                            </td>

                        </tr>


                    <?php } ?>


                    <?php while ($vuelo = $resultado->fetch_assoc()) { ?>


                        <tr>


                            <td>

                                <?php
                                echo (int) $vuelo["codVuelo"];
                                ?>

                            </td>


                            <td>

                                <?php

                                echo htmlspecialchars(
                                    $vuelo["nombreAerolinea"]
                                );

                                ?>

                                <br>

                                <small class="text-muted">

                                    <?php

                                    echo htmlspecialchars(
                                        $vuelo["codigoIATA"]
                                    );

                                    ?>

                                </small>

                            </td>


                            <td>

                                <?php

                                echo htmlspecialchars(
                                    $vuelo["origenVuelo"]
                                );

                                ?>

                            </td>


                            <td>

                                <?php

                                echo htmlspecialchars(
                                    $vuelo["destinoVuelo"]
                                );

                                ?>

                            </td>


                            <td>

                                <?php

                                echo date(
                                    "d/m/Y",
                                    strtotime(
                                        $vuelo["fechaSalidaVuelo"]
                                    )
                                );

                                ?>

                            </td>


                            <td>

                                <?php

                                echo htmlspecialchars(
                                    $vuelo["horaSalidaVuelo"]
                                );

                                ?>

                            </td>


                            <td class="precio-vuelo">

                                <?php

                                echo "$ "
                                    . number_format(
                                        (float) $vuelo["precioVuelo"],
                                        0,
                                        ",",
                                        "."
                                    );

                                ?>

                            </td>


                            <td>

                                <?php

                                echo (int)
                                    $vuelo["asientosDisponibles"];

                                ?>

                            </td>


                            <td>


                                <?php if ((int) $vuelo["activoVuelo"] === 1) { ?>


                                    <span class="badge bg-success">

                                        Activo

                                    </span>


                                <?php } else { ?>


                                    <span class="badge bg-secondary">

                                        Dado de baja

                                    </span>


                                <?php } ?>


                            </td>


                        </tr>


                    <?php } ?>


                </tbody>


            </table>


        </section>


    </main>


    <script src="../../js/bootstrap.bundle.min.js"></script>


</body>

</html>