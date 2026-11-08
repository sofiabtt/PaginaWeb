<?php

session_start();

include "php/conexionBD.php";
include "php/consultasVuelos.php";
include "php/procesarVueloElegido.php";


// =========================
// DATOS DE LA BÚSQUEDA
// =========================

foreach (
    [
        "tipoViaje" => "tipoViajeReserva",
        "origen" => "origenReserva",
        "destino" => "destinoReserva",
        "fechaIda" => "fechaIdaReserva",
        "fechaVuelta" => "fechaVueltaReserva",
        "tramo" => "tramoReserva"
    ] as $parametro => $claveSesion
) {
    if (isset($_GET[$parametro])) {
        $_SESSION[$claveSesion] = $_GET[$parametro];
    }
}

$tipoViaje = $_GET["tipoViaje"]
    ?? $_POST["tipoViaje"]
    ?? $_SESSION["tipoViajeReserva"]
    ?? "soloIda";

$origen = $_GET["origen"]
    ?? $_POST["origen"]
    ?? $_SESSION["origenReserva"]
    ?? "";

$destino = $_GET["destino"]
    ?? $_POST["destino"]
    ?? $_SESSION["destinoReserva"]
    ?? "";

$fechaIda = $_GET["fechaIda"]
    ?? $_POST["fechaIda"]
    ?? $_SESSION["fechaIdaReserva"]
    ?? "";

$fechaVuelta = $_GET["fechaVuelta"]
    ?? $_POST["fechaVuelta"]
    ?? $_SESSION["fechaVueltaReserva"]
    ?? "";

$tramo = $_GET["tramo"]
    ?? $_POST["tramo"]
    ?? $_SESSION["tramoReserva"]
    ?? "ida";

$reservaIda = (int) (
    $_GET["reservaIda"]
    ?? $_POST["reservaIda"]
    ?? 0
);

$codVuelo = (int) (
    $_GET["codVuelo"]
    ?? $_POST["codVuelo"]
    ?? 0
);

if ($codVuelo <= 0) {
    die("No se seleccionó ningún vuelo.");
}

$vuelo = obtenerVueloElegido($conexion, $codVuelo);

if (!$vuelo) {
    die("No se encontró el vuelo seleccionado.");
}


// =========================
// FUNCIONES AUXILIARES
// =========================

function escaparVuelo($valor)
{
    return htmlspecialchars(
        (string) $valor,
        ENT_QUOTES,
        "UTF-8"
    );
}

function camposContextoVuelo(
    $codVuelo,
    $tipoViaje,
    $origen,
    $destino,
    $fechaIda,
    $fechaVuelta,
    $tramo,
    $reservaIda
) {
    $campos = [
        "codVuelo" => $codVuelo,
        "tipoViaje" => $tipoViaje,
        "origen" => $origen,
        "destino" => $destino,
        "fechaIda" => $fechaIda,
        "fechaVuelta" => $fechaVuelta,
        "tramo" => $tramo,
        "reservaIda" => $reservaIda
    ];

    foreach ($campos as $nombre => $valor) {
        echo '<input type="hidden" name="'
            . escaparVuelo($nombre)
            . '" value="'
            . escaparVuelo($valor)
            . '">';
    }
}

function campoPasajeroVuelo($nombre, $indice, $tipo = "text")
{
    $valores = $_POST[$nombre] ?? [];
    $valor = $valores[$indice] ?? "";

    $atributos = "";

    if ($tipo === "date") {
        $atributos = ' class="form-control"';
    }

    echo '<input type="'
        . escaparVuelo($tipo)
        . '" name="'
        . escaparVuelo($nombre)
        . '[]" value="'
        . escaparVuelo($valor)
        . '"'
        . $atributos
        . ' required>';
}


// =========================
// VARIABLES DEL FORMULARIO
// =========================

$pasoFormulario = 1;

$adultos = 1;
$menores = 0;

$error = "";

$reservaCreada = false;
$codReservaCreada = 0;


// =========================
// GUARDAR DATOS POR VUELO
// =========================

// Se usa una clave distinta por vuelo y tramo.
// Así no se mezclan pasajeros de vuelos diferentes.

$claveDatosVuelo = $codVuelo . "_" . $tramo . "_" . $reservaIda;

if (!isset($_SESSION["datosPasajerosVuelo"])) {
    $_SESSION["datosPasajerosVuelo"] = [];
}

$datosGuardados =
    $_SESSION["datosPasajerosVuelo"][$claveDatosVuelo] ?? [];


// =========================
// RESTAURAR RESERVA TEMPORAL
// =========================

if (
    !empty($_SESSION["restaurarReservaTemporal"])
    && isset($_SESSION["reservaTemporal"])
) {
    $reservaTemporal = $_SESSION["reservaTemporal"];

    if ((int) ($reservaTemporal["codVuelo"] ?? 0) === $codVuelo) {

        $adultos = (int) ($reservaTemporal["adultos"] ?? 1);
        $menores = (int) ($reservaTemporal["menores"] ?? 0);

        $datosGuardados = [
            "nombreAdulto" => $reservaTemporal["nombreAdulto"] ?? [],
            "apellidoAdulto" => $reservaTemporal["apellidoAdulto"] ?? [],
            "emailAdulto" => $reservaTemporal["emailAdulto"] ?? [],
            "fechaAdulto" => $reservaTemporal["fechaAdulto"] ?? [],
            "nombreMenor" => $reservaTemporal["nombreMenor"] ?? [],
            "apellidoMenor" => $reservaTemporal["apellidoMenor"] ?? [],
            "emailMenor" => $reservaTemporal["emailMenor"] ?? [],
            "fechaMenor" => $reservaTemporal["fechaMenor"] ?? []
        ];

        $_SESSION["datosPasajerosVuelo"][$claveDatosVuelo] =
            $datosGuardados;

        $pasoFormulario = 3;

        unset($_SESSION["restaurarReservaTemporal"]);
    }
}

foreach ($datosGuardados as $campo => $valores) {
    $_POST[$campo] = $valores;
}


// =========================
// PROCESAR FORMULARIOS
// =========================

if ($_SERVER["REQUEST_METHOD"] === "POST") {

    $accion = $_POST["accion"] ?? "";


    // VOLVER A CANTIDAD DE PASAJEROS

    if ($accion === "volverCantidad") {

        $adultos = (int) ($_POST["adultos"] ?? 1);
        $menores = (int) ($_POST["menores"] ?? 0);

        $pasoFormulario = 1;
    }


    // VOLVER A DATOS DE PASAJEROS

    if (
        $accion === "volverDatos"
        || $accion === "volverResumen"
    ) {

        $adultos = (int) ($_POST["adultos"] ?? 1);
        $menores = (int) ($_POST["menores"] ?? 0);

        if (isset($_SESSION["reservaTemporal"])) {

            $temporal = $_SESSION["reservaTemporal"];

            if ((int) ($temporal["codVuelo"] ?? 0) === $codVuelo) {

                $adultos = (int) ($temporal["adultos"] ?? $adultos);
                $menores = (int) ($temporal["menores"] ?? $menores);
            }
        }

        foreach ($datosGuardados as $campo => $valores) {
            $_POST[$campo] = $valores;
        }

        $pasoFormulario = 2;
    }


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


    // PASO 2 - DATOS DE PASAJEROS

    if ($accion === "datos") {

        $adultos = (int) ($_POST["adultos"] ?? 0);
        $menores = (int) ($_POST["menores"] ?? 0);

        $nombresAdultos = $_POST["nombreAdulto"] ?? [];
        $apellidosAdultos = $_POST["apellidoAdulto"] ?? [];
        $emailsAdultos = $_POST["emailAdulto"] ?? [];
        $fechasAdultos = $_POST["fechaAdulto"] ?? [];

        $nombresMenores = $_POST["nombreMenor"] ?? [];
        $apellidosMenores = $_POST["apellidoMenor"] ?? [];
        $emailsMenores = $_POST["emailMenor"] ?? [];
        $fechasMenores = $_POST["fechaMenor"] ?? [];


        // CONSERVAR LOS DATOS INGRESADOS

        $datosGuardados = [
            "nombreAdulto" => $nombresAdultos,
            "apellidoAdulto" => $apellidosAdultos,
            "emailAdulto" => $emailsAdultos,
            "fechaAdulto" => $fechasAdultos,
            "nombreMenor" => $nombresMenores,
            "apellidoMenor" => $apellidosMenores,
            "emailMenor" => $emailsMenores,
            "fechaMenor" => $fechasMenores
        ];

        $_SESSION["datosPasajerosVuelo"][$claveDatosVuelo] =
            $datosGuardados;


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

        foreach ($datosGuardados as $campo => $valores) {
            $_POST[$campo] = $valores;
        }

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


        // SI NECESITA INICIAR SESIÓN

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


// =========================
// PRECIO
// =========================

$totalPasajeros = $adultos + $menores;

$precioTotal =
    $totalPasajeros * (float) $vuelo["precioVuelo"];

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
        href="imagenes/logo.png"
    >

    <link rel="stylesheet" href="css/bootstrap.min.css">
    <link rel="stylesheet" href="css/bootstrap-icons.css">

    <link rel="stylesheet" href="<?= htmlspecialchars(urlApp('css/estilos.css'), ENT_QUOTES, 'UTF-8') ?>?v=20261108">
    <link rel="stylesheet" href="css/estilosVueloElegido.css">
    <link rel="stylesheet" href="css/progresoReserva.css?v=4">
    <link rel="stylesheet" href="css/footer.css">

    <!-- AJUSTES DE LOS BOTONES VOLVER Y REGISTRO -->

    <style>

        .reporte-volver {
            display: inline-flex;
            align-items: center;
            gap: 8px;

            padding: 0;
            margin-bottom: 0;

            background: transparent;
            border: none;

            color: #555;
            text-decoration: none;

            font-family: inherit;
            font-size: 14px;

            cursor: pointer;

            transition:
                color 0.2s ease,
                transform 0.2s ease;
        }

        .reporte-volver:hover {
            color: #684028;
            transform: translateX(-4px);
        }

        .reporte-volver:focus-visible {
            outline: 2px solid #835a40;
            outline-offset: 4px;
            border-radius: 3px;
        }

        #cantidad-pasajeros,
        #datos-pasajeros,
        #resumen-reserva,
        #necesitas-registrarte {
            scroll-margin-top: 90px;
        }

        .form-volver-registro {
            margin-bottom: 18px;
        }

        .icono-registro {
            color: #835a40;
            font-size: 32px;
            line-height: 1;
            margin-bottom: 12px;
        }

        .tarjeta-registro h2 {
            margin-bottom: 20px;
        }

        .tarjeta-registro p {
            margin-bottom: 18px;
        }

        .tarjeta-registro .modal-botones a {
            text-align: center;
            text-decoration: none;
        }

        @media (max-width: 768px) {

            .tarjeta-registro {
                padding: 22px;
            }

            .icono-registro {
                font-size: 28px;
                margin-bottom: 10px;
            }

            .tarjeta-registro h2 {
                font-size: 23px;
                margin-bottom: 18px;
            }

            .tarjeta-registro p {
                font-size: 14px;
                line-height: 1.6;
                margin-bottom: 15px;
            }

            .tarjeta-registro .modal-botones {
                display: flex;
                flex-direction: column;
                align-items: stretch;
                gap: 12px;
            }

            .tarjeta-registro .modal-botones a {
                display: block;
                width: 100%;
            }

        }

    </style>

</head>

<body class="nuvia-vuelos">

<?php include "includes/navbar.php"; ?>

<main class="contenedor-vuelo-elegido">

    <div class="container">


        <!-- =========================
             BOTÓN VOLVER SUPERIOR
        ========================= -->

        <div class="mb-4">

            <?php if ($pasoFormulario === 1) { ?>

                <a href="./" class="reporte-volver">
                    <i class="bi bi-arrow-left"></i>
                    Volver al inicio
                </a>

            <?php } else { ?>

                <form
                    method="POST"
                    action="<?php
                        echo $pasoFormulario === 2
                            ? '#cantidad-pasajeros'
                            : '#datos-pasajeros';
                    ?>"
                >

                    <input
                        type="hidden"
                        name="accion"
                        value="<?php
                            echo $pasoFormulario === 2
                                ? 'volverCantidad'
                                : 'volverDatos';
                        ?>"
                    >

                    <?php
                    camposContextoVuelo(
                        $codVuelo,
                        $tipoViaje,
                        $origen,
                        $destino,
                        $fechaIda,
                        $fechaVuelta,
                        $tramo,
                        $reservaIda
                    );
                    ?>

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

                    <button
                        type="submit"
                        class="reporte-volver"
                    >
                        <i class="bi bi-arrow-left"></i>
                        Volver
                    </button>

                </form>

            <?php } ?>

        </div>


        <!-- =========================
             PROGRESO SOLO IDA
        ========================= -->

        <?php if ($tipoViaje === "soloIda") { ?>

            <div class="progreso-reserva mb-5">

                <div class="progreso-titulo">
                    <h2 class="meta-valor">
                        Obtener vuelo
                    </h2>
                </div>

                <div class="timeline-pasos">

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
                                Vuelo seleccionado.
                            </p>

                        </div>

                    </div>


                    <div class="timeline-paso <?php
                        echo $reservaCreada
                            ? 'completado'
                            : 'activo';
                    ?>">

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


                    <div class="timeline-paso <?php
                        echo $reservaCreada ? 'activo' : '';
                    ?>">

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


        <!-- =========================
             PROGRESO IDA Y VUELTA
        ========================= -->

        <?php } elseif ($tipoViaje === "idaVuelta") { ?>

            <div class="progreso-reserva progreso-ida-vuelta mb-5">

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

                    <div class="timeline-paso <?php
                        echo $tramo === 'ida'
                            ? 'activo'
                            : 'completado';
                    ?>">

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

                    <div class="timeline-paso <?php
                        echo $tramo === 'vuelta'
                            ? 'completado'
                            : '';
                    ?>">

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

                    <div class="timeline-paso <?php
                        echo $tramo === 'vuelta'
                            ? 'activo'
                            : '';
                    ?>">

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


        <!-- =========================
             VUELO ELEGIDO
        ========================= -->

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
                            echo escaparVuelo(
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
                                    echo escaparVuelo(
                                        $vuelo["origenVuelo"]
                                    );
                                    ?>
                                </h4>

                            </div>


                            <div class="col-md-2 text-center">

                                <i class="bi bi-arrow-right flecha-vuelo"></i>

                            </div>


                            <div class="col-md-5">

                                <span class="texto-secundario">
                                    Destino
                                </span>

                                <h4>
                                    <?php
                                    echo escaparVuelo(
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
                                echo escaparVuelo(
                                    $vuelo["fechaSalidaVuelo"]
                                );
                                ?>
                            </span>

                            <span>
                                <i class="bi bi-clock"></i>

                                <?php
                                echo escaparVuelo(
                                    $vuelo["horaSalidaVuelo"]
                                );
                                ?>
                            </span>

                            <span>
                                <i class="bi bi-person"></i>

                                <?php
                                echo escaparVuelo(
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


        <!-- =========================
             FORMULARIO DE RESERVA
        ========================= -->

        <div class="contenedor-reserva">


            <!-- =========================
                 PASO 1 - CANTIDAD
            ========================= -->

            <?php if ($pasoFormulario === 1) { ?>

                <div
                    class="tarjeta-reserva"
                    id="cantidad-pasajeros"
                >

                    <h2>Pasajeros</h2>

                    <?php if ($error !== "") { ?>

                        <p class="mensaje-error">
                            <?php echo escaparVuelo($error); ?>
                        </p>

                    <?php } ?>


                    <form
                        method="POST"
                        action="#datos-pasajeros"
                    >

                        <input
                            type="hidden"
                            name="accion"
                            value="cantidad"
                        >

                        <?php
                        camposContextoVuelo(
                            $codVuelo,
                            $tipoViaje,
                            $origen,
                            $destino,
                            $fechaIda,
                            $fechaVuelta,
                            $tramo,
                            $reservaIda
                        );
                        ?>


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


            <!-- =========================
                 PASO 2 - PASAJEROS
            ========================= -->

            <?php if ($pasoFormulario === 2) { ?>

                <div
                    class="tarjeta-reserva"
                    id="datos-pasajeros"
                >

                    <h2>Datos de los pasajeros</h2>

                    <?php if ($error !== "") { ?>

                        <p class="mensaje-error">
                            <?php echo escaparVuelo($error); ?>
                        </p>

                    <?php } ?>


                    <form
                        method="POST"
                        action="#resumen-reserva"
                    >

                        <input
                            type="hidden"
                            name="accion"
                            value="datos"
                        >

                        <?php
                        camposContextoVuelo(
                            $codVuelo,
                            $tipoViaje,
                            $origen,
                            $destino,
                            $fechaIda,
                            $fechaVuelta,
                            $tramo,
                            $reservaIda
                        );
                        ?>

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


                        <!-- ADULTOS -->

                        <?php for ($i = 0; $i < $adultos; $i++) { ?>

                            <div class="pasajero-card">

                                <h3>
                                    Adulto <?php echo $i + 1; ?>
                                </h3>

                                <div class="grid-datos">

                                    <div>

                                        <label>
                                            Nombre
                                        </label>

                                        <?php
                                        campoPasajeroVuelo(
                                            "nombreAdulto",
                                            $i
                                        );
                                        ?>

                                    </div>


                                    <div>

                                        <label>
                                            Apellido
                                        </label>

                                        <?php
                                        campoPasajeroVuelo(
                                            "apellidoAdulto",
                                            $i
                                        );
                                        ?>

                                    </div>


                                    <div>

                                        <label>
                                            Email
                                        </label>

                                        <?php
                                        campoPasajeroVuelo(
                                            "emailAdulto",
                                            $i,
                                            "email"
                                        );
                                        ?>

                                    </div>


                                    <div>

                                        <label>
                                            Fecha de nacimiento
                                        </label>

                                        <input
                                            type="date"
                                            name="fechaAdulto[]"
                                            class="form-control"

                                            value="<?php
                                                echo escaparVuelo(
                                                    $_POST["fechaAdulto"][$i] ?? ""
                                                );
                                            ?>"

                                            max="<?php echo date('Y-m-d'); ?>"

                                            min="<?php
                                                echo date(
                                                    'Y-m-d',
                                                    strtotime('-115 years')
                                                );
                                            ?>"

                                            required
                                        >

                                    </div>

                                </div>

                            </div>

                        <?php } ?>


                        <!-- MENORES -->

                        <?php for ($i = 0; $i < $menores; $i++) { ?>

                            <div class="pasajero-card">

                                <h3>
                                    Menor <?php echo $i + 1; ?>
                                </h3>

                                <div class="grid-datos">

                                    <div>

                                        <label>
                                            Nombre
                                        </label>

                                        <?php
                                        campoPasajeroVuelo(
                                            "nombreMenor",
                                            $i
                                        );
                                        ?>

                                    </div>


                                    <div>

                                        <label>
                                            Apellido
                                        </label>

                                        <?php
                                        campoPasajeroVuelo(
                                            "apellidoMenor",
                                            $i
                                        );
                                        ?>

                                    </div>


                                    <div>

                                        <label>
                                            Email
                                        </label>

                                        <?php
                                        campoPasajeroVuelo(
                                            "emailMenor",
                                            $i,
                                            "email"
                                        );
                                        ?>

                                    </div>


                                    <div>

                                        <label>
                                            Fecha de nacimiento
                                        </label>

                                        <input
                                            type="date"
                                            name="fechaMenor[]"
                                            class="form-control"

                                            value="<?php
                                                echo escaparVuelo(
                                                    $_POST["fechaMenor"][$i] ?? ""
                                                );
                                            ?>"

                                            max="<?php echo date('Y-m-d'); ?>"

                                            min="<?php
                                                echo date(
                                                    'Y-m-d',
                                                    strtotime('-12 years')
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


            <!-- =========================
                 PASO 3 - RESUMEN
            ========================= -->

            <?php if ($pasoFormulario === 3) { ?>

                <div
                    class="tarjeta-reserva"
                    id="resumen-reserva"
                >

                    <!-- VOLVER A PASAJEROS -->

                    <form
                        method="POST"
                        action="#datos-pasajeros"
                        class="mb-3"
                    >

                        <input
                            type="hidden"
                            name="accion"
                            value="volverDatos"
                        >

                        <?php
                        camposContextoVuelo(
                            $codVuelo,
                            $tipoViaje,
                            $origen,
                            $destino,
                            $fechaIda,
                            $fechaVuelta,
                            $tramo,
                            $reservaIda
                        );
                        ?>

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

                        <button
                            type="submit"
                            class="reporte-volver"
                        >
                            <i class="bi bi-arrow-left"></i>
                            Volver a los pasajeros
                        </button>

                    </form>


                    <h2>Datos de la reserva</h2>


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

                        <span>Adultos</span>

                        <strong>
                            <?php echo $adultos; ?>
                        </strong>

                    </div>


                    <div class="detalle-precio">

                        <span>Menores</span>

                        <strong>
                            <?php echo $menores; ?>
                        </strong>

                    </div>


                    <div class="detalle-precio">

                        <span>Total de pasajeros</span>

                        <strong>
                            <?php echo $totalPasajeros; ?>
                        </strong>

                    </div>


                    <div class="detalle-precio total">

                        <span>Precio total</span>

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


                    <!-- RESERVAR -->

                    <form
                        method="POST"
                        action="<?php
                            echo (
                                !isset($_SESSION["codUsuario"])
                                || !isset($_SESSION["tipoUsuario"])
                                || $_SESSION["tipoUsuario"] !== "usuario"
                            )
                                ? '#necesitas-registrarte'
                                : '';
                        ?>"
                    >

                        <input
                            type="hidden"
                            name="accion"
                            value="reservar"
                        >

                        <?php
                        camposContextoVuelo(
                            $codVuelo,
                            $tipoViaje,
                            $origen,
                            $destino,
                            $fechaIda,
                            $fechaVuelta,
                            $tramo,
                            $reservaIda
                        );
                        ?>

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


                        <!-- CONSERVAR ADULTOS -->

                        <?php for ($i = 0; $i < $adultos; $i++) { ?>

                            <?php foreach (
                                [
                                    "nombreAdulto",
                                    "apellidoAdulto",
                                    "emailAdulto",
                                    "fechaAdulto"
                                ] as $campo
                            ) { ?>

                                <input
                                    type="hidden"
                                    name="<?php echo escaparVuelo($campo); ?>[]"
                                    value="<?php
                                        echo escaparVuelo(
                                            $_POST[$campo][$i] ?? ""
                                        );
                                    ?>"
                                >

                            <?php } ?>

                        <?php } ?>


                        <!-- CONSERVAR MENORES -->

                        <?php for ($i = 0; $i < $menores; $i++) { ?>

                            <?php foreach (
                                [
                                    "nombreMenor",
                                    "apellidoMenor",
                                    "emailMenor",
                                    "fechaMenor"
                                ] as $campo
                            ) { ?>

                                <input
                                    type="hidden"
                                    name="<?php echo escaparVuelo($campo); ?>[]"
                                    value="<?php
                                        echo escaparVuelo(
                                            $_POST[$campo][$i] ?? ""
                                        );
                                    ?>"
                                >

                            <?php } ?>

                        <?php } ?>


                        <button
                            type="submit"
                            class="boton-pagar"
                        >
                            Reservar
                        </button>


                        <p class="mensaje-reserva">

                            <i class="bi bi-clock"></i>

                            Una vez realizada la reserva,
                            tendrás 30 minutos para completar el pago.

                            Pasado ese tiempo, la reserva
                            se cancelará automáticamente.

                        </p>

                    </form>

                </div>

            <?php } ?>


            <!-- =========================
                 PASO 4 - REGISTRO
            ========================= -->

            <?php if ($pasoFormulario === 4) { ?>

                <div
                    class="tarjeta-reserva tarjeta-registro"
                    id="necesitas-registrarte"
                >

                    <!-- VOLVER A LOS PASAJEROS -->

                    <form
                        method="POST"
                        action="#datos-pasajeros"
                        class="form-volver-registro"
                    >

                        <input
                            type="hidden"
                            name="accion"
                            value="volverDatos"
                        >

                        <?php
                        camposContextoVuelo(
                            $codVuelo,
                            $tipoViaje,
                            $origen,
                            $destino,
                            $fechaIda,
                            $fechaVuelta,
                            $tramo,
                            $reservaIda
                        );
                        ?>

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

                        <button
                            type="submit"
                            class="reporte-volver"
                        >
                            <i class="bi bi-arrow-left"></i>
                            Volver a los pasajeros
                        </button>

                    </form>


                    <div class="icono-registro">
                        <i class="bi bi-person-circle"></i>
                    </div>


                    <h2>Necesitás registrarte</h2>


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