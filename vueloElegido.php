<?php

session_start();

include "php/conexionBD.php";
include "php/consultasVuelos.php";
include "php/procesarVueloElegido.php";

// GUARDAR DATOS DE LA BÚSQUEDA

if (isset($_GET["tipoViaje"])) {
    $_SESSION["tipoViajeReserva"] = $_GET["tipoViaje"];
}

if (isset($_GET["origen"])) {
    $_SESSION["origenReserva"] = $_GET["origen"];
}

if (isset($_GET["destino"])) {
    $_SESSION["destinoReserva"] = $_GET["destino"];
}

if (isset($_GET["fechaIda"])) {
    $_SESSION["fechaIdaReserva"] = $_GET["fechaIda"];
}

if (isset($_GET["fechaVuelta"])) {
    $_SESSION["fechaVueltaReserva"] = $_GET["fechaVuelta"];
}

if (isset($_GET["tramo"])) {
    $_SESSION["tramoReserva"] = $_GET["tramo"];
}


// RECUPERAR DATOS DEL VIAJE

$tipoViaje =
    $_GET["tipoViaje"]
    ?? $_POST["tipoViaje"]
    ?? $_SESSION["tipoViajeReserva"]
    ?? "soloIda";


$origen =
    $_GET["origen"]
    ?? $_POST["origen"]
    ?? $_SESSION["origenReserva"]
    ?? "";


$destino =
    $_GET["destino"]
    ?? $_POST["destino"]
    ?? $_SESSION["destinoReserva"]
    ?? "";


$fechaIda =
    $_GET["fechaIda"]
    ?? $_POST["fechaIda"]
    ?? $_SESSION["fechaIdaReserva"]
    ?? "";


$fechaVuelta =
    $_GET["fechaVuelta"]
    ?? $_POST["fechaVuelta"]
    ?? $_SESSION["fechaVueltaReserva"]
    ?? "";


$tramo =
    $_GET["tramo"]
    ?? $_POST["tramo"]
    ?? $_SESSION["tramoReserva"]
    ?? "ida";


$reservaIda =
    isset($_GET["reservaIda"])
    ? (int) $_GET["reservaIda"]
    : (int) ($_POST["reservaIda"] ?? 0);


// OBTENER VUELO

$codVuelo =
    isset($_GET["codVuelo"])
    ? (int) $_GET["codVuelo"]
    : (int) ($_POST["codVuelo"] ?? 0);


if ($codVuelo <= 0) {
    die("No se seleccionó ningún vuelo.");
}

$vuelo = obtenerVueloElegido($conexion,$codVuelo);

if (!$vuelo) {
    die("No se encontró el vuelo seleccionado.");
}

// VARIABLES DEL FORMULARIO

$pasoFormulario = 1;

$adultos = 1;
$menores = 0;

$error = "";

$reservaCreada = false;
$codReservaCreada = 0;


// RESTAURAR RESERVA TEMPORAL

if (
    isset($_SESSION["restaurarReservaTemporal"])
    && $_SESSION["restaurarReservaTemporal"] === true
    && isset($_SESSION["reservaTemporal"])
) {

    $reservaTemporal = $_SESSION["reservaTemporal"];


    $adultos = (int) ($reservaTemporal["adultos"] ?? 1);

    $menores = (int) ($reservaTemporal["menores"] ?? 0);

    $_POST["nombreAdulto"] =
        $reservaTemporal["nombreAdulto"] ?? [];

    $_POST["apellidoAdulto"] =
        $reservaTemporal["apellidoAdulto"] ?? [];

    $_POST["emailAdulto"] =
        $reservaTemporal["emailAdulto"] ?? [];

    $_POST["fechaAdulto"] =
        $reservaTemporal["fechaAdulto"] ?? [];


    $_POST["nombreMenor"] =
        $reservaTemporal["nombreMenor"] ?? [];

    $_POST["apellidoMenor"] =
        $reservaTemporal["apellidoMenor"] ?? [];

    $_POST["emailMenor"] =
        $reservaTemporal["emailMenor"] ?? [];

    $_POST["fechaMenor"] =
        $reservaTemporal["fechaMenor"] ?? [];


    $pasoFormulario = 3;

    unset($_SESSION["restaurarReservaTemporal"]);
}

// PROCESAR FORMULARIOS

if ($_SERVER["REQUEST_METHOD"] === "POST") {

    $accion = $_POST["accion"] ?? "";

    // PASO 1 - CANTIDAD

    if ($accion === "cantidad") {

        $adultos = (int) ($_POST["adultos"] ?? 0);

        $menores = (int) ($_POST["menores"] ?? 0);

        $totalPasajeros = $adultos + $menores;

        if ($adultos < 1) {

            $error = "Debe viajar al menos un adulto.";

            $pasoFormulario = 1;

        } elseif ($menores < 0) {

            $error = "La cantidad de menores no es válida.";

            $pasoFormulario = 1;

        } elseif ($totalPasajeros > 10) {

            $error = "La reserva puede tener como máximo 10 pasajeros.";

            $pasoFormulario = 1;

        } else {

            $pasoFormulario = 2;
        }
    }

    // PASO 2 - DATOS PASAJEROS

    if ($accion === "datos") {

        $adultos = (int) ($_POST["adultos"] ?? 0);

        $menores = (int) ($_POST["menores"] ?? 0);

        $nombresAdultos =
            $_POST["nombreAdulto"] ?? [];

        $apellidosAdultos =
            $_POST["apellidoAdulto"] ?? [];

        $emailsAdultos =
            $_POST["emailAdulto"] ?? [];

        $fechasAdultos =
            $_POST["fechaAdulto"] ?? [];


        $nombresMenores =
            $_POST["nombreMenor"] ?? [];

        $apellidosMenores =
            $_POST["apellidoMenor"] ?? [];

        $emailsMenores =
            $_POST["emailMenor"] ?? [];

        $fechasMenores =
            $_POST["fechaMenor"] ?? [];


        // VALIDAR ADULTOS

        $error = validarAdultos(
            $nombresAdultos,
            $apellidosAdultos,
            $emailsAdultos,
            $fechasAdultos,
            $adultos
        );


        // VALIDAR MENORES

        if ($error === "") {

            $error = validarMenores(
                $nombresMenores,
                $apellidosMenores,
                $emailsMenores,
                $fechasMenores,
                $menores
            );
        }


        if ($error === "") {

            $pasoFormulario = 3;

        } else {

            $pasoFormulario = 2;
        }
    }


    // PASO 3 - RESERVAR

    if ($accion === "reservar") {

        $adultos = (int) ($_POST["adultos"] ?? 0);

        $menores = (int) ($_POST["menores"] ?? 0);

        // GUARDAR DATOS TEMPORALES

        guardarReservaTemporal(
            $codVuelo,
            $adultos,
            $menores,
            $tipoViaje,
            $origen,
            $destino,
            $fechaIda,
            $fechaVuelta,
            $tramo,
            $reservaIda
        );


        // SI NO ESTÁ LOGUEADO

        if (
            !isset($_SESSION["codUsuario"])
            || !isset($_SESSION["tipoUsuario"])
            || $_SESSION["tipoUsuario"] !== "usuario"
        ) {

            $_SESSION["restaurarReservaTemporal"] = true;

            $pasoFormulario = 4;

        } else {

            $_SESSION["crearReservaDesdeVuelo"] = true;

            header("Location: usuario/reservas/crearReserva.php");

            exit();
        }
    }
}

// PRECIO

$totalPasajeros = $adultos + $menores;

$precioTotal = $totalPasajeros * (float) $vuelo["precioVuelo"];

?>

<!DOCTYPE html>

<html lang="es">

<head>

    <meta charset="UTF-8">

    <meta
        name="viewport"
        content="width=device-width, initial-scale=1.0"
    >

    <title>Nuvia - Vuelo elegido</title>


    <link
        rel="icon"
        type="image/png"
        href="/PaginaWeb/imagenes/logo.png"
    >


    <link
        rel="stylesheet"
        href="css/bootstrap.min.css"
    >

    <link
        rel="stylesheet"
        href="css/bootstrap-icons.css"
    >

    <link
        rel="stylesheet"
        href="css/estiloshome.css"
    >

    <link
        rel="stylesheet"
        href="css/estilos-usuario.css"
    >

    <link
        rel="stylesheet"
        href="css/estilosVueloElegido.css"
    >

    <link
        rel="stylesheet"
        href="css/navbar.css"
    >

    <link
        rel="stylesheet"
        href="css/progresoReserva.css?v=4"
    >

    <link
        rel="stylesheet"
        href="css/footer.css"
    >

</head>


<body>


<?php include "includes/navbar.php"; ?>


<main class="contenedor-vuelo-elegido">

    <div class="container">


        <!--PROGRESO SOLO IDA-->

        <?php if ($tipoViaje === "soloIda") { ?>

            <div class="progreso-reserva mb-5">

                <div class="progreso-titulo">

                    <h2 class="meta-valor">
                        Obtener vuelo
                    </h2>

                </div>


                <div class="timeline-pasos">

                    <div
                        class="timeline-paso completado"
                    >

                        <span class="timeline-punto"></span>

                        <div class="timeline-contenido">

                            <span class="timeline-etiqueta">
                                Paso 1
                            </span>

                            <div class="timeline-nombre">
                                Elegir vuelo de ida
                            </div>

                            <p class="timeline-detalle">
                                Vuelo seleccionado.
                            </p>

                        </div>

                    </div>


                    <div
                        class="timeline-paso
                        <?php
                        echo $reservaCreada
                            ? "completado"
                            : "activo";
                        ?>"
                    >

                        <span class="timeline-punto"></span>

                        <div class="timeline-contenido">

                            <span class="timeline-etiqueta">
                                Paso 2
                            </span>

                            <div class="timeline-nombre">
                                Completar la reserva
                            </div>

                            <p class="timeline-detalle">
                                Ingresá los datos de los pasajeros
                                y confirmá la reserva.
                            </p>

                        </div>

                    </div>


                    <div
                        class="timeline-paso
                        <?php
                        echo $reservaCreada
                            ? "activo"
                            : "";
                        ?>"
                    >

                        <span class="timeline-punto"></span>

                        <div class="timeline-contenido">

                            <span class="timeline-etiqueta">
                                Paso 3
                            </span>

                            <div class="timeline-nombre">
                                Finalizar en Mis Reservas
                            </div>

                            <p class="timeline-detalle">
                                Después de reservar,
                                seguí a Mis Reservas para terminar.
                            </p>

                        </div>

                    </div>

                </div>

            </div>


        <!-- PROGRESO IDA Y VUELTA-->

        <?php } elseif ($tipoViaje === "idaVuelta") { ?>

            <div
                class="progreso-reserva
                       progreso-ida-vuelta
                       mb-5"
            >

                <div class="progreso-titulo">

                    <h2 class="meta-valor">
                        Obtener vuelo
                    </h2>

                </div>


                <div class="timeline-pasos cinco-pasos">


                    <!-- PASO 1 -->

                    <div class="timeline-paso completado">

                        <span class="timeline-punto"></span>

                        <div class="timeline-contenido">

                            <span class="timeline-etiqueta">
                                Paso 1
                            </span>

                            <div class="timeline-nombre">
                                Elegir vuelo de ida
                            </div>

                            <p class="timeline-detalle">
                                Vuelo de ida seleccionado.
                            </p>

                        </div>

                    </div>


                    <!-- PASO 2 -->

                    <div
                        class="timeline-paso
                        <?php
                        echo $tramo === "ida"
                            ? "activo"
                            : "completado";
                        ?>"
                    >

                        <span class="timeline-punto"></span>

                        <div class="timeline-contenido">

                            <span class="timeline-etiqueta">
                                Paso 2
                            </span>

                            <div class="timeline-nombre">
                                Completar reserva de ida
                            </div>

                            <p class="timeline-detalle">
                                Cargá los pasajeros y reservá la ida.
                            </p>

                        </div>

                    </div>


                    <!-- PASO 3 -->

                    <div
                        class="timeline-paso
                        <?php
                        echo $tramo === "vuelta"
                            ? "completado"
                            : "";
                        ?>"
                    >

                        <span class="timeline-punto"></span>

                        <div class="timeline-contenido">

                            <span class="timeline-etiqueta">
                                Paso 3
                            </span>

                            <div class="timeline-nombre">
                                Elegir vuelo de vuelta
                            </div>

                            <p class="timeline-detalle">
                                Seleccioná el vuelo de regreso.
                            </p>

                        </div>

                    </div>


                    <!-- PASO 4 -->

                    <div
                        class="timeline-paso
                        <?php
                        echo $tramo === "vuelta"
                            ? "activo"
                            : "";
                        ?>"
                    >

                        <span class="timeline-punto"></span>

                        <div class="timeline-contenido">

                            <span class="timeline-etiqueta">
                                Paso 4
                            </span>

                            <div class="timeline-nombre">
                                Completar reserva de vuelta
                            </div>

                            <p class="timeline-detalle">
                                Cargá los pasajeros y reservá la vuelta.
                            </p>

                        </div>

                    </div>


                    <!-- PASO 5 -->

                    <div class="timeline-paso">

                        <span class="timeline-punto"></span>

                        <div class="timeline-contenido">

                            <span class="timeline-etiqueta">
                                Paso 5
                            </span>

                            <div class="timeline-nombre">
                                Finalizar en Mis Reservas
                            </div>

                            <p class="timeline-detalle">
                                Confirmá las reservas
                                para completar el viaje.
                            </p>

                        </div>

                    </div>

                </div>

            </div>

        <?php } ?>


        <!-- VUELO ELEGIDO-->

        <h1 class="titulo-vuelo-elegido">
            Confirmá tu vuelo
        </h1>


        <div class="card tarjeta-vuelo-elegido">

            <div class="card-body">

                <div class="row align-items-center">


                    <div class="col-md-8">


                        <div class="aerolinea-vuelo mb-3">

                            <i class="bi bi-airplane"></i>

                            <?php
                            echo htmlspecialchars(
                                $vuelo["nombreAerolinea"]
                            );
                            ?>

                        </div>


                        <div class="row align-items-center">


                            <div class="col-md-5">

                                <span class="texto-secundario">
                                    Origen
                                </span>

                                <h4>
                                    <?php
                                    echo htmlspecialchars(
                                        $vuelo["origenVuelo"]
                                    );
                                    ?>
                                </h4>

                            </div>


                            <div class="col-md-2 text-center">

                                <i
                                    class="bi bi-arrow-right
                                           flecha-vuelo"
                                ></i>

                            </div>


                            <div class="col-md-5">

                                <span class="texto-secundario">
                                    Destino
                                </span>

                                <h4>
                                    <?php
                                    echo htmlspecialchars(
                                        $vuelo["destinoVuelo"]
                                    );
                                    ?>
                                </h4>

                            </div>

                        </div>


                        <div class="datos-secundarios mt-4">

                            <span>

                                <i class="bi bi-calendar3"></i>

                                <?php
                                echo htmlspecialchars(
                                    $vuelo["fechaSalidaVuelo"]
                                );
                                ?>

                            </span>


                            <span>

                                <i class="bi bi-clock"></i>

                                <?php
                                echo htmlspecialchars(
                                    $vuelo["horaSalidaVuelo"]
                                );
                                ?>

                            </span>


                            <span>

                                <i class="bi bi-person"></i>

                                <?php
                                echo htmlspecialchars(
                                    $vuelo["asientosDisponibles"]
                                );
                                ?>

                                asientos disponibles

                            </span>

                        </div>

                    </div>


                    <div class="col-md-4 text-end">

                        <span class="texto-secundario">
                            Precio por pasajero
                        </span>

                        <h2 class="precio-vuelo-elegido">

                            $

                            <?php
                            echo number_format(
                                $vuelo["precioVuelo"],
                                0,
                                ",",
                                "."
                            );
                            ?>

                        </h2>

                    </div>

                </div>

            </div>

        </div>


        <!-- FORMULARIO-->

        <div class="contenedor-reserva">


            <!-- PASO 1-->

            <?php if ($pasoFormulario === 1) { ?>

                <div class="tarjeta-reserva">

                    <h2>
                        Pasajeros
                    </h2>


                    <?php if ($error !== "") { ?>

                        <p class="mensaje-error">
                            <?php echo htmlspecialchars($error); ?>
                        </p>

                    <?php } ?>


                    <form method="POST" action="#datos-pasajeros">


                        <input
                            type="hidden"
                            name="accion"
                            value="cantidad"
                        >

                        <input
                            type="hidden"
                            name="codVuelo"
                            value="<?php echo $codVuelo; ?>"
                        >

                        <input
                            type="hidden"
                            name="tipoViaje"
                            value="<?php echo htmlspecialchars($tipoViaje); ?>"
                        >

                        <input
                            type="hidden"
                            name="origen"
                            value="<?php echo htmlspecialchars($origen); ?>"
                        >

                        <input
                            type="hidden"
                            name="destino"
                            value="<?php echo htmlspecialchars($destino); ?>"
                        >

                        <input
                            type="hidden"
                            name="fechaIda"
                            value="<?php echo htmlspecialchars($fechaIda); ?>"
                        >

                        <input
                            type="hidden"
                            name="fechaVuelta"
                            value="<?php echo htmlspecialchars($fechaVuelta); ?>"
                        >

                        <input
                            type="hidden"
                            name="tramo"
                            value="<?php echo htmlspecialchars($tramo); ?>"
                        >

                        <input
                            type="hidden"
                            name="reservaIda"
                            value="<?php echo $reservaIda; ?>"
                        >


                        <div class="fila-pasajeros">


                            <div class="campo-pasajero">

                                <label for="adultos">
                                    Adultos
                                </label>

                                <input
                                    type="number"
                                    id="adultos"
                                    name="adultos"
                                    min="1"
                                    max="10"
                                    value="<?php echo $adultos; ?>"
                                    required
                                >

                                <small>
                                    Mayores de 12 años
                                </small>

                            </div>


                            <div class="campo-pasajero">

                                <label for="menores">
                                    Menores
                                </label>

                                <input
                                    type="number"
                                    id="menores"
                                    name="menores"
                                    min="0"
                                    max="9"
                                    value="<?php echo $menores; ?>"
                                    required
                                >

                                <small>
                                    Menores de 12 años
                                </small>

                            </div>

                        </div>


                        <button
                            type="submit"
                            class="boton-principal"
                        >
                            Siguiente
                        </button>

                    </form>

                </div>

            <?php } ?>


            <!-- PASO 2-->

            <?php if ($pasoFormulario === 2) { ?>

                <div class="tarjeta-reserva" id="datos-pasajeros">

                    <h2>
                        Datos de los pasajeros
                    </h2>


                    <?php if ($error !== "") { ?>

                        <p class="mensaje-error">
                            <?php echo htmlspecialchars($error); ?>
                        </p>

                    <?php } ?>


                    <form method="POST" action="#resumen-reserva">


                        <input
                            type="hidden"
                            name="accion"
                            value="datos"
                        >

                        <input
                            type="hidden"
                            name="codVuelo"
                            value="<?php echo $codVuelo; ?>"
                        >

                        <input
                            type="hidden"
                            name="adultos"
                            value="<?php echo $adultos; ?>"
                        >

                        <input
                            type="hidden"
                            name="menores"
                            value="<?php echo $menores; ?>"
                        >

                        <input
                            type="hidden"
                            name="tipoViaje"
                            value="<?php echo htmlspecialchars($tipoViaje); ?>"
                        >

                        <input
                            type="hidden"
                            name="origen"
                            value="<?php echo htmlspecialchars($origen); ?>"
                        >

                        <input
                            type="hidden"
                            name="destino"
                            value="<?php echo htmlspecialchars($destino); ?>"
                        >

                        <input
                            type="hidden"
                            name="fechaIda"
                            value="<?php echo htmlspecialchars($fechaIda); ?>"
                        >

                        <input
                            type="hidden"
                            name="fechaVuelta"
                            value="<?php echo htmlspecialchars($fechaVuelta); ?>"
                        >

                        <input
                            type="hidden"
                            name="tramo"
                            value="<?php echo htmlspecialchars($tramo); ?>"
                        >

                        <input
                            type="hidden"
                            name="reservaIda"
                            value="<?php echo $reservaIda; ?>"
                        >


                        <!-- ADULTOS -->

                        <?php
                        for ($i = 0; $i < $adultos; $i++) {
                        ?>

                            <div class="pasajero-card">

                                <h3>
                                    Adulto <?php echo $i + 1; ?>
                                </h3>


                                <div class="grid-datos">


                                    <div>

                                        <label>
                                            Nombre
                                        </label>

                                        <input
                                            type="text"
                                            name="nombreAdulto[]"
                                            value="<?php
                                                echo valorArray(
                                                    $_POST["nombreAdulto"] ?? [],
                                                    $i
                                                );
                                            ?>"
                                            required
                                        >

                                    </div>


                                    <div>

                                        <label>
                                            Apellido
                                        </label>

                                        <input
                                            type="text"
                                            name="apellidoAdulto[]"
                                            value="<?php
                                                echo valorArray(
                                                    $_POST["apellidoAdulto"] ?? [],
                                                    $i
                                                );
                                            ?>"
                                            required
                                        >

                                    </div>


                                    <div>

                                        <label>
                                            Email
                                        </label>

                                        <input
                                            type="email"
                                            name="emailAdulto[]"
                                            value="<?php
                                                echo valorArray(
                                                    $_POST["emailAdulto"] ?? [],
                                                    $i
                                                );
                                            ?>"
                                            required
                                        >

                                    </div>


                                    <div>

                                        <label>
                                            Fecha de nacimiento
                                        </label>

                                        <input
                                            type="date"
                                            name="fechaAdulto[]"
                                            max="<?php echo date("Y-m-d"); ?>"
                                            value="<?php
                                                echo valorArray(
                                                    $_POST["fechaAdulto"] ?? [],
                                                    $i
                                                );
                                            ?>"
                                            required
                                        >

                                    </div>


                                </div>

                            </div>

                        <?php } ?>


                        <!-- MENORES -->

                        <?php
                        for ($i = 0; $i < $menores; $i++) {
                        ?>

                            <div class="pasajero-card">

                                <h3>
                                    Menor <?php echo $i + 1; ?>
                                </h3>


                                <div class="grid-datos">


                                    <div>

                                        <label>
                                            Nombre
                                        </label>

                                        <input
                                            type="text"
                                            name="nombreMenor[]"
                                            value="<?php
                                                echo valorArray(
                                                    $_POST["nombreMenor"] ?? [],
                                                    $i
                                                );
                                            ?>"
                                            required
                                        >

                                    </div>


                                    <div>

                                        <label>
                                            Apellido
                                        </label>

                                        <input
                                            type="text"
                                            name="apellidoMenor[]"
                                            value="<?php
                                                echo valorArray(
                                                    $_POST["apellidoMenor"] ?? [],
                                                    $i
                                                );
                                            ?>"
                                            required
                                        >

                                    </div>


                                    <div>

                                        <label>
                                            Email
                                        </label>

                                        <input
                                            type="email"
                                            name="emailMenor[]"
                                            value="<?php
                                                echo valorArray(
                                                    $_POST["emailMenor"] ?? [],
                                                    $i
                                                );
                                            ?>"
                                            required
                                        >

                                    </div>


                                    <div>

                                        <label>
                                            Fecha de nacimiento
                                        </label>

                                        <input
                                            type="date"
                                            name="fechaMenor[]"
                                            max="<?php echo date("Y-m-d"); ?>"
                                            value="<?php
                                                echo valorArray(
                                                    $_POST["fechaMenor"] ?? [],
                                                    $i
                                                );
                                            ?>"
                                            required
                                        >

                                    </div>


                                </div>

                            </div>

                        <?php } ?>


                        <button
                            type="submit"
                            class="boton-principal"
                        >
                            Continuar al pago
                        </button>

                    </form>

                </div>

            <?php } ?>


            <!-- PASO 3 - RESUMEN-->

            <?php if ($pasoFormulario === 3) { ?>

                <div class="tarjeta-reserva" id="resumen-reserva">

                    <h2>
                        Datos de la reserva
                    </h2>


                    <div class="detalle-precio">

                        <span>
                            Precio por pasajero
                        </span>

                        <strong>
                            $
                            <?php
                            echo number_format(
                                $vuelo["precioVuelo"],
                                0,
                                ",",
                                "."
                            );
                            ?>
                        </strong>

                    </div>


                    <div class="detalle-precio">

                        <span>
                            Adultos
                        </span>

                        <strong>
                            <?php echo $adultos; ?>
                        </strong>

                    </div>


                    <div class="detalle-precio">

                        <span>
                            Menores
                        </span>

                        <strong>
                            <?php echo $menores; ?>
                        </strong>

                    </div>


                    <div class="detalle-precio">

                        <span>
                            Total de pasajeros
                        </span>

                        <strong>
                            <?php echo $totalPasajeros; ?>
                        </strong>

                    </div>


                    <hr>


                    <div class="detalle-precio total">

                        <span>
                            Precio total
                        </span>

                        <strong>
                            $
                            <?php
                            echo number_format(
                                $precioTotal,
                                0,
                                ",",
                                "."
                            );
                            ?>
                        </strong>

                    </div>


                    <form method="POST">


                        <input
                            type="hidden"
                            name="accion"
                            value="reservar"
                        >

                        <input
                            type="hidden"
                            name="codVuelo"
                            value="<?php echo $codVuelo; ?>"
                        >

                        <input
                            type="hidden"
                            name="adultos"
                            value="<?php echo $adultos; ?>"
                        >

                        <input
                            type="hidden"
                            name="menores"
                            value="<?php echo $menores; ?>"
                        >

                        <input
                            type="hidden"
                            name="tipoViaje"
                            value="<?php echo htmlspecialchars($tipoViaje); ?>"
                        >

                        <input
                            type="hidden"
                            name="origen"
                            value="<?php echo htmlspecialchars($origen); ?>"
                        >

                        <input
                            type="hidden"
                            name="destino"
                            value="<?php echo htmlspecialchars($destino); ?>"
                        >

                        <input
                            type="hidden"
                            name="fechaIda"
                            value="<?php echo htmlspecialchars($fechaIda); ?>"
                        >

                        <input
                            type="hidden"
                            name="fechaVuelta"
                            value="<?php echo htmlspecialchars($fechaVuelta); ?>"
                        >

                        <input
                            type="hidden"
                            name="tramo"
                            value="<?php echo htmlspecialchars($tramo); ?>"
                        >

                        <input
                            type="hidden"
                            name="reservaIda"
                            value="<?php echo $reservaIda; ?>"
                        >


                        <!-- CONSERVAR ADULTOS -->

                        <?php
                        for ($i = 0; $i < $adultos; $i++) {
                        ?>

                            <input
                                type="hidden"
                                name="nombreAdulto[]"
                                value="<?php
                                    echo valorArray(
                                        $_POST["nombreAdulto"] ?? [],
                                        $i
                                    );
                                ?>"
                            >

                            <input
                                type="hidden"
                                name="apellidoAdulto[]"
                                value="<?php
                                    echo valorArray(
                                        $_POST["apellidoAdulto"] ?? [],
                                        $i
                                    );
                                ?>"
                            >

                            <input
                                type="hidden"
                                name="emailAdulto[]"
                                value="<?php
                                    echo valorArray(
                                        $_POST["emailAdulto"] ?? [],
                                        $i
                                    );
                                ?>"
                            >

                            <input
                                type="hidden"
                                name="fechaAdulto[]"
                                value="<?php
                                    echo valorArray(
                                        $_POST["fechaAdulto"] ?? [],
                                        $i
                                    );
                                ?>"
                            >

                        <?php } ?>


                        <!-- CONSERVAR MENORES -->

                        <?php
                        for ($i = 0; $i < $menores; $i++) {
                        ?>

                            <input
                                type="hidden"
                                name="nombreMenor[]"
                                value="<?php
                                    echo valorArray(
                                        $_POST["nombreMenor"] ?? [],
                                        $i
                                    );
                                ?>"
                            >

                            <input
                                type="hidden"
                                name="apellidoMenor[]"
                                value="<?php
                                    echo valorArray(
                                        $_POST["apellidoMenor"] ?? [],
                                        $i
                                    );
                                ?>"
                            >

                            <input
                                type="hidden"
                                name="emailMenor[]"
                                value="<?php
                                    echo valorArray(
                                        $_POST["emailMenor"] ?? [],
                                        $i
                                    );
                                ?>"
                            >

                            <input
                                type="hidden"
                                name="fechaMenor[]"
                                value="<?php
                                    echo valorArray(
                                        $_POST["fechaMenor"] ?? [],
                                        $i
                                    );
                                ?>"
                            >

                        <?php } ?>


                        <button
                            type="submit"
                            class="boton-pagar"
                        >
                            Reservar
                        </button>

                    </form>

                </div>

            <?php } ?>


            <!-- NO ESTÁ REGISTRADO-->

            <?php if ($pasoFormulario === 4) { ?>

                <div class="tarjeta-reserva">

                    <div class="modal-icono">

                        <i class="bi bi-person-circle"></i>

                    </div>


                    <h2>
                        Necesitás registrarte
                    </h2>


                    <p>
                        Para continuar con la reserva
                        tenés que crear una cuenta.
                    </p>

                    <p>
                        No vas a perder los datos de tu vuelo
                        ni los pasajeros ingresados.
                    </p>


                    <div class="modal-botones">

                        <a
                            href="registro.php"
                            class="btn-ir-registro"
                        >
                            Ir al registro
                        </a>

                        <a
                            href="inicioSesion.php"
                            class="btn-cancelar"
                        >
                            Ya tengo cuenta
                        </a>

                    </div>

                </div>

            <?php } ?>


        </div>

    </div>

</main>


<?php include "includes/footer.php"; ?>

<script src="js/bootstrap.bundle.min.js"></script>


</body>

</html>