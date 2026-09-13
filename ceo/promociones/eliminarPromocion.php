<?php

session_start();

ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);


// =========================
// VERIFICAR QUE SEA CEO
// =========================

if (
    !isset($_SESSION["tipoUsuario"]) ||
    $_SESSION["tipoUsuario"] != "ceo"
) {

    header("Location: ../../inicioSesion.php");
    exit();

}


// =========================
// CONEXIÓN
// =========================

include "../../php/conexionBD.php";


// =========================
// IDENTIFICAR AL CEO
// =========================

if (!isset($_SESSION["codUsuario"])) {

    echo "No se pudo identificar al CEO.";
    exit();

}

$codUsuario = $_SESSION["codUsuario"];


// =========================
// OBTENER SU AEROLÍNEA
// =========================

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


// =========================
// OBTENER ID
// =========================

if (!isset($_GET["id"])) {

    echo "Promoción no especificada.";
    exit();

}

$codPromocion = intval($_GET["id"]);


// =========================
// BUSCAR PROMOCIÓN
// =========================

$consulta = $conexion->prepare("
    SELECT
        descripcionPromocion,
        descuentoPromocion

    FROM Promociones

    WHERE codPromocion = ?
      AND codAerolinea = ?
      AND activoPromocion = 1
");

$consulta->bind_param(
    "ii",
    $codPromocion,
    $codAerolinea
);

$consulta->execute();

$resultado = $consulta->get_result();


if ($resultado->num_rows != 1) {

    echo "La promoción no existe o no pertenece a tu aerolínea.";
    exit();

}

$promocion = $resultado->fetch_assoc();

$consulta->close();


// =========================
// CONFIRMAR ELIMINACIÓN
// =========================

if ($_SERVER["REQUEST_METHOD"] == "POST") {


    $eliminar = $conexion->prepare("
        UPDATE Promociones

        SET
            activoPromocion = 0,
            fechaEliminacionPromocion = NOW()

        WHERE codPromocion = ?
          AND codAerolinea = ?
    ");


    $eliminar->bind_param(
        "ii",
        $codPromocion,
        $codAerolinea
    );


    if ($eliminar->execute()) {

        $eliminar->close();

        header(
            "Location: gestionPromocion.php?mensaje=eliminada"
        );

        exit();

    } else {

        $error =
            "No se pudo eliminar la promoción.";

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

    <title>
        Nuvia - Eliminar promoción
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
                Eliminar promoción
            </h1>

            <p>
                Confirma la eliminación de la promoción.
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


            <div class="alert alert-warning">

                ¿Seguro que querés eliminar esta promoción?

            </div>


            <p>

                <strong>
                    Descripción:
                </strong>

                <?php
                echo htmlspecialchars(
                    $promocion["descripcionPromocion"]
                );
                ?>

            </p>


            <p>

                <strong>
                    Descuento:
                </strong>

                <?php
                echo $promocion["descuentoPromocion"];
                ?>%

            </p>


            <form method="POST">


                <div class="perfil-acciones">


                    <a
                        href="gestionPromocion.php"
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


    <script
        src="../../js/bootstrap.bundle.min.js"
    ></script>


</body>

</html>


<?php

$conexion->close();

?>