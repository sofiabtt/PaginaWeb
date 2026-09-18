<?php

session_start();

include "../../php/conexionBD.php";
include "../../php/consultasCeos.php";
include "../../php/consultasAerolineas.php";
include "../../php/consultasPromociones.php";

verificarCeo();
$codUsuario = obtenerCodCeo();

$aerolinea = obtenerAerolineaPorCeo(
    $conexion,
    $codUsuario
);

if (!$aerolinea) {

    echo "El CEO no tiene una aerolínea asignada.";
    exit();

}

$codAerolinea = $aerolinea["codAerolinea"];

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

// CONFIRMAR ELIMINACIÓN

if ($_SERVER["REQUEST_METHOD"] == "POST") {

    if (eliminarPromocion($conexion,$codPromocion,$codAerolinea)) {

        header("Location: gestionPromocion.php?mensaje=eliminada");
        exit();

    } else {

        $error = "No se pudo eliminar la promoción.";

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