
<?php

session_start();

include "php/conexionBD.php";

if (!isset($_GET["codVuelo"])) {
    die("No se seleccionó ningún vuelo.");
}

$codVuelo = (int) $_GET["codVuelo"];

$consulta = $conexion->prepare("
    SELECT
        V.codVuelo,
        V.origenVuelo,
        V.destinoVuelo,
        V.fechaSalidaVuelo,
        V.horaSalidaVuelo,
        V.precioVuelo,
        V.asientosDisponibles,
        A.nombreAerolinea
    FROM Vuelos V
    INNER JOIN Aerolineas A
        ON V.codAerolinea = A.codAerolinea
    WHERE V.codVuelo = ?
    AND V.activoVuelo = 1
");

$consulta->bind_param("i", $codVuelo);

$consulta->execute();

$resultado = $consulta->get_result();

$vuelo = $resultado->fetch_assoc();

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
        href="css/estilosVueloElegido.css"
    >

</head>


<body>


<?php include "includes/navbar.php"; ?>


<main class="contenedor-vuelo-elegido">

    <div class="container">

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

        <div class="card tarjeta-formulario mt-4">

            <div class="card-body">

                <h3 class="mb-4">
                    Datos de la reserva
                </h3>


                <form
                    action="pago.php"
                    method="POST"
                >

                    <input
                        type="hidden"
                        name="codVuelo"
                        value="<?php echo $vuelo["codVuelo"]; ?>"
                    >


                    <!-- PRECIO POR PASAJERO -->

                    <div class="dato-reserva">

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


                    <!-- CANTIDAD -->

                    <div class="mb-4">

                        <label
                            for="cantidadPasajes"
                            class="form-label fw-bold"
                        >
                            Cantidad de pasajeros
                        </label>


                        <input
                            type="number"
                            class="form-control"
                            id="cantidadPasajes"
                            name="cantidadPasajes"
                            min="1"
                            max="10"
                            value="1"
                            required
                        >

                    </div>


                    <!-- TOTAL -->

                    <div class="dato-reserva total-reserva">

                        <span>
                            Precio total
                        </span>

                        <strong id="precioTotal">

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


                    <button
                        type="button"
                        class="btn btn-pagar mt-4"
                        onclick="validarPago()"
                    >
                        Pagar
                    </button>

                </form>

            </div>

        </div>
    </div>

</main>

    <script>

const cantidadPasajes =
    document.getElementById("cantidadPasajes");

const precioTotal =
    document.getElementById("precioTotal");

const precioPorPasajero =
    <?php echo (float) $vuelo["precioVuelo"]; ?>;


cantidadPasajes.addEventListener(
    "input",
    function() {

        let cantidad = parseInt(this.value);

        if (cantidad < 1) {
            cantidad = 1;
        }

        if (cantidad > 10) {
            cantidad = 10;
        }

        const total =
            precioPorPasajero * cantidad;

        precioTotal.textContent =
            "$ " +
            total.toLocaleString("es-AR");

    }
);


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

    const cantidad =
        document.getElementById("cantidadPasajes").value;

    window.location.href =
        "php/guardarReservaTemporal.php"
        + "?codVuelo=<?php echo $vuelo['codVuelo']; ?>"
        + "&cantidadPasajes=" + cantidad;

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
            No vas a perder los datos de tu vuelo ni la cantidad de pasajeros seleccionada.
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

    </div>

</div>

</body>

</html>