<?php

session_start();

include "../../php/conexionBD.php";
include "../../php/consultasCeos.php";
include "../../php/consultasAerolineas.php";
include "../../php/consultasPromociones.php";

verificarCeo();
$codUsuario = obtenerCodCeo();

$aerolinea = obtenerAerolineaPorCeo($conexion,$codUsuario);

if (!$aerolinea) {

    echo "El CEO no tiene una aerolínea asignada.";
    exit();

}

$codAerolinea = $aerolinea["codAerolinea"];


// OBTENER ID DE PROMOCIÓN

if (!isset($_GET["id"])) {

    echo "Promoción no especificada.";
    exit();

}

$codPromocion = intval($_GET["id"]);

// BUSCAR PROMOCIÓN

$promocion = obtenerPromocion($conexion,$codPromocion,$codAerolinea);

if (!$promocion) {

    echo "La promoción no existe o no pertenece a tu aerolínea.";
    exit();

}


// PROCESAR MODIFICACIÓN

if ($_SERVER["REQUEST_METHOD"] == "POST") {

    $descripcion = trim($_POST["descripcion"]);
    $descuento = $_POST["descuento"];


    if (empty($descripcion) || empty($descuento)) {

        $error = "Debe completar todos los campos.";

    } elseif ($descuento <= 0) {

        $error = "El descuento debe ser mayor a 0.";

    } elseif ($descuento > 100) {

        $error = "El descuento no puede ser mayor a 100%.";

    } else {

        if (modificarPromocion($conexion, $codPromocion, $codAerolinea, $descripcion, $descuento)) {

            header("Location: gestionPromocion.php?mensaje=modificada");
            exit();

        } else {

            $error = "No se pudo modificar la promoción.";

        }

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