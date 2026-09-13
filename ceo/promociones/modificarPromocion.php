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
// OBTENER ID DE PROMOCIÓN
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
        codPromocion,
        descripcionPromocion,
        descuentoPromocion,
        estadoPromocion

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
// PROCESAR MODIFICACIÓN
// =========================

if ($_SERVER["REQUEST_METHOD"] == "POST") {

    $descripcion =
        trim($_POST["descripcion"]);

    $descuento =
        $_POST["descuento"];


    if (
        empty($descripcion) ||
        empty($descuento)
    ) {

        $error = "Debe completar todos los campos.";

    } elseif ($descuento <= 0) {

        $error = "El descuento debe ser mayor a 0.";

    } elseif ($descuento > 100) {

        $error = "El descuento no puede ser mayor a 100%.";

    } else {


        $actualizar = $conexion->prepare("
            UPDATE Promociones

            SET
                descripcionPromocion = ?,
                descuentoPromocion = ?,
                estadoPromocion = 'Pendiente'

            WHERE codPromocion = ?
              AND codAerolinea = ?
        ");


        $actualizar->bind_param(
            "sdii",
            $descripcion,
            $descuento,
            $codPromocion,
            $codAerolinea
        );


        if ($actualizar->execute()) {

            $actualizar->close();

            header(
                "Location: gestionPromocion.php?mensaje=modificada"
            );

            exit();

        } else {

            $error =
                "No se pudo modificar la promoción.";

        }

        $actualizar->close();

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

    <title>
        Nuvia - Modificar promoción
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
                Modificar promoción
            </h1>

            <p>
                Edita los datos de la promoción.
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


                <!-- DESCRIPCIÓN -->

                <div class="mb-4">

                    <label
                        for="descripcion"
                        class="form-label"
                    >
                        Descripción
                    </label>


                    <textarea
                        class="form-control"
                        id="descripcion"
                        name="descripcion"
                        rows="2"
                        maxlength="200"
                        required
                    ><?php

                        echo isset($_POST["descripcion"])
                            ? htmlspecialchars(
                                $_POST["descripcion"]
                            )
                            : htmlspecialchars(
                                $promocion["descripcionPromocion"]
                            );

                    ?></textarea>

                </div>


                <!-- DESCUENTO -->

                <div class="mb-4">

                    <label
                        for="descuento"
                        class="form-label"
                    >
                        Descuento (%)
                    </label>


                    <input
                        type="number"
                        class="form-control"
                        id="descuento"
                        name="descuento"
                        min="1"
                        max="100"
                        step="1"
                        value="<?php

                            echo isset($_POST["descuento"])
                                ? htmlspecialchars(
                                    $_POST["descuento"]
                                )
                                : htmlspecialchars(
                                    $promocion["descuentoPromocion"]
                                );

                        ?>"
                        required
                    >

                </div>


                <div class="alert alert-info">

                    Al modificar la promoción,
                    volverá a estado
                    <strong>Pendiente</strong>
                    para ser revisada nuevamente.

                </div>


                <div class="perfil-acciones">


                    <a
                        href="gestionPromocion.php"
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


    <script
        src="../../js/bootstrap.bundle.min.js"
    ></script>


</body>

</html>


<?php

$conexion->close();

?>