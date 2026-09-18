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


// PROCESAR FORMULARIO

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

        if (crearPromocion($conexion,$descripcion,$descuento,$codAerolinea)) {

            header("Location: /PaginaWeb/ceo/promociones/gestionPromocion.php?mensaje=creada");

            exit();

        } else {

            $error = "No se pudo crear la promoción.";

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