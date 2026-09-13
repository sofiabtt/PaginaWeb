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
// PROCESAR FORMULARIO
// =========================

if ($_SERVER["REQUEST_METHOD"] == "POST") {

    $descripcion =
        trim($_POST["descripcion"]);

    $descuento =
        $_POST["descuento"];


    // =========================
    // VALIDAR CAMPOS
    // =========================

    if (
        empty($descripcion) ||
        empty($descuento)
    ) {

        $error =
            "Debe completar todos los campos.";

    } elseif ($descuento <= 0) {

        $error =
            "El descuento debe ser mayor a 0.";

    } elseif ($descuento > 100) {

        $error =
            "El descuento no puede ser mayor a 100%.";

    } else {


        // =========================
        // DATOS INICIALES
        // =========================

        $estadoPromocion =
            "Pendiente";

        $activoPromocion =
            1;


        // =========================
        // INSERTAR PROMOCIÓN
        // =========================

        $consulta = $conexion->prepare("

            INSERT INTO Promociones
            (
                descripcionPromocion,
                descuentoPromocion,
                codAerolinea,
                estadoPromocion,
                activoPromocion
            )

            VALUES (?, ?, ?, ?, ?)

        ");


        $consulta->bind_param(
            "sdisi",
            $descripcion,
            $descuento,
            $codAerolinea,
            $estadoPromocion,
            $activoPromocion
        );


        if ($consulta->execute()) {

            $consulta->close();

            header("Location: /PaginaWeb/ceo/promociones/gestionPromocion.php?mensaje=creada");
            exit();


        } else {

            $error =
                "No se pudo crear la promoción.";

        }


        $consulta->close();

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
        Nuvia - CEO
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
                Crear promoción
            </h1>


            <p>
                Crea una nueva promoción
                para tu aerolínea.
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
                        placeholder="Ej. 20% de descuento en vuelos a Córdoba"
                        required
                    ><?php

                        echo isset($_POST["descripcion"])
                            ? htmlspecialchars(
                                $_POST["descripcion"]
                            )
                            : "";

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
                        placeholder="Ej. 20"
                        value="<?php

                            echo isset($_POST["descuento"])
                                ? htmlspecialchars(
                                    $_POST["descuento"]
                                )
                                : "";

                        ?>"
                        required
                    >


                </div>



                <!-- INFORMACIÓN -->

                <div class="alert alert-info">

                    La promoción quedará en estado
                    <strong>Pendiente</strong>
                    hasta que sea aprobada
                    por el administrador.

                </div>



                <!-- BOTONES -->

                <div class="perfil-acciones">


                    <a
                        href="gestionPromociones.php"
                        class="btn btn-outline-secondary me-2"
                    >

                        Cancelar

                    </a>


                    <button
                        type="submit"
                        class="btn btn-primary"
                    >

                        <i class="bi bi-plus-lg"></i>

                        Crear promoción

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