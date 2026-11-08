<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);

session_start();

include("php/conexionBD.php");

// OBTENER AEROPUERTOS PARA EL BUSCADOR

$consultaAeropuertos = $conexion->query("
    SELECT
        a.codigoIATA,
        a.nombreAeropuerto,
        c.nombreCiudad,
        p.nombrePais
    FROM aeropuertos a
    INNER JOIN ciudades c ON a.codCiudad = c.codCiudad
    INNER JOIN paises p ON c.codPais = p.codPais
    ORDER BY c.nombreCiudad ASC, a.codigoIATA ASC
");

// OBTENER AEROPUERTOS PARA EL FORMULARIO DE DESTINOS

$consultaOrigenes = $conexion->query("
    SELECT
        a.codigoIATA,
        a.nombreAeropuerto,
        c.nombreCiudad,
        p.nombrePais
    FROM aeropuertos a
    INNER JOIN ciudades c ON a.codCiudad = c.codCiudad
    INNER JOIN paises p ON c.codPais = p.codPais
    ORDER BY c.nombreCiudad ASC, a.codigoIATA ASC
");
?>

<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">

    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <title>Nuvia</title>

    <link rel="icon" href="imagenes/logo.png" type="image/png">

    <link rel="stylesheet" href="css/bootstrap.min.css">

    <link rel="stylesheet" href="css/bootstrap-icons.css">

    <link rel="stylesheet" href="css/estiloshome.css?v=3">

    <link rel="stylesheet" href="css/estilos.css">

    <link rel="stylesheet" href="css/footer.css">

    <link
        rel="stylesheet"
        href="https://cdn.jsdelivr.net/npm/flatpickr/dist/flatpickr.min.css"
    >
</head>

<body class="nuvia-home nuvia-navbar">

<?php include("includes/navbar.php"); ?>


<!-- =========================================
     HERO
========================================= -->

<div class="hero">

    <!-- =====================================
         BUSCADOR DE VUELOS
    ===================================== -->

    <div id="buscador" class="contenedor-buscador">

        <div class="tipo-viaje">

            <p class="titulo-buscador">
                Encontrá el
                <strong>vuelo ideal</strong>
                para tu próximo viaje
            </p>

            <!-- IDA Y VUELTA -->

            <input
                type="radio"
                name="tipoViaje"
                id="ida-vuelta"
                value="idaVuelta"
                checked
            >

            <label for="ida-vuelta">
                Ida y vuelta
            </label>

            <!-- SOLO IDA -->

            <input
                type="radio"
                name="tipoViaje"
                id="solo-ida"
                value="soloIda"
            >

            <label for="solo-ida">
                Solo ida
            </label>

        </div>


        <!-- FORMULARIO DE BÚSQUEDA -->

        <form action="resultadosVuelos.php" method="GET">

            <input
                type="hidden"
                id="tipoViajeForm"
                name="tipoViaje"
                value="idaVuelta"
            >

            <div id="buscador-ing-datos">

                <div class="row g-0">

                    <!-- ORIGEN -->

                    <div class="col-md-3">

                        <input
                            type="text"
                            class="form-control"
                            name="origen"
                            placeholder="Desde"
                            list="aeropuertos"
                            autocomplete="off"
                            required
                        >

                    </div>


                    <!-- DESTINO -->

                    <div class="col-md-3">

                        <input
                            type="text"
                            class="form-control"
                            name="destino"
                            placeholder="Hacia"
                            list="aeropuertos"
                            autocomplete="off"
                            required
                        >

                    </div>


                    <!-- FECHAS -->

                    <div class="col-md-4">

                        <input
                            type="text"
                            id="fechaViaje"
                            class="form-control"
                            placeholder="Seleccioná fecha"
                            readonly
                        >

                        <input
                            type="hidden"
                            id="fechaIda"
                            name="fechaIda"
                        >

                        <input
                            type="hidden"
                            id="fechaVuelta"
                            name="fechaVuelta"
                        >

                    </div>


                    <!-- BOTÓN BUSCAR -->

                    <div class="col-md-2">

                        <button
                            type="submit"
                            class="buscar"
                            aria-label="Buscar vuelos"
                        >
                            →
                        </button>

                    </div>

                </div>

            </div>


            <!-- LISTA DE AEROPUERTOS -->

            <datalist id="aeropuertos">

                <?php while ($aeropuerto = $consultaAeropuertos->fetch_assoc()) {

                    $nombreCompleto =
                        $aeropuerto["nombreCiudad"]
                        . " - "
                        . $aeropuerto["codigoIATA"]
                        . " - "
                        . $aeropuerto["nombreAeropuerto"];
                ?>

                    <option
                        value="<?= htmlspecialchars(
                            $nombreCompleto,
                            ENT_QUOTES,
                            'UTF-8'
                        ) ?>"
                    ></option>

                <?php } ?>

            </datalist>

        </form>

    </div>


    <!-- =====================================
         DESTINOS DESTACADOS
    ===================================== -->

    <section id="destinos" class="destinos-destacados">

        <h2 class="titulo-destino">
            Viajá por el mundo
        </h2>


        <div class="carrusel-destinos">

            <!-- FLECHA ANTERIOR -->

            <button
                type="button"
                class="flecha-destino flecha-anterior"
                id="destinoAnterior"
                aria-label="Ver destino anterior"
            >
                &#10094;
            </button>


            <!-- TARJETAS -->

            <div
                class="contenedor-tarjetas"
                id="carruselTarjetas"
                role="region"
                aria-label="Destinos destacados"
            >

                <!-- BRASIL -->

                <button
                    type="button"
                    class="tarjeta-destino"
                    onclick="abrirOferta('Río de Janeiro - GIG - Aeropuerto Internacional de Río de Janeiro-Galeão')"
                    aria-label="Buscar vuelos a Río de Janeiro, Brasil"
                >

                    <img
                        src="imagenes/brasil.jpg"
                        alt=""
                    >

                    <span class="titulo-tarjeta">
                        Brasil, Río de Janeiro
                    </span>

                </button>


                <!-- BUENOS AIRES -->

                <button
                    type="button"
                    class="tarjeta-destino"
                    onclick="abrirOferta('Buenos Aires - EZE - Aeropuerto Internacional Ministro Pistarini')"
                    aria-label="Buscar vuelos a Buenos Aires, Argentina"
                >

                    <img
                        src="imagenes/bsas.jpg"
                        alt=""
                    >

                    <span class="titulo-tarjeta">
                        Argentina, Buenos Aires
                    </span>

                </button>


                <!-- MADRID -->

                <button
                    type="button"
                    class="tarjeta-destino"
                    onclick="abrirOferta('Madrid - MAD - Aeropuerto Adolfo Suárez Madrid-Barajas')"
                    aria-label="Buscar vuelos a Madrid, España"
                >

                    <img
                        src="imagenes/madrid.jpg"
                        alt=""
                    >

                    <span class="titulo-tarjeta">
                        España, Madrid
                    </span>

                </button>


                <!-- ROMA -->

                <button
                    type="button"
                    class="tarjeta-destino"
                    onclick="abrirOferta('Roma - FCO - Aeropuerto Internacional Leonardo da Vinci')"
                    aria-label="Buscar vuelos a Roma, Italia"
                >

                    <img
                        src="imagenes/roma.jpg"
                        alt=""
                    >

                    <span class="titulo-tarjeta">
                        Italia, Roma
                    </span>

                </button>

            </div>


            <!-- FLECHA SIGUIENTE -->

            <button
                type="button"
                class="flecha-destino flecha-siguiente"
                id="destinoSiguiente"
                aria-label="Ver siguiente destino"
            >
                &#10095;
            </button>

        </div>


        <!-- INDICADORES -->

        <div class="indicadores-destinos" id="indicadoresDestinos">

            <button
                type="button"
                class="indicador-destino activo"
                data-destino="0"
                aria-label="Ver Brasil"
            ></button>

            <button
                type="button"
                class="indicador-destino"
                data-destino="1"
                aria-label="Ver Buenos Aires"
            ></button>

            <button
                type="button"
                class="indicador-destino"
                data-destino="2"
                aria-label="Ver Madrid"
            ></button>

            <button
                type="button"
                class="indicador-destino"
                data-destino="3"
                aria-label="Ver Roma"
            ></button>

        </div>

    </section>

</div>


<!-- =========================================
     FOOTER
========================================= -->

<?php include("includes/footer.php"); ?>


<!-- =========================================
     MODAL DE DESTINOS
========================================= -->

<div id="modalOferta" class="modal-oferta">

    <div class="modal-oferta-contenido">

        <!-- CERRAR MODAL -->

        <button
            type="button"
            class="cerrar-oferta"
            onclick="cerrarOferta()"
            aria-label="Cerrar formulario"
        >
            ×
        </button>


        <h2>
            Encontrá tu vuelo
        </h2>

        <p class="destino-oferta">
            Destino:
            <strong id="nombreDestinoOferta"></strong>
        </p>


        <form action="resultadosVuelos.php" method="GET">

            <!-- SOLO IDA -->

            <input
                type="hidden"
                name="tipoViaje"
                value="soloIda"
            >


            <!-- DESTINO ELEGIDO -->

            <input
                type="hidden"
                name="destino"
                id="destinoOferta"
            >


            <!-- ORIGEN -->

            <div class="campo-oferta">

                <label for="origenOferta">
                    ¿Desde dónde viajás?
                </label>

                <select
                    name="origen"
                    id="origenOferta"
                    required
                >

                    <option value="">
                        Seleccioná un aeropuerto
                    </option>

                    <?php while ($origenBD = $consultaOrigenes->fetch_assoc()) {

                        $origenCompleto =
                            $origenBD["nombreCiudad"]
                            . " - "
                            . $origenBD["codigoIATA"]
                            . " - "
                            . $origenBD["nombreAeropuerto"];

                    ?>

                        <option
                            value="<?= htmlspecialchars(
                                $origenCompleto,
                                ENT_QUOTES,
                                'UTF-8'
                            ) ?>"
                        >
                            <?= htmlspecialchars(
                                $origenCompleto,
                                ENT_QUOTES,
                                'UTF-8'
                            ) ?>
                        </option>

                    <?php } ?>

                </select>

            </div>


            <!-- FECHA -->

            <div class="campo-oferta">

                <label for="fechaOferta">
                    Fecha de ida
                </label>

                <input
                    type="date"
                    name="fechaIda"
                    id="fechaOferta"
                    required
                >

            </div>


            <!-- BUSCAR -->

            <button
                type="submit"
                class="boton-buscar-oferta"
            >
                Buscar vuelos
            </button>

        </form>

    </div>

</div>


<!-- =========================================
     JAVASCRIPT
========================================= -->

<script src="https://cdn.jsdelivr.net/npm/flatpickr"></script>

<script>

// =========================================
// VARIABLES DEL BUSCADOR
// =========================================

const radioIdaVuelta = document.getElementById("ida-vuelta");
const radioSoloIda = document.getElementById("solo-ida");

const fechaIda = document.getElementById("fechaIda");
const fechaVuelta = document.getElementById("fechaVuelta");

const tipoViajeForm = document.getElementById("tipoViajeForm");

let primeraFecha = null;


// =========================================
// CALENDARIO
// =========================================

const calendario = flatpickr("#fechaViaje", {

    mode: "range",

    dateFormat: "d/m/Y",

    rangeSeparator: " - ",

    minDate: "today",

    locale: {

        firstDayOfWeek: 1,

        weekdays: {

            shorthand: [
                "Dom", "Lun", "Mar", "Mié",
                "Jue", "Vie", "Sáb"
            ],

            longhand: [
                "Domingo",
                "Lunes",
                "Martes",
                "Miércoles",
                "Jueves",
                "Viernes",
                "Sábado"
            ]

        },

        months: {

            shorthand: [
                "Ene", "Feb", "Mar", "Abr",
                "May", "Jun", "Jul", "Ago",
                "Sep", "Oct", "Nov", "Dic"
            ],

            longhand: [
                "Enero",
                "Febrero",
                "Marzo",
                "Abril",
                "Mayo",
                "Junio",
                "Julio",
                "Agosto",
                "Septiembre",
                "Octubre",
                "Noviembre",
                "Diciembre"
            ]

        }

    },


    onChange: function(selectedDates, dateStr, instance) {

        // SOLO IDA

        if (radioSoloIda.checked) {

            if (selectedDates.length >= 1) {

                fechaIda.value = instance.formatDate(
                    selectedDates[0],
                    "Y-m-d"
                );

                fechaVuelta.value = "";

                instance.close();

            }

            return;
        }


        // IDA Y VUELTA

        if (selectedDates.length === 1) {

            primeraFecha = selectedDates[0];

            fechaIda.value = instance.formatDate(
                primeraFecha,
                "Y-m-d"
            );

            fechaVuelta.value = "";

        }


        if (selectedDates.length === 2) {

            const ida = selectedDates[0];
            const vuelta = selectedDates[1];

            fechaIda.value = instance.formatDate(
                ida,
                "Y-m-d"
            );

            fechaVuelta.value = instance.formatDate(
                vuelta,
                "Y-m-d"
            );

            instance.close();

        }

    }

});


// =========================================
// CAMBIAR A SOLO IDA
// =========================================

radioSoloIda.addEventListener("change", function() {

    tipoViajeForm.value = "soloIda";

    primeraFecha = null;

    calendario.clear();

    calendario.set("mode", "single");

    document.getElementById("fechaViaje").placeholder =
        "Seleccioná fecha de ida";

    fechaIda.value = "";
    fechaVuelta.value = "";

});


// =========================================
// CAMBIAR A IDA Y VUELTA
// =========================================

radioIdaVuelta.addEventListener("change", function() {

    tipoViajeForm.value = "idaVuelta";

    primeraFecha = null;

    calendario.clear();

    calendario.set("mode", "range");

    document.getElementById("fechaViaje").placeholder =
        "Seleccioná ida y vuelta";

    fechaIda.value = "";
    fechaVuelta.value = "";

});


// =========================================
// ABRIR MODAL DE DESTINO
// =========================================

function abrirOferta(destino) {

    document.getElementById("destinoOferta").value =
        destino;

    document.getElementById("nombreDestinoOferta").textContent =
        destino;

    document.getElementById("modalOferta").style.display =
        "flex";

}


// =========================================
// CERRAR MODAL DE DESTINO
// =========================================

function cerrarOferta() {

    document.getElementById("modalOferta").style.display =
        "none";

}


// =========================================
// CARRUSEL DE DESTINOS
// =========================================

const carrusel = document.getElementById("carruselTarjetas");

const tarjetas = Array.from(
    carrusel.querySelectorAll(".tarjeta-destino")
);

const indicadores = Array.from(
    document.querySelectorAll(".indicador-destino")
);

const botonAnterior = document.getElementById("destinoAnterior");

const botonSiguiente = document.getElementById("destinoSiguiente");

const pantallaCelular = window.matchMedia("(max-width: 767px)");

const movimientoReducido = window.matchMedia(
    "(prefers-reduced-motion: reduce)"
);

let destinoActual = 0;

let intervaloCarrusel = null;


// ACTUALIZAR INDICADORES

function actualizarIndicadores() {

    indicadores.forEach(function(indicador, indice) {

        indicador.classList.toggle(
            "activo",
            indice === destinoActual
        );

    });

}


// MOSTRAR UN DESTINO

function mostrarDestino(indice) {

    if (!pantallaCelular.matches) {
        return;
    }

    destinoActual = (
        indice + tarjetas.length
    ) % tarjetas.length;

    carrusel.scrollTo({

        left: tarjetas[destinoActual].offsetLeft -
              tarjetas[0].offsetLeft,

        behavior: movimientoReducido.matches
            ? "instant"
            : "smooth"

    });

    actualizarIndicadores();

}


// SIGUIENTE DESTINO

botonSiguiente.addEventListener("click", function() {

    mostrarDestino(destinoActual + 1);

    reiniciarCarrusel();

});


// DESTINO ANTERIOR

botonAnterior.addEventListener("click", function() {

    mostrarDestino(destinoActual - 1);

    reiniciarCarrusel();

});


// INDICADORES

indicadores.forEach(function(indicador, indice) {

    indicador.addEventListener("click", function() {

        mostrarDestino(indice);

        reiniciarCarrusel();

    });

});


// DETECTAR DESLIZAMIENTO CON EL DEDO

let temporizadorScroll;

carrusel.addEventListener("scroll", function() {

    if (!pantallaCelular.matches) {
        return;
    }

    clearTimeout(temporizadorScroll);

    temporizadorScroll = setTimeout(function() {

        let distanciaMinima = Infinity;
        let indiceMasCercano = 0;

        tarjetas.forEach(function(tarjeta, indice) {

            const posicion =
                tarjeta.offsetLeft - tarjetas[0].offsetLeft;

            const distancia = Math.abs(
                carrusel.scrollLeft - posicion
            );

            if (distancia < distanciaMinima) {

                distanciaMinima = distancia;
                indiceMasCercano = indice;

            }

        });

        destinoActual = indiceMasCercano;

        actualizarIndicadores();

    }, 100);

}, { passive: true });


// CAMBIO AUTOMÁTICO

function iniciarCarrusel() {

    detenerCarrusel();

    if (
        !pantallaCelular.matches ||
        movimientoReducido.matches ||
        document.hidden
    ) {
        return;
    }

    intervaloCarrusel = setInterval(function() {

        mostrarDestino(destinoActual + 1);

    }, 4000);

}


// DETENER CAMBIO AUTOMÁTICO

function detenerCarrusel() {

    if (intervaloCarrusel !== null) {

        clearInterval(intervaloCarrusel);

        intervaloCarrusel = null;

    }

}


// REINICIAR TEMPORIZADOR

function reiniciarCarrusel() {

    iniciarCarrusel();

}


// PAUSAR MIENTRAS EL USUARIO INTERACTÚA

carrusel.addEventListener("touchstart", detenerCarrusel, {
    passive: true
});

carrusel.addEventListener("touchend", reiniciarCarrusel, {
    passive: true
});

carrusel.addEventListener("focusin", detenerCarrusel);

carrusel.addEventListener("focusout", reiniciarCarrusel);

carrusel.addEventListener("mouseenter", detenerCarrusel);

carrusel.addEventListener("mouseleave", reiniciarCarrusel);


// CAMBIOS DE TAMAÑO DE PANTALLA

pantallaCelular.addEventListener("change", function() {

    destinoActual = 0;

    carrusel.scrollLeft = 0;

    actualizarIndicadores();

    iniciarCarrusel();

});

movimientoReducido.addEventListener("change", iniciarCarrusel);

document.addEventListener("visibilitychange", iniciarCarrusel);


// INICIAR

actualizarIndicadores();

iniciarCarrusel();

</script>


<!-- BOOTSTRAP: SE CARGA UNA SOLA VEZ -->

<script src="<?= htmlspecialchars(
    urlApp('js/bootstrap.bundle.min.js'),
    ENT_QUOTES,
    'UTF-8'
) ?>"></script>

</body>
</html>