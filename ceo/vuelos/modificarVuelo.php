<?php

require_once __DIR__ . '/../../includes/verificarRol.php';
verificarRol('ceo');

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

// OBTENER AEROPUERTOS PARA LOS DESPLEGABLES

$consultaAeropuertos = obtenerAeropuertos($conexion);
$consultaAeropuertosDestino = obtenerAeropuertos($conexion);

if (!$consultaAeropuertos || !$consultaAeropuertosDestino) {
    die("Error al obtener los aeropuertos.");
}


?>


<!DOCTYPE html>
<html lang="es">

<head>

    <meta charset="UTF-8">

    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <title>
        Nuvia - Modificar vuelo
    </title>


    <link rel="icon" href="<?= htmlspecialchars(urlApp('imagenes/logo.png'), ENT_QUOTES, 'UTF-8') ?>" type="image/png">

    <link rel="stylesheet" href="<?= htmlspecialchars(urlApp('css/bootstrap.min.css'), ENT_QUOTES, 'UTF-8') ?>">
    <link rel="stylesheet" href="<?= htmlspecialchars(urlApp('css/bootstrap-icons.css'), ENT_QUOTES, 'UTF-8') ?>">
    <link rel="stylesheet" href="<?= htmlspecialchars(urlApp('css/estilosAdmin.css'), ENT_QUOTES, 'UTF-8') ?>">
    <link rel="stylesheet" href="<?= htmlspecialchars(urlApp('css/navbar.css'), ENT_QUOTES, 'UTF-8') ?>">
    <link rel="stylesheet" href="<?= htmlspecialchars(urlApp('css/footer.css'), ENT_QUOTES, 'UTF-8') ?>">

</head>


<body>

    <?php include __DIR__ . "/../includes/navbarCeo.php"; ?>

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

                <!-- ORIGEN Y DESTINO -->

                <div class="perfil-campos">

                    <!-- ORIGEN -->

                    <div>
                        <label for="origen" class="form-label">
                            Origen
                        </label>

                        <div class="campo-icono">

                            <i class="bi bi-geo-alt"></i>

                            <select
                                class="form-select"
                                id="origen"
                                name="origen"
                                style="padding-left: 50px;"
                                required
                            >

                                <option value="">
                                    Seleccione el aeropuerto de origen
                                </option>

                                <?php

                                $origenSeleccionado =
                                    $_POST["origen"] ?? $vuelo["origenVuelo"];

                                while (
                                    $aeropuerto = $consultaAeropuertos->fetch_assoc()
                                ) {

                                    $valorAeropuerto =
                                        $aeropuerto["nombreCiudad"]
                                        . " - "
                                        . $aeropuerto["codigoIATA"]
                                        . " - "
                                        . $aeropuerto["nombreAeropuerto"];

                                ?>

                                    <option
                                        value="<?= htmlspecialchars($valorAeropuerto, ENT_QUOTES, 'UTF-8') ?>"
                                        <?= $origenSeleccionado == $valorAeropuerto ? "selected" : "" ?>
                                    >

                                        <?= htmlspecialchars(
                                            $aeropuerto["nombreCiudad"]
                                            . " ("
                                            . $aeropuerto["codigoIATA"]
                                            . ") - "
                                            . $aeropuerto["nombrePais"]
                                        ) ?>

                                    </option>

                                <?php } ?>

                            </select>

                        </div>
                    </div>


                    <!-- DESTINO -->

                    <div>
                        <label for="destino" class="form-label">
                            Destino
                        </label>

                        <div class="campo-icono">

                            <i class="bi bi-geo-alt-fill"></i>

                            <select
                                class="form-select"
                                id="destino"
                                name="destino"
                                style="padding-left: 50px;"
                                required
                            >

                                <option value="">
                                    Seleccione el aeropuerto de destino
                                </option>

                                <?php

                                $destinoSeleccionado =
                                    $_POST["destino"] ?? $vuelo["destinoVuelo"];

                                while (
                                    $aeropuerto = $consultaAeropuertosDestino->fetch_assoc()
                                ) {

                                    $valorAeropuerto =
                                        $aeropuerto["nombreCiudad"]
                                        . " - "
                                        . $aeropuerto["codigoIATA"]
                                        . " - "
                                        . $aeropuerto["nombreAeropuerto"];

                                ?>

                                    <option
                                        value="<?= htmlspecialchars($valorAeropuerto, ENT_QUOTES, 'UTF-8') ?>"
                                        <?= $destinoSeleccionado == $valorAeropuerto ? "selected" : "" ?>
                                    >

                                        <?= htmlspecialchars(
                                            $aeropuerto["nombreCiudad"]
                                            . " ("
                                            . $aeropuerto["codigoIATA"]
                                            . ") - "
                                            . $aeropuerto["nombrePais"]
                                        ) ?>

                                    </option>

                                <?php } ?>

                            </select>

                        </div>
                    </div>

                </div>


                <!-- FECHA Y HORA -->

                <div class="perfil-campos" style="margin-top: 25px;">

                    <!-- FECHA -->

                    <div>
                        <label for="fecha" class="form-label">
                            Fecha de salida
                        </label>

                        <div class="campo-icono">

                            <i class="bi bi-calendar"></i>

                            <input
                                type="date"
                                class="form-control"
                                id="fecha"
                                name="fecha"
                                value="<?= htmlspecialchars(
                                    $_POST["fecha"] ?? $vuelo["fechaSalidaVuelo"]
                                ) ?>"
                                required
                            >

                        </div>
                    </div>


                    <!-- HORA -->

                    <div>
                        <label for="hora" class="form-label">
                            Hora de salida
                        </label>

                        <div class="campo-icono">

                            <i class="bi bi-clock"></i>

                            <input
                                type="time"
                                class="form-control"
                                id="hora"
                                name="hora"
                                value="<?= htmlspecialchars(
                                    $_POST["hora"] ?? $vuelo["horaSalidaVuelo"]
                                ) ?>"
                                required
                            >

                        </div>
                    </div>

                </div>


                <!-- PRECIO Y ASIENTOS -->

                <div class="perfil-campos" style="margin-top: 25px;">

                    <!-- PRECIO -->

                    <div>
                        <label for="precio" class="form-label">
                            Precio
                        </label>

                        <div class="campo-icono">

                            <i class="bi bi-currency-dollar"></i>

                            <input
                                type="number"
                                class="form-control"
                                id="precio"
                                name="precio"
                                min="1"
                                step="1"
                                value="<?= htmlspecialchars(
                                    $_POST["precio"] ?? $vuelo["precioVuelo"]
                                ) ?>"
                                required
                            >

                        </div>
                    </div>


                    <!-- ASIENTOS -->

                    <div>
                        <label for="asientos" class="form-label">
                            Asientos disponibles
                        </label>

                        <div class="campo-icono">

                            <i class="bi bi-person"></i>

                            <input
                                type="number"
                                class="form-control"
                                id="asientos"
                                name="asientos"
                                min="1"
                                step="1"
                                value="<?= htmlspecialchars(
                                    $_POST["asientos"] ?? $vuelo["asientosDisponibles"]
                                ) ?>"
                                required
                            >

                        </div>
                    </div>

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

    <?php include __DIR__ . "/../../includes/footer.php"; ?>

    <script src="<?= htmlspecialchars(urlApp('js/bootstrap.bundle.min.js'), ENT_QUOTES, 'UTF-8') ?>"></script>

</body>

</html>


<?php

$conexion->close();

?>