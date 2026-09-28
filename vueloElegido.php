
<?php

session_start();

include "php/conexionBD.php";
include "php/consultasVuelos.php";

// Guardamos los datos de la búsqueda para que no se pierdan
// si el usuario tiene que iniciar sesión o registrarse.
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

// Primero usamos lo recibido por URL y, si ya no viene,
// recuperamos los datos guardados en la sesión.
$tipoViaje =
    $_GET["tipoViaje"]
    ?? $_SESSION["tipoViajeReserva"]
    ?? "soloIda";

$origen =
    $_GET["origen"]
    ?? $_SESSION["origenReserva"]
    ?? "";

$destino =
    $_GET["destino"]
    ?? $_SESSION["destinoReserva"]
    ?? "";

$fechaIda =
    $_GET["fechaIda"]
    ?? $_SESSION["fechaIdaReserva"]
    ?? "";

$fechaVuelta =
    $_GET["fechaVuelta"]
    ?? $_SESSION["fechaVueltaReserva"]
    ?? "";

$tramo =
    $_GET["tramo"]
    ?? $_SESSION["tramoReserva"]
    ?? "ida";

$reservaIda =
    isset($_GET["reservaIda"])
    ? (int) $_GET["reservaIda"]
    : 0;

if (!isset($_GET["codVuelo"])) {

    die("No se seleccionó ningún vuelo.");
}


$codVuelo = (int) $_GET["codVuelo"];


$vuelo = obtenerVueloElegido(
    $conexion,
    $codVuelo
);


if (!$vuelo) {

    die("No se encontró el vuelo seleccionado.");
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

    <title>Vuelo elegido</title>

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

    <link rel="stylesheet" href="css/navbar.css">
    <link rel="stylesheet" href="css/progresoReserva.css?v=4">
</head>


<body>


<?php

include"includes/navbar.php";

?>


<main class="contenedor-vuelo-elegido">

    <div class="container">
        <?php if ($tipoViaje === "soloIda"): ?>

            <div class="progreso-reserva mb-5">
                <div class="progreso-titulo">
                    <h2 class="meta-valor">Obtener vuelo</h2>
                </div>

                <div class="timeline-pasos">
                    <div class="timeline-paso completado" id="pasoSoloIda1">
                        <span class="timeline-punto"></span>
                        <div class="timeline-contenido">
                            <span class="timeline-etiqueta">Paso 1</span>
                            <div class="timeline-nombre">Elegir vuelo de ida</div>
                            <p class="timeline-detalle">Vuelo seleccionado.</p>
                        </div>
                    </div>

                    <div class="timeline-paso activo" id="pasoSoloIda2">
                        <span class="timeline-punto"></span>
                        <div class="timeline-contenido">
                            <span class="timeline-etiqueta">Paso 2</span>
                            <div class="timeline-nombre">Completar la reserva</div>
                            <p class="timeline-detalle">
                                Ingresá los datos de los pasajeros y confirmá la reserva.
                            </p>
                        </div>
                    </div>

                    <div class="timeline-paso" id="pasoSoloIda3">
                        <span class="timeline-punto"></span>
                        <div class="timeline-contenido">
                            <span class="timeline-etiqueta">Paso 3</span>
                            <div class="timeline-nombre">Finalizar en Mis Reservas</div>
                            <p class="timeline-detalle">
                                Después de reservar, seguí a Mis Reservas para terminar.
                            </p>
                        </div>
                    </div>
                </div>
            </div>

        <?php elseif ($tipoViaje === "idaVuelta"): ?>

            <div class="progreso-reserva progreso-ida-vuelta mb-5">
                <div class="progreso-titulo">
                    <h2 class="meta-valor">Obtener vuelo</h2>
                </div>

                <div class="timeline-pasos cinco-pasos">

                    <div class="timeline-paso completado" id="pasoIdaVuelta1">
                        <span class="timeline-punto"></span>
                        <div class="timeline-contenido">
                            <span class="timeline-etiqueta">Paso 1</span>
                            <div class="timeline-nombre">Elegir vuelo de ida</div>
                            <p class="timeline-detalle">Vuelo de ida seleccionado.</p>
                        </div>
                    </div>

                    <div
                        class="timeline-paso <?php echo $tramo === 'ida' ? 'activo' : 'completado'; ?>"
                        id="pasoIdaVuelta2"
                    >
                        <span class="timeline-punto"></span>
                        <div class="timeline-contenido">
                            <span class="timeline-etiqueta">Paso 2</span>
                            <div class="timeline-nombre">Completar reserva de ida</div>
                            <p class="timeline-detalle">
                                Cargá los pasajeros y reservá la ida.
                            </p>
                        </div>
                    </div>

                    <div
                        class="timeline-paso <?php echo $tramo === 'vuelta' ? 'completado' : ''; ?>"
                        id="pasoIdaVuelta3"
                    >
                        <span class="timeline-punto"></span>
                        <div class="timeline-contenido">
                            <span class="timeline-etiqueta">Paso 3</span>
                            <div class="timeline-nombre">Elegir vuelo de vuelta</div>
                            <p class="timeline-detalle">
                                Seleccioná el vuelo de regreso.
                            </p>
                        </div>
                    </div>

                    <div
                        class="timeline-paso <?php echo $tramo === 'vuelta' ? 'activo' : ''; ?>"
                        id="pasoIdaVuelta4"
                    >
                        <span class="timeline-punto"></span>
                        <div class="timeline-contenido">
                            <span class="timeline-etiqueta">Paso 4</span>
                            <div class="timeline-nombre">Completar reserva de vuelta</div>
                            <p class="timeline-detalle">
                                Cargá los pasajeros y reservá la vuelta.
                            </p>
                        </div>
                    </div>

                    <div class="timeline-paso" id="pasoIdaVuelta5">
                        <span class="timeline-punto"></span>
                        <div class="timeline-contenido">
                            <span class="timeline-etiqueta">Paso 5</span>
                            <div class="timeline-nombre">Finalizar en Mis Reservas</div>
                            <p class="timeline-detalle">
                                Confirmá las reservas para completar el viaje.
                            </p>
                        </div>
                    </div>

                </div>
            </div>

        <?php endif; ?>


        <h1 class="titulo-vuelo-elegido">
            Confirmá tu vuelo
        </h1>


        <!-- DATOS DEL VUELO -->

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

                                <i class="bi bi-arrow-right flecha-vuelo"></i>

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


        <!-- FORMULARIO -->

        <div class="contenedor-reserva">

            <!-- PASO 1: CANTIDAD DE PASAJEROS -->
            <div class="tarjeta-reserva" id="seleccionPasajeros">
                <h2>Pasajeros</h2>

                <div class="fila-pasajeros">

                    <div class="campo-pasajero">
                        <label for="adultos">Adultos</label>
                        <input
                            type="number"
                            id="adultos"
                            min="1"
                            max="10"
                            value="1"
                        >
                        <small>Mayores de 12 años</small>
                    </div>

                    <div class="campo-pasajero">
                        <label for="menores">Menores</label>
                        <input
                            type="number"
                            id="menores"
                            min="0"
                            max="9"
                            value="0"
                        >
                        <small>Menores de 12 años</small>
                    </div>

                </div>

                <p id="errorPasajeros" class="mensaje-error"></p>

                <button
                    type="button"
                    class="boton-principal"
                    onclick="crearFormulariosPasajeros()"
                >
                    Siguiente
                </button>
            </div>


            <!-- PASO 2: DATOS DE LOS PASAJEROS -->
            <div
                class="tarjeta-reserva"
                id="datosPasajeros"
                style="display: none;"
            >
                <h2>Datos de los pasajeros</h2>

                <form id="formPasajeros">

                    <div id="formulariosPasajeros"></div>

                    <button
                        type="button"
                        class="boton-principal"
                        onclick="mostrarResumen()"
                    >
                        Continuar al pago
                    </button>

                </form>
            </div>


            <!-- PASO 3: RESUMEN DE LA RESERVA -->
            <div
                class="tarjeta-reserva"
                id="resumenReserva"
                style="display: none;"
            >

                <h2>Datos de la reserva</h2>

                <div class="detalle-precio">
                    <span>Precio por pasajero</span>

                    <strong>
                        $<?= number_format($vuelo["precioVuelo"], 0, ',', '.') ?>
                    </strong>
                </div>

                <div class="detalle-precio">
                    <span>Adultos</span>
                    <strong id="resumenAdultos"></strong>
                </div>

                <div class="detalle-precio">
                    <span>Menores</span>
                    <strong id="resumenMenores"></strong>
                </div>

                <div class="detalle-precio">
                    <span>Total de pasajeros</span>
                    <strong id="resumenPasajeros"></strong>
                </div>

                <hr>

                <div class="detalle-precio total">
                    <span>Precio total</span>
                    <strong id="precioTotal"></strong>
                </div>

                <button
                    type="button"
                    id="btnReservar"
                    class="boton-pagar"
                    onclick="reservar()"
                >
                    Reservar
                </button>
                <div
                    id="mensajeReserva"
                    class="mensaje-reserva"
                    style="display: none;"
                >

                    <?php if ($tipoViaje === "idaVuelta" && $tramo === "ida") { ?>

                        <a
                            id="linkElegirVuelta"
                            href="resultadosVuelos.php?tipoViaje=idaVuelta&origen=<?php echo urlencode($origen); ?>&destino=<?php echo urlencode($destino); ?>&fechaIda=<?php echo urlencode($fechaIda); ?>&fechaVuelta=<?php echo urlencode($fechaVuelta); ?>&etapa=vuelta&reservaIda=<?php echo $reservaIda; ?>#vuelo-vuelta"
                            style="
                                display: inline-flex;
                                align-items: center;
                                gap: 6px;
                                font-size: 16px;
                                font-weight: 600;
                                color: #7a4a2e;
                                text-decoration: none;
                                margin-top: 10px;
                                margin-bottom: 15px;
                            "
                        >
                            <i class="bi bi-arrow-left"></i>
                            Elegí tu vuelo de vuelta
                        </a>

                    <?php } ?>

                    <p>
                        Selecciona el siguiente link para terminá de pagar tu vuelo en:
                        <a
                            id="linkMisReservas"
                            href="usuario/reservas/gestionReservas.php"
                        >
                            Mis Reservas
                        </a>
                    </p>

                </div>

            </div>

        </div>
    </div>

</main>

    <script>



const precioTotal =
    document.getElementById("precioTotal");

const precioPorPasajero =
    <?php echo (float) $vuelo["precioVuelo"]; ?>;



function validarPago() {

    const usuarioRegistrado =
        <?php
        echo (
            isset($_SESSION["tipoUsuario"])
            && $_SESSION["tipoUsuario"] === "usuario"
        ) ? "true" : "false";
        ?>;

    if (usuarioRegistrado) {

        document.querySelector("form").submit();
        return;

    }

    document.getElementById("modalRegistro").style.display = "flex";
}


function cerrarModalRegistro() {

    document.getElementById("modalRegistro").style.display = "none";

}


function irAlRegistro() {

    const adultos =
        document.getElementById("adultos").value;

    const menores =
        document.getElementById("menores").value;

    const formulario =
        document.getElementById("formPasajeros");

    const datosFormulario =
        new FormData(formulario);

    datosFormulario.append(
        "codVuelo",
        "<?php echo $vuelo['codVuelo']; ?>"
    );

    datosFormulario.append(
        "adultos",
        adultos
    );

    datosFormulario.append(
        "menores",
        menores
    );

    // También conservamos los datos de la búsqueda ida/vuelta.
    datosFormulario.append("tipoViaje", <?= json_encode($tipoViaje) ?>);
    datosFormulario.append("origen", <?= json_encode($origen) ?>);
    datosFormulario.append("destino", <?= json_encode($destino) ?>);
    datosFormulario.append("fechaIda", <?= json_encode($fechaIda) ?>);
    datosFormulario.append("fechaVuelta", <?= json_encode($fechaVuelta) ?>);
    datosFormulario.append("tramo", <?= json_encode($tramo) ?>);

    fetch(
        "php/guardarReservaTemporal.php",
        {
            method: "POST",
            body: datosFormulario
        }
    )
    .then(respuesta => respuesta.text())
    .then(() => {

        window.location.href =
            "registro.php";

    })
    .catch(error => {

        console.error("Error al crear reserva:", error);

        alert(
            "El servidor devolvió una respuesta incorrecta. Mirá la consola."
        );

    });
}

</script>



<script src="js/bootstrap.bundle.min.js"></script>


    <div id="modalRegistro" class="modal-registro">

        <div class="modal-contenido">

            <div class="modal-icono">
                <i class="bi bi-person-circle"></i>
            </div>

            <h3>Necesitás registrarte</h3>

            <p>
                Para continuar con el pago tenés que crear una cuenta.
                No vas a perder los datos de tu vuelo ni los pasajeros ingresados.
            </p>

            <div class="modal-botones">

                <button
                    type="button"
                    class="btn-cancelar"
                    onclick="cerrarModalRegistro()"
                >
                    Cancelar
                </button>

                <button
                    type="button"
                    class="btn-ir-registro"
                    onclick="irAlRegistro()"
                >
                    Ir al registro
                </button>

            </div>
            <br>
            <a href="#" class="ya-tengo-cuenta" onclick="irAlInicioSesion(event)">
                Ya tengo cuenta
            </a>

        </div>

    </div>
<script>

const precioPasajero = <?= $vuelo["precioVuelo"] ?>;


function crearFormulariosPasajeros() {

    const adultos =
        parseInt(document.getElementById("adultos").value) || 0;

    const menores =
        parseInt(document.getElementById("menores").value) || 0;

    const total = adultos + menores;

    const error =
        document.getElementById("errorPasajeros");


    if (adultos < 1) {
        error.textContent =
            "Debe viajar al menos un adulto.";
        return;
    }


    if (total > 10) {
        error.textContent =
            "La reserva puede tener como máximo 10 pasajeros.";
        return;
    }


    error.textContent = "";


    const contenedor =
        document.getElementById("formulariosPasajeros");

    contenedor.innerHTML = "";


    /* ADULTOS */

    for (let i = 1; i <= adultos; i++) {

        contenedor.innerHTML += `
            <div class="pasajero-card">

                <h3>Adulto ${i}</h3>

                <div class="grid-datos">

                    <div>
                        <label>Nombre</label>
                        <input
                            type="text"
                            name="nombreAdulto[]"
                            required
                        >
                    </div>

                    <div>
                        <label>Apellido</label>
                        <input
                            type="text"
                            name="apellidoAdulto[]"
                            required
                        >
                    </div>

                    <div>
                        <label>Email</label>
                        <input
                            type="email"
                            name="emailAdulto[]"
                            required
                        >
                    </div>

                    <div>
                        <label>Fecha de nacimiento</label>
                        <input
                            type="date"
                            name="fechaAdulto[]"
                            required
                        >
                    </div>

                </div>

            </div>
        `;
    }


    /* MENORES */

    for (let i = 1; i <= menores; i++) {

        contenedor.innerHTML += `
            <div class="pasajero-card">

                <h3>Menor ${i}</h3>

                <div class="grid-datos">

                    <div>
                        <label>Nombre</label>
                        <input
                            type="text"
                            name="nombreMenor[]"
                            required
                        >
                    </div>

                    <div>
                        <label>Apellido</label>
                        <input
                            type="text"
                            name="apellidoMenor[]"
                            required
                        >
                    </div>

                    <div>
                        <label>Email</label>
                        <input
                            type="email"
                            name="emailMenor[]"
                            required
                        >
                    </div>

                    <div>
                        <label>Fecha de nacimiento</label>
                        <input
                            type="date"
                            name="fechaMenor[]"
                            required
                        >
                    </div>

                </div>

            </div>
        `;
    }


    // No permitimos fechas de nacimiento futuras.
    const hoy = new Date().toISOString().split("T")[0];

    document.querySelectorAll(
        'input[name="fechaAdulto[]"], input[name="fechaMenor[]"]'
    ).forEach(input => {
        input.max = hoy;

        // Limpiamos un posible error anterior cuando el usuario modifica la fecha.
        input.addEventListener("input", function () {
            this.setCustomValidity("");
        });
    });


    document.getElementById("datosPasajeros").style.display =
        "block";


    document.getElementById("resumenReserva").style.display =
        "none";


    // Esperamos a que el navegador muestre la sección antes de movernos.
    setTimeout(() => {
        document.getElementById("datosPasajeros")
            .scrollIntoView({
                behavior: "smooth",
                block: "start"
            });
    }, 50);
}




function calcularEdad(fechaNacimiento) {
    const hoy = new Date();

    // Agregamos T00:00:00 para evitar desplazamientos por zona horaria.
    const nacimiento = new Date(fechaNacimiento + "T00:00:00");

    if (Number.isNaN(nacimiento.getTime()) || nacimiento > hoy) {
        return null;
    }

    let edad = hoy.getFullYear() - nacimiento.getFullYear();

    const mes = hoy.getMonth() - nacimiento.getMonth();

    if (
        mes < 0 ||
        (mes === 0 && hoy.getDate() < nacimiento.getDate())
    ) {
        edad--;
    }

    return edad;
}


function validarEdadesPasajeros() {

    // Adultos: 12 años o más
    const fechasAdultos =
        document.querySelectorAll('input[name="fechaAdulto[]"]');

    for (let i = 0; i < fechasAdultos.length; i++) {

        const input = fechasAdultos[i];
        const edad = calcularEdad(input.value);

        if (edad === null) {
            input.setCustomValidity(
                "Ingresá una fecha de nacimiento válida."
            );
            input.reportValidity();
            input.focus();
            return false;
        }

        if (edad < 12) {
            input.setCustomValidity(
                "Este pasajero tiene " + edad +
                " años. Debe cargarse como menor."
            );
            input.reportValidity();
            input.focus();
            return false;
        }

        input.setCustomValidity("");
    }


    // Menores: menos de 12 años
    const fechasMenores =
        document.querySelectorAll('input[name="fechaMenor[]"]');

    for (let i = 0; i < fechasMenores.length; i++) {

        const input = fechasMenores[i];
        const edad = calcularEdad(input.value);

        if (edad === null) {
            input.setCustomValidity(
                "Ingresá una fecha de nacimiento válida."
            );
            input.reportValidity();
            input.focus();
            return false;
        }

        if (edad >= 12) {
            input.setCustomValidity(
                "Este pasajero tiene " + edad +
                " años. Debe cargarse como adulto."
            );
            input.reportValidity();
            input.focus();
            return false;
        }

        input.setCustomValidity("");
    }

    return true;
}


function mostrarResumen() {

    const formulario =
        document.getElementById("formPasajeros");


    if (!formulario.reportValidity()) {
        return;
    }

    // Verificamos que las fechas coincidan con la categoría elegida.
    if (!validarEdadesPasajeros()) {
        return;
    }


    const adultos =
        parseInt(document.getElementById("adultos").value);

    const menores =
        parseInt(document.getElementById("menores").value);

    const totalPasajeros =
        adultos + menores;

    const totalPrecio =
        totalPasajeros * precioPasajero;


    document.getElementById("resumenAdultos")
        .textContent = adultos;

    document.getElementById("resumenMenores")
        .textContent = menores;

    document.getElementById("resumenPasajeros")
        .textContent = totalPasajeros;


    document.getElementById("precioTotal")
        .textContent =
        "$" + totalPrecio.toLocaleString("es-AR");


    document.getElementById("resumenReserva")
        .style.display = "block";


    // Esperamos a que el resumen quede visible antes de hacer scroll.
    setTimeout(() => {
        document.getElementById("resumenReserva")
            .scrollIntoView({
                behavior: "smooth",
                block: "start"
            });
    }, 50);
}

function irAlInicioSesion(event) {

    event.preventDefault();

    const adultos =
        document.getElementById("adultos").value;

    const menores =
        document.getElementById("menores").value;

    const formulario =
        document.getElementById("formPasajeros");

    const datosFormulario =
        new FormData(formulario);

    datosFormulario.append(
        "codVuelo",
        "<?php echo $vuelo['codVuelo']; ?>"
    );

    datosFormulario.append("adultos", adultos);
    datosFormulario.append("menores", menores);

    datosFormulario.append("tipoViaje", <?= json_encode($tipoViaje) ?>);
    datosFormulario.append("origen", <?= json_encode($origen) ?>);
    datosFormulario.append("destino", <?= json_encode($destino) ?>);
    datosFormulario.append("fechaIda", <?= json_encode($fechaIda) ?>);
    datosFormulario.append("fechaVuelta", <?= json_encode($fechaVuelta) ?>);
    datosFormulario.append("tramo", <?= json_encode($tramo) ?>);

    fetch(
        "php/guardarReservaTemporal.php",
        {
            method: "POST",
            body: datosFormulario
        }
    )
    .then(respuesta => respuesta.text())
    .then(() => {

        window.location.href = "inicioSesion.php";

    })
    .catch(error => {

        console.error(error);

    });

}



///////////////////////////////

        //reconstruccion del formulario

//////////////////////////////
<?php

$restaurarReserva = false;

if (
    isset($_SESSION["restaurarReservaTemporal"])
    && $_SESSION["restaurarReservaTemporal"] === true
    && isset($_SESSION["reservaTemporal"])
) {

    $restaurarReserva = true;

    // Se usa una sola vez
    unset($_SESSION["restaurarReservaTemporal"]);
}

?>

const reservaTemporal =
    <?= $restaurarReserva
        ? json_encode(
            $_SESSION["reservaTemporal"],
            JSON_UNESCAPED_UNICODE
        )
        : "null";
    ?>;

/*
    SI NO HAY RESERVA TEMPORAL:
    empezar siempre desde cero
*/

if (!reservaTemporal) {

    document.getElementById("adultos").value = 1;
    document.getElementById("menores").value = 0;

    document.getElementById(
        "formulariosPasajeros"
    ).innerHTML = "";

    document.getElementById(
        "datosPasajeros"
    ).style.display = "none";

    document.getElementById(
        "resumenReserva"
    ).style.display = "none";

}


/*
    SOLO reconstruimos el formulario
    si realmente existe una reserva temporal
*/

if (reservaTemporal) {

    document.getElementById("adultos").value =
        reservaTemporal.adultos;

    document.getElementById("menores").value =
        reservaTemporal.menores;


    crearFormulariosPasajeros();


    /* ADULTOS */

    const nombresAdultos =
        document.querySelectorAll(
            'input[name="nombreAdulto[]"]'
        );

    const apellidosAdultos =
        document.querySelectorAll(
            'input[name="apellidoAdulto[]"]'
        );

    const emailsAdultos =
        document.querySelectorAll(
            'input[name="emailAdulto[]"]'
        );

    const fechasAdultos =
        document.querySelectorAll(
            'input[name="fechaAdulto[]"]'
        );


    nombresAdultos.forEach((input, i) => {

        input.value =
            reservaTemporal.nombreAdulto?.[i] ?? "";

        apellidosAdultos[i].value =
            reservaTemporal.apellidoAdulto?.[i] ?? "";

        emailsAdultos[i].value =
            reservaTemporal.emailAdulto?.[i] ?? "";

        fechasAdultos[i].value =
            reservaTemporal.fechaAdulto?.[i] ?? "";

    });


    /* MENORES */

    const nombresMenores =
        document.querySelectorAll(
            'input[name="nombreMenor[]"]'
        );

    const apellidosMenores =
        document.querySelectorAll(
            'input[name="apellidoMenor[]"]'
        );

    const emailsMenores =
        document.querySelectorAll(
            'input[name="emailMenor[]"]'
        );

    const fechasMenores =
        document.querySelectorAll(
            'input[name="fechaMenor[]"]'
        );


    nombresMenores.forEach((input, i) => {

        input.value =
            reservaTemporal.nombreMenor?.[i] ?? "";

        apellidosMenores[i].value =
            reservaTemporal.apellidoMenor?.[i] ?? "";

        emailsMenores[i].value =
            reservaTemporal.emailMenor?.[i] ?? "";

        fechasMenores[i].value =
            reservaTemporal.fechaMenor?.[i] ?? "";

    });


    mostrarResumen();

}
    
/////////////////////////////

        // RESERVAR

////////////////////////////

function reservar() {

    const usuarioRegistrado =
        <?php
        echo (
            isset($_SESSION["codUsuario"])
            && $_SESSION["tipoUsuario"] === "usuario"
        ) ? "true" : "false";
        ?>;


    // Si NO inició sesión
    if (!usuarioRegistrado) {

        document.getElementById("modalRegistro")
            .style.display = "flex";

        return;
    }


    // Obtener cantidades
    const adultos =
        document.getElementById("adultos").value;

    const menores =
        document.getElementById("menores").value;


    // Obtener formulario con los datos de pasajeros
    const formulario =
        document.getElementById("formPasajeros");

    const datosFormulario =
        new FormData(formulario);


    // Agregar datos generales de la reserva
    datosFormulario.append(
        "codVuelo",
        "<?php echo $vuelo['codVuelo']; ?>"
    );

    datosFormulario.append(
        "adultos",
        adultos
    );

    datosFormulario.append(
        "menores",
        menores
    );

    // También conservamos los datos de la búsqueda ida/vuelta.
    datosFormulario.append("tipoViaje", <?= json_encode($tipoViaje) ?>);
    datosFormulario.append("origen", <?= json_encode($origen) ?>);
    datosFormulario.append("destino", <?= json_encode($destino) ?>);
    datosFormulario.append("fechaIda", <?= json_encode($fechaIda) ?>);
    datosFormulario.append("fechaVuelta", <?= json_encode($fechaVuelta) ?>);
    datosFormulario.append("tramo", <?= json_encode($tramo) ?>);


    // Primero guardamos los datos en la sesión
    fetch(
        "php/guardarReservaTemporal.php",
        {
            method: "POST",
            body: datosFormulario
        }
    )

    .then(respuesta => respuesta.text())

    .then(texto => {

        console.log(
            "Reserva temporal guardada:",
            texto
        );


        // Después creamos la reserva definitiva
        return fetch(
            "usuario/reservas/crearReserva.php",
            {
                method: "POST"
            }
        );

    })

    .then(respuesta => respuesta.text())

    .then(texto => {

        console.log(
            "RESPUESTA COMPLETA:",
            texto
        );


        try {

            const datos =
                JSON.parse(texto);


            if (datos.ok) {

                const linkMisReservas =
                    document.getElementById("linkMisReservas");

                if (linkMisReservas && datos.codReserva) {

                    if (<?= json_encode($tipoViaje) ?> === "idaVuelta") {

                        if (<?= json_encode($tramo) ?> === "vuelta") {
                            linkMisReservas.href =
                                "usuario/reservas/gestionReservas.php" +
                                "?desdeReserva=1" +
                                "&tipoViaje=idaVuelta" +
                                "&tramo=vuelta" +
                                "&reservaIda=" + encodeURIComponent(<?= (int) $reservaIda ?>) +
                                "&reserva=" + encodeURIComponent(datos.codReserva);
                        } else {
                            linkMisReservas.href =
                                "usuario/reservas/gestionReservas.php" +
                                "?desdeReserva=1" +
                                "&tipoViaje=idaVuelta" +
                                "&tramo=ida" +
                                "&reserva=" + encodeURIComponent(datos.codReserva);
                        }

                    } else {
                        linkMisReservas.href =
                            "usuario/reservas/gestionReservas.php" +
                            "?desdeReserva=1" +
                            "&tipoViaje=soloIda" +
                            "&reserva=" + encodeURIComponent(datos.codReserva);
                    }
                }

                const linkElegirVuelta =
                    document.getElementById("linkElegirVuelta");

                if (
                    linkElegirVuelta &&
                    datos.codReserva &&
                    <?= json_encode($tipoViaje) ?> === "idaVuelta" &&
                    <?= json_encode($tramo) ?> === "ida"
                ) {
                    const urlVuelta =
                        new URL(linkElegirVuelta.href, window.location.origin);

                    urlVuelta.searchParams.set(
                        "reservaIda",
                        datos.codReserva
                    );

                    urlVuelta.searchParams.set(
                        "etapa",
                        "vuelta"
                    );

                    linkElegirVuelta.href =
                        urlVuelta.pathname +
                        urlVuelta.search +
                        "#vuelo-vuelta";
                }

                const boton =
                    document.getElementById("btnReservar");

                boton.textContent = "Reservado";
                boton.disabled = true;
                boton.classList.add("reservado");

                document.getElementById("mensajeReserva")
                    .style.display = "block";

                const paso1 = document.getElementById("pasoSoloIda1");
                const paso2 = document.getElementById("pasoSoloIda2");
                const paso3 = document.getElementById("pasoSoloIda3");

                if (paso1 && paso2 && paso3) {
                    paso1.classList.add("completado");
                    paso1.classList.remove("activo");

                    paso2.classList.add("completado");
                    paso2.classList.remove("activo");

                    paso3.classList.add("activo");
                }

                const pasoIV1 = document.getElementById("pasoIdaVuelta1");
                const pasoIV2 = document.getElementById("pasoIdaVuelta2");
                const pasoIV3 = document.getElementById("pasoIdaVuelta3");
                const pasoIV4 = document.getElementById("pasoIdaVuelta4");
                const pasoIV5 = document.getElementById("pasoIdaVuelta5");

                if (
                    pasoIV1 &&
                    pasoIV2 &&
                    pasoIV3 &&
                    pasoIV4 &&
                    pasoIV5
                ) {

                    if (<?= json_encode($tramo) ?> === "ida") {

                        pasoIV2.classList.add("completado");
                        pasoIV2.classList.remove("activo");

                        pasoIV3.classList.add("activo");

                    } else {

                        pasoIV4.classList.add("completado");
                        pasoIV4.classList.remove("activo");

                        pasoIV5.classList.add("activo");
                    }
                }


                // REINICIAR DATOS DE PASAJEROS

                document.getElementById("adultos").value = 1;
                document.getElementById("menores").value = 0;

                const formulario =
                    document.getElementById("formPasajeros");

                formulario.reset();

                document.getElementById(
                    "formulariosPasajeros"
                ).innerHTML = "";

                document.getElementById(
                    "datosPasajeros"
                ).style.display = "none";


                // Una vez que se ocultaron los formularios y el contenido
                // terminó de acomodarse, llevamos al usuario al mensaje final.
                setTimeout(() => {
                    document.getElementById(
                        "resumenReserva"
                    ).scrollIntoView({
                        behavior: "smooth",
                        block: "center"
                    });
                }, 100);

            } else {
                alert("No se pudo completar la reserva: " + datos.mensaje);
            }


        } catch (error) {

            alert(
                "Error del servidor:\n\n"
                + texto
            );

        }

    })

    .catch(error => {

        console.error(
            "Error al reservar:",
            error
        );

        alert(
            "No se pudo comunicar con el servidor."
        );

    });

}
</script>

</body>

</html>