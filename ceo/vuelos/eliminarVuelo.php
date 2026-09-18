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

$codAerolinea = $aerolinea["codAerolinea"];

$codVuelo = obtenerCodVuelo();


// BUSCAR VUELO

$vuelo = obtenerVuelo($conexion,$codVuelo,$codAerolinea);

if (!$vuelo || $vuelo["activoVuelo"] != 1) {

    echo "El vuelo no existe o no pertenece a tu aerolínea.";
    exit();

}

// CONFIRMAR ELIMINACIÓN

if ($_SERVER["REQUEST_METHOD"] == "POST") {

    if (eliminarVuelo($conexion,$codVuelo,$codAerolinea)) {

        header("Location: gestionVuelos.php?mensaje=eliminado");
        exit();

    } else {

        $error = "No se pudo eliminar el vuelo.";

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

    <title>Nuvia - Eliminar vuelo</title>

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
            Eliminar vuelo
        </h1>

        <p>
            Confirma la eliminación del vuelo.
        </p>

    </section>

    <section class="perfil-card">

        <?php if (isset($error)) { ?>

            <div class="alert alert-danger">
                <?php echo htmlspecialchars($error); ?>
            </div>

        <?php } ?>

        <div class="alert alert-warning">
            ¿Seguro que querés eliminar este vuelo?
        </div>

        <p>
            <strong>Origen:</strong>
            <?php
            echo htmlspecialchars(
                $vuelo["origenVuelo"]
            );
            ?>
        </p>

        <p>
            <strong>Destino:</strong>
            <?php
            echo htmlspecialchars(
                $vuelo["destinoVuelo"]
            );
            ?>
        </p>

        <p>
            <strong>Fecha:</strong>
            <?php
            echo htmlspecialchars(
                $vuelo["fechaSalidaVuelo"]
            );
            ?>
        </p>

        <p>
            <strong>Hora:</strong>
            <?php
            echo htmlspecialchars(
                $vuelo["horaSalidaVuelo"]
            );
            ?>
        </p>

        <form method="POST">

            <div class="perfil-acciones">

                <a
                    href="gestionVuelos.php"
                    class="btn btn-outline-secondary me-2"
                >
                    Cancelar
                </a>

                <button
                    type="submit"
                    class="btn btn-danger"
                >
                    <i class="bi bi-trash"></i>
                    Eliminar
                </button>

            </div>

        </form>

    </section>

</main>

<script src="../../js/bootstrap.bundle.min.js"></script>

</body>
</html>

<?php

$conexion->close();

?>