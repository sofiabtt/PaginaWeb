<?php

require "../includes/protegerUsuario.php";

include "../../php/conexionBD.php";


if (!isset($_GET["id"])) {

    header("Location: gestionReservas.php");
    exit();

}


$codReserva =
    (int) $_GET["id"];

$codUsuario =
    (int) $_SESSION["codUsuario"];



$consulta = $conexion->prepare(
    "SELECT
        r.codReserva,
        r.fechaReserva,
        r.estadoReserva,
        r.precioFinalReserva,

        v.codVuelo,
        v.origenVuelo,
        v.destinoVuelo,
        v.fechaSalidaVuelo,
        v.horaSalidaVuelo,

        a.nombreAerolinea

    FROM Reservas r

    INNER JOIN Vuelos v
        ON v.codVuelo = r.codVuelo

    INNER JOIN Aerolineas a
        ON a.codAerolinea = v.codAerolinea

    WHERE r.codReserva = ?
    AND r.codUsuario = ?"
);


$consulta->bind_param(
    "ii",
    $codReserva,
    $codUsuario
);


$consulta->execute();


$resultado =
    $consulta->get_result();


$reserva =
    $resultado->fetch_assoc();



if (!$reserva) {

    header("Location: gestionReservas.php");
    exit();

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

    <title>Nuvia - Detalle de reserva</title>


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


<?php

include "../includes/navbarUsuario.php";

?>


<main class="contenido-admin">


    <section class="encabezado-contenido">

        <div>

            <h1>
                Detalle de la reserva
            </h1>

            <p>
                Consultá toda la información de tu reserva.
            </p>

        </div>


        <a
            href="gestionReservas.php"
            class="btn btn-outline-secondary"
        >

            <i class="bi bi-arrow-left"></i>

            Volver

        </a>

    </section>



    <section class="detalle-reserva-card">


        <div class="detalle-reserva-encabezado">

            <div>

                <span class="detalle-reserva-label">
                    Reserva
                </span>

                <h2>
                    #<?php
                    echo (int) $reserva["codReserva"];
                    ?>
                </h2>

            </div>


            <span
                class="badge <?php
                echo $reserva["estadoReserva"] === "confirmada"
                    ? "bg-success"
                    : "bg-warning text-dark";
                ?>"
            >

                <?php

                echo htmlspecialchars(
                    ucfirst(
                        $reserva["estadoReserva"]
                    )
                );

                ?>

            </span>

        </div>



        <hr>



        <div class="detalle-reserva-grid">


            <div class="detalle-item">

                <span>Aerolínea</span>

                <strong>

                    <?php

                    echo htmlspecialchars(
                        $reserva["nombreAerolinea"]
                    );

                    ?>

                </strong>

            </div>



            <div class="detalle-item">

                <span>Código de vuelo</span>

                <strong>

                    <?php

                    echo (int) $reserva["codVuelo"];

                    ?>

                </strong>

            </div>



            <div class="detalle-item">

                <span>Origen</span>

                <strong>

                    <?php

                    echo htmlspecialchars(
                        $reserva["origenVuelo"]
                    );

                    ?>

                </strong>

            </div>



            <div class="detalle-item">

                <span>Destino</span>

                <strong>

                    <?php

                    echo htmlspecialchars(
                        $reserva["destinoVuelo"]
                    );

                    ?>

                </strong>

            </div>



            <div class="detalle-item">

                <span>Fecha de salida</span>

                <strong>

                    <?php

                    echo date(
                        "d/m/Y",
                        strtotime(
                            $reserva["fechaSalidaVuelo"]
                        )
                    );

                    ?>

                </strong>

            </div>



            <div class="detalle-item">

                <span>Hora de salida</span>

                <strong>

                    <?php

                    echo htmlspecialchars(
                        $reserva["horaSalidaVuelo"]
                    );

                    ?>

                </strong>

            </div>



            <div class="detalle-item">

                <span>Fecha de reserva</span>

                <strong>

                    <?php

                    echo date(
                        "d/m/Y H:i",
                        strtotime(
                            $reserva["fechaReserva"]
                        )
                    );

                    ?>

                </strong>

            </div>



            <div class="detalle-item">

                <span>Precio total</span>

                <strong class="precio-detalle">

                    $

                    <?php

                    echo number_format(
                        (float)
                        $reserva["precioFinalReserva"],
                        0,
                        ",",
                        "."
                    );

                    ?>

                </strong>

            </div>


        </div>


    </section>


</main>



<script src="../../js/bootstrap.bundle.min.js"></script>


</body>

</html>


<?php

$consulta->close();
$conexion->close();

?>