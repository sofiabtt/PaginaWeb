<?php

include "../../php/consultasAerolineas.php";
include "../../php/consultasActividad.php";

$nombre = "";
$iata = "";
$descripcion = "";
$codPais = "";
$mensaje = "";


if ($_SERVER["REQUEST_METHOD"] == "POST") {

    $nombre = trim($_POST["nombreAerolinea"]);
    $iata = strtoupper(trim($_POST["codigoIATA"]));
    $descripcion = trim($_POST["descripcionAerolinea"]);
    $codPais = $_POST["codPais"];


    // VALIDAR CÓDIGO IATA

    if (!preg_match('/^[A-Z]{3}$/', $iata)) {

        $mensaje = "El código IATA debe tener exactamente 3 letras.";

    } else {

        // CREAR AEROLÍNEA

        if (crearAerolinea($conexion, $nombre, $iata, $descripcion, $codPais)) {

            registrarActividad(
                $conexion,
                "Administrador",
                "Creó la aerolínea " . $nombre
            );

            header("Location: gestionAerolineas.php?creada=1");

            exit;

        } else {

            $mensaje = "Ocurrió un error al crear la aerolínea.";

        }

    }

}


$resultadoPaises = obtenerPaises($conexion);

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
        Nuvia - Administrador
    </title>


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

    <link
        rel="stylesheet"
        href="../../css/navbar.css"
    >

    <link
        rel="icon"
        type="image/png"
        href="../../imagenes/logo.png"
    >

</head>


<body>


    <?php include "../includes/navbarAdmin.php"; ?>


    <main class="contenido-admin">


        <section class="encabezado-contenido">

            <div>

                <h1>
                    Crear aerolínea
                </h1>

                <p>
                    Ingresá los datos de la nueva aerolínea.
                </p>

            </div>

        </section>



        <?php if ($mensaje != "") { ?>

            <div class="alert alert-danger">

                <?php echo $mensaje; ?>

            </div>

        <?php } ?>



        <section class="container-fluid px-0">


            <div
                class="card shadow-sm border-0 mx-auto"
                style="max-width: 850px;"
            >

                <div class="card-body p-4 p-md-5">


                    <form
                        method="POST"
                        action="crearAerolinea.php"
                    >


                        <!-- NOMBRE -->

                        <div class="mb-3">

                            <label
                                for="nombreAerolinea"
                                class="form-label fw-semibold"
                            >
                                Nombre de la aerolínea
                            </label>

                            <input
                                type="text"
                                class="form-control"
                                id="nombreAerolinea"
                                name="nombreAerolinea"
                                value="<?php echo htmlspecialchars($nombre); ?>"
                                required
                            >

                        </div>



                        <!-- CÓDIGO IATA -->

                        <div class="mb-3">

                            <label
                                for="codigoIATA"
                                class="form-label fw-semibold"
                            >
                                Código IATA

                                <span class="text-muted fw-normal">
                                    (3 letras)
                                </span>

                            </label>

                            <input
                                type="text"
                                class="form-control"
                                id="codigoIATA"
                                name="codigoIATA"
                                minlength="3"
                                maxlength="3"
                                pattern="[A-Za-z]{3}"
                                title="El código IATA debe tener exactamente 3 letras."
                                value="<?php echo htmlspecialchars($iata); ?>"
                                required
                            >

                        </div>



                        <!-- PAÍS -->

                        <div class="mb-3">

                            <label
                                for="codPais"
                                class="form-label fw-semibold"
                            >
                                País
                            </label>

                            <select
                                class="form-select"
                                id="codPais"
                                name="codPais"
                                required
                            >

                                <option value="">
                                    Seleccioná un país
                                </option>


                                <?php while ($pais = $resultadoPaises->fetch_assoc()) { ?>

                                    <option
                                        value="<?php echo $pais["codPais"]; ?>"
                                        <?php
                                        if ($codPais == $pais["codPais"]) {
                                            echo "selected";
                                        }
                                        ?>
                                    >
                                        <?php echo htmlspecialchars($pais["nombrePais"]); ?>
                                    </option>

                                <?php } ?>


                            </select>

                        </div>



                        <!-- DESCRIPCIÓN -->

                        <div class="mb-4">

                            <label
                                for="descripcionAerolinea"
                                class="form-label fw-semibold"
                            >
                                Descripción
                            </label>

                            <textarea
                                class="form-control"
                                id="descripcionAerolinea"
                                name="descripcionAerolinea"
                                rows="4"
                                required
                            ><?php echo htmlspecialchars($descripcion); ?></textarea>

                        </div>



                        <!-- BOTONES -->

                        <div class="d-flex justify-content-end gap-2">

                            <a
                                href="/PaginaWeb/admin/aerolineas/gestionAerolineas.php"
                                class="btn btn-secondary"
                            >
                                Cancelar
                            </a>


                            <button
                                type="submit"
                                class="btn btn-primary"
                            >

                                <i class="bi bi-plus-lg"></i>

                                Crear aerolínea

                            </button>

                        </div>


                    </form>


                </div>

            </div>


        </section>


    </main>


</body>

</html>