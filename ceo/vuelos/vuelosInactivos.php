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


    // CONEXIÓN

    include "../../php/conexionBD.php";


    if (!isset($_SESSION["codUsuario"])) {

        echo "No se pudo identificar al CEO.";
        exit();

    }


    $codUsuario = $_SESSION["codUsuario"];


    // OBTENER LA AEROLÍNEA DEL CEO

    $consultaAerolinea = $conexion->prepare("
        SELECT codAerolinea
        FROM Aerolineas
        WHERE codUsuario = ?
    ");

    $consultaAerolinea->bind_param("i", $codUsuario);

    $consultaAerolinea->execute();

    $resultadoAerolinea = $consultaAerolinea->get_result();


    if ($resultadoAerolinea->num_rows != 1) {

        echo "El CEO no tiene una aerolínea asignada.";
        exit();

    }


    $aerolinea = $resultadoAerolinea->fetch_assoc();

    $codAerolinea = $aerolinea["codAerolinea"];

    $consultaAerolinea->close();



    // OBTENER LOS VUELOS DADOS DE BAJA

    $consulta = $conexion->prepare("
        SELECT *
        FROM Vuelos
        WHERE activoVuelo = 0
          AND codAerolinea = ?
        ORDER BY fechaEliminacion DESC
    ");

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

    <title>
        Nuvia - CEO
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

    <?php include "../includes/navbarCeo.php"; ?>


    <main class="contenido-admin">


        <!-- ENCABEZADO -->

        <section class="encabezado-contenido">

            <div>

                <h1>
                    Vuelos dados de baja
                </h1>

                <p>
                    Consulta los vuelos que fueron dados de baja de tu aerolínea.
                </p>

            </div>


            <a
                href="gestionVuelos.php"
                class="btn btn-secondary"
            >

                <i class="bi bi-arrow-left"></i>

                Volver a vuelos

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
                            Origen
                        </th>

                        <th>
                            Destino
                        </th>

                        <th>
                            Fecha de salida
                        </th>

                        <th>
                            Hora
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

                    <?php if ($resultado->num_rows > 0) { ?>


                        <?php while ($vuelo = $resultado->fetch_assoc()) { ?>

                            <tr>

                                <td>

                                    <?php
                                    echo $vuelo["codVuelo"];
                                    ?>

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


                                <td>

                                    <?php
                                    echo date(
                                        "d/m/Y H:i",
                                        strtotime(
                                            $vuelo["fechaEliminacion"]
                                        )
                                    );
                                    ?>

                                </td>


                                <td>

                                    <a
                                        href="verVuelo.php?id=<?php echo $vuelo["codVuelo"]; ?>&origen=inactivas"
                                        class="btn btn-sm btn-outline-secondary"
                                    >

                                        <i class="bi bi-eye"></i>

                                        Ver

                                    </a>

                                </td>

                            </tr>

                        <?php } ?>


                    <?php } else { ?>

                        <tr>

                            <td
                                colspan="7"
                                class="text-center py-4"
                            >

                                No hay vuelos dados de baja.

                            </td>

                        </tr>

                    <?php } ?>

                </tbody>


            </table>

        </section>


    </main>


</body>

</html>


<?php

    $consulta->close();

    $conexion->close();

?>