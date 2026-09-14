<?php

session_start();

ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);


// VERIFICAR CEO

if (
    !isset($_SESSION["tipoUsuario"]) ||
    $_SESSION["tipoUsuario"] != "ceo"
) {
    header("Location: ../../inicioSesion.php");
    exit();
}


// CONEXIÓN

include "../../php/conexionBD.php";


// IDENTIFICAR CEO

if (!isset($_SESSION["codUsuario"])) {
    echo "No se pudo identificar al CEO.";
    exit();
}

$codUsuario = $_SESSION["codUsuario"];


// OBTENER AEROLÍNEA

$consultaAerolinea = $conexion->prepare("
    SELECT codAerolinea
    FROM Aerolineas
    WHERE codUsuario = ?
");

$consultaAerolinea->bind_param(
    "i",
    $codUsuario
);

$consultaAerolinea->execute();

$resultadoAerolinea =
    $consultaAerolinea->get_result();

if ($resultadoAerolinea->num_rows != 1) {
    echo "El CEO no tiene una aerolínea asignada.";
    exit();
}

$aerolinea =
    $resultadoAerolinea->fetch_assoc();

$codAerolinea =
    $aerolinea["codAerolinea"];

$consultaAerolinea->close();


// OBTENER ID DEL VUELO

if (!isset($_GET["id"])) {
    echo "Vuelo no especificado.";
    exit();
}

$codVuelo = intval($_GET["id"]);


// BUSCAR VUELO

$consulta = $conexion->prepare("
    SELECT
        codVuelo,
        origenVuelo,
        destinoVuelo,
        fechaSalidaVuelo,
        horaSalidaVuelo

    FROM Vuelos

    WHERE codVuelo = ?
      AND codAerolinea = ?
      AND activo = 1
");

$consulta->bind_param(
    "ii",
    $codVuelo,
    $codAerolinea
);

$consulta->execute();

$resultado = $consulta->get_result();

if ($resultado->num_rows != 1) {
    echo "El vuelo no existe o no pertenece a tu aerolínea.";
    exit();
}

$vuelo = $resultado->fetch_assoc();

$consulta->close();


// CONFIRMAR ELIMINACIÓN

if ($_SERVER["REQUEST_METHOD"] == "POST") {

    $eliminar = $conexion->prepare("
        UPDATE Vuelos

        SET
            activoVuelo = 0,
            fechaEliminacionVuelo = NOW()

        WHERE codVuelo = ?
          AND codAerolinea = ?
    ");

    $eliminar->bind_param(
        "ii",
        $codVuelo,
        $codAerolinea
    );

    if ($eliminar->execute()) {

        $eliminar->close();

        header(
            "Location: gestionVuelos.php?mensaje=eliminado"
        );

        exit();

    } else {

        $error =
            "No se pudo eliminar el vuelo.";

    }

    $eliminar->close();

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