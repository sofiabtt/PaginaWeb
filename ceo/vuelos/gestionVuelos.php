<?php

session_start();

ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);


// VERIFICAR QUE SEA CEO

if (!isset($_SESSION["tipoUsuario"]) || $_SESSION["tipoUsuario"] != "ceo") {

    header("Location: ../../inicioSesion.php");
    exit();

}


// VERIFICAR QUE TENGA UNA AEROLÍNEA ASIGNADA

if (!isset($_SESSION["codAerolinea"])) {

    echo "El CEO no tiene una aerolínea asignada.";
    exit();

}


$codAerolinea = $_SESSION["codAerolinea"];


// CONEXIÓN

include "../../php/conexionBD.php";


// OBTENER VUELOS DE LA AEROLÍNEA

$consulta = $conexion->prepare(
    "SELECT *
     FROM Vuelos
     WHERE codAerolinea = ?
     ORDER BY fechaSalidaVuelo ASC, horaSalidaVuelo ASC"
);

$consulta->bind_param("i", $codAerolinea);

$consulta->execute();

$resultado = $consulta->get_result();

?>


<!DOCTYPE html>
<html lang="es">

<head>

    <meta charset="UTF-8">

    <meta
        name="viewport"
        content="width=device-width, initial-scale=1.0"
    >

    <title>Nuvia - CEO</title>


    <link
        rel="icon"
        href="../../imagenes/logo.png"
        type="image/png"
    >


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

</head>


<body>


    <!-- NAVBAR -->

    <?php include "../includes/navbarCeo.php"; ?>


    <main class="contenido-admin">


        <!-- ENCABEZADO -->

        <section class="bienvenida-admin">

            <div class="encabezado-contenido">

                <div>

                    <h1>
                        Gestión de vuelos
                    </h1>

                    <p>
                        Administra los vuelos de tu aerolínea.
                    </p>

                </div>


                <a
                    href="crearVuelo.php"
                    class="btn btn-primary"
                >

                    <i class="bi bi-plus-lg"></i>

                    Crear vuelo

                </a>

            </div>

        </section>



        <!-- TABLA -->

        <section class="actividad-admin">

            <div class="tabla-contenedor">

                <div class="table-responsive">

                    <table class="table align-middle">

                        <thead>

                            <tr>

                                <th>
                                    Código
                                </th>

                                <th>
                                    Origen
                                </th>

                                <th>
                                    Destino
                                </th>

                                <th>
                                    Fecha
                                </th>

                                <th>
                                    Hora
                                </th>

                                <th>
                                    Precio
                                </th>

                                <th>
                                    Asientos disponibles
                                </th>

                                <th>
                                    Acciones
                                </th>

                            </tr>

                        </thead>


                        <tbody>


                            <?php if ($resultado->num_rows > 0) { ?>


                                <?php while ($vuelo = $resultado->fetch_assoc()) { ?>


                                    <tr>


                                        <!-- CÓDIGO -->

                                        <td>

                                            <?php
                                            echo $vuelo["codVuelo"];
                                            ?>

                                        </td>


                                        <!-- ORIGEN -->

                                        <td>

                                            <?php
                                            echo htmlspecialchars(
                                                $vuelo["origenVuelo"]
                                            );
                                            ?>

                                        </td>


                                        <!-- DESTINO -->

                                        <td>

                                            <?php
                                            echo htmlspecialchars(
                                                $vuelo["destinoVuelo"]
                                            );
                                            ?>

                                        </td>


                                        <!-- FECHA -->

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


                                        <!-- HORA -->

                                        <td>

                                            <?php
                                            echo htmlspecialchars(
                                                $vuelo["horaSalidaVuelo"]
                                            );
                                            ?>

                                        </td>


                                        <!-- PRECIO -->

                                        <td>

                                            $
                                            <?php
                                            echo number_format(
                                                $vuelo["precioVuelo"],
                                                0,
                                                ",",
                                                "."
                                            );
                                            ?>

                                        </td>


                                        <!-- ASIENTOS -->

                                        <td>

                                            <?php
                                            echo $vuelo["asientosDisponibles"];
                                            ?>

                                        </td>


                                        <!-- ACCIONES -->

                                        <td>

                                            <a
                                                href="#"
                                                class="btn btn-sm btn-outline-primary"
                                                title="Editar vuelo"
                                            >

                                                <i class="bi bi-pencil"></i>

                                            </a>


                                            <a
                                                href="#"
                                                class="btn btn-sm btn-outline-danger"
                                                title="Eliminar vuelo"
                                            >

                                                <i class="bi bi-trash"></i>

                                            </a>

                                        </td>


                                    </tr>


                                <?php } ?>


                            <?php } else { ?>


                                <tr>

                                    <td
                                        colspan="8"
                                        class="text-center py-4"
                                    >

                                        <i
                                            class="bi bi-airplane"
                                            style="font-size: 30px;"
                                        ></i>

                                        <p class="mt-2 mb-0">

                                            No hay vuelos registrados
                                            para tu aerolínea.

                                        </p>

                                    </td>

                                </tr>


                            <?php } ?>


                        </tbody>

                    </table>

                </div>

            </div>


        </section>


    </main>


    <script
        src="../../js/bootstrap.bundle.min.js">
    </script>


</body>

</html>


<?php

$consulta->close();

$conexion->close();

?>

