<?php

session_start();

include "../../php/consultasCeos.php";
include "../../php/consultasAerolineas.php";
include "../../php/consultasVuelos.php";


verificarCeo();
$codUsuario = obtenerCodCeo();

$aerolinea = obtenerAerolineaPorCeo($conexion,$codUsuario);

if (!$aerolinea) {

    echo "El CEO no tiene una aerolínea asignada.";
    exit();

}

$codAerolinea =$aerolinea["codAerolinea"];

$codVuelo = obtenerCodVuelo();

$vuelo = obtenerVuelo($conexion,$codVuelo,$codAerolinea);


if (!$vuelo || $vuelo["activoVuelo"] != 1) {

    echo "El vuelo no existe o no pertenece a tu aerolínea.";
    exit();

}


// PROCESAR MODIFICACIÓN

if ($_SERVER["REQUEST_METHOD"] == "POST") {

    $origen =
        trim($_POST["origen"]);

    $destino =
        trim($_POST["destino"]);

    $fecha =
        $_POST["fecha"];

    $hora =
        $_POST["hora"];

    $precio =
        $_POST["precio"];

    $asientos =
        $_POST["asientos"];


    // VALIDACIONES

    if (
        empty($origen) ||
        empty($destino) ||
        empty($fecha) ||
        empty($hora) ||
        empty($precio) ||
        empty($asientos)
    ) {

        $error = "Debe completar todos los campos.";

    } elseif ($origen == $destino) {

        $error = "El origen y el destino no pueden ser iguales.";

    } elseif (strtotime($fecha) < strtotime(date("Y-m-d"))) {

        $error = "La fecha de salida no puede ser anterior a la fecha actual.";

    } elseif ($precio <= 0) {

        $error = "El precio debe ser mayor a 0.";

    } elseif ($asientos <= 0) {

        $error = "La cantidad de asientos debe ser mayor a 0.";

    } else {

        if ( modificarVuelo(
                $conexion,
                $codVuelo,
                $codAerolinea,
                $origen,
                $destino,
                $fecha,
                $hora,
                $precio,
                $asientos
            )
        ) {

            header("Location: gestionVuelos.php?mensaje=modificado");
            exit();

        } else {

            $error ="No se pudo modificar el vuelo.";

        }

    }

}

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
        Nuvia - Modificar vuelo
    </title>


    <link
        rel="icon"
        href="../../imagenes/logo.png"
        type="image/png"
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

</head>


<body>


    <?php include "../includes/navbarCeo.php"; ?>


    <main class="contenido-admin">


        <section class="bienvenida-admin">

            <h1>
                Modificar vuelo
            </h1>

            <p>
                Edita los datos del vuelo.
            </p>

        </section>


        <section class="perfil-card">


            <?php if (isset($error)) { ?>

                <div class="alert alert-danger">

                    <?php
                    echo htmlspecialchars($error);
                    ?>

                </div>

            <?php } ?>


            <form method="POST">


                <!-- ORIGEN -->

                <div class="mb-4">

                    <label
                        for="origen"
                        class="form-label"
                    >
                        Origen
                    </label>


                    <input
                        type="text"
                        class="form-control"
                        id="origen"
                        name="origen"
                        value="<?php

                            echo isset($_POST["origen"])
                                ? htmlspecialchars(
                                    $_POST["origen"]
                                )
                                : htmlspecialchars(
                                    $vuelo["origenVuelo"]
                                );

                        ?>"
                        required
                    >

                </div>


                <!-- DESTINO -->

                <div class="mb-4">

                    <label
                        for="destino"
                        class="form-label"
                    >
                        Destino
                    </label>


                    <input
                        type="text"
                        class="form-control"
                        id="destino"
                        name="destino"
                        value="<?php

                            echo isset($_POST["destino"])
                                ? htmlspecialchars(
                                    $_POST["destino"]
                                )
                                : htmlspecialchars(
                                    $vuelo["destinoVuelo"]
                                );

                        ?>"
                        required
                    >

                </div>


                <!-- FECHA -->

                <div class="mb-4">

                    <label
                        for="fecha"
                        class="form-label"
                    >
                        Fecha de salida
                    </label>


                    <input
                        type="date"
                        class="form-control"
                        id="fecha"
                        name="fecha"
                        value="<?php

                            echo isset($_POST["fecha"])
                                ? htmlspecialchars(
                                    $_POST["fecha"]
                                )
                                : htmlspecialchars(
                                    $vuelo["fechaSalidaVuelo"]
                                );

                        ?>"
                        required
                    >

                </div>


                <!-- HORA -->

                <div class="mb-4">

                    <label
                        for="hora"
                        class="form-label"
                    >
                        Hora de salida
                    </label>


                    <input
                        type="time"
                        class="form-control"
                        id="hora"
                        name="hora"
                        value="<?php

                            echo isset($_POST["hora"])
                                ? htmlspecialchars(
                                    $_POST["hora"]
                                )
                                : htmlspecialchars(
                                    $vuelo["horaSalidaVuelo"]
                                );

                        ?>"
                        required
                    >

                </div>


                <!-- PRECIO -->

                <div class="mb-4">

                    <label
                        for="precio"
                        class="form-label"
                    >
                        Precio
                    </label>


                    <input
                        type="number"
                        class="form-control"
                        id="precio"
                        name="precio"
                        min="1"
                        step="1"
                        value="<?php

                            echo isset($_POST["precio"])
                                ? htmlspecialchars(
                                    $_POST["precio"]
                                )
                                : htmlspecialchars(
                                    $vuelo["precioVuelo"]
                                );

                        ?>"
                        required
                    >

                </div>


                <!-- ASIENTOS -->

                <div class="mb-4">

                    <label
                        for="asientos"
                        class="form-label"
                    >
                        Asientos disponibles
                    </label>


                    <input
                        type="number"
                        class="form-control"
                        id="asientos"
                        name="asientos"
                        min="1"
                        step="1"
                        value="<?php

                            echo isset($_POST["asientos"])
                                ? htmlspecialchars(
                                    $_POST["asientos"]
                                )
                                : htmlspecialchars(
                                    $vuelo["asientosDisponibles"]
                                );

                        ?>"
                        required
                    >

                </div>


                <!-- BOTONES -->

                <div class="perfil-acciones">


                    <a
                        href="gestionVuelos.php"
                        class="btn btn-outline-secondary me-2"
                    >
                        Cancelar
                    </a>


                    <button
                        type="submit"
                        class="btn btn-primary"
                    >

                        <i class="bi bi-check-lg"></i>

                        Guardar cambios

                    </button>


                </div>


            </form>


        </section>


    </main>


    <script
        src="../../js/bootstrap.bundle.min.js"
    ></script>


</body>

</html>


<?php

$conexion->close();

?>