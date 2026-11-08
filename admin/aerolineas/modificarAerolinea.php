<?php

require_once __DIR__ . '/../../includes/verificarRol.php';
verificarRol('administrador');

include "../../php/consultasAerolineas.php";
include "../../php/consultasActividad.php";

// OBTENER EL CÓDIGO DE LA AEROLÍNEA

if (!isset($_GET["id"])) {

    header("Location: gestionAerolineas.php");

    exit;
}

$codAerolinea = $_GET["id"];

// SI SE ENVIÓ EL FORMULARIO

if ($_SERVER["REQUEST_METHOD"] == "POST") {

    $nombre = $_POST["nombreAerolinea"];
    $iata = $_POST["codigoIATA"];
    $descripcion = $_POST["descripcionAerolinea"];
    $pais = $_POST["codPais"];


    // MODIFICAR AEROLÍNEA

    if (modificarAerolinea($conexion, $codAerolinea, $nombre, $iata, $descripcion, $pais)) {

        registrarActividad($conexion,"Administrador","Modificó la aerolínea " . $nombre);

        // VOLVER A GESTIÓN DE AEROLÍNEAS

        header("Location: gestionAerolineas.php?modificada=1");

        exit;

    } else {

        $mensaje = "Ocurrió un error al modificar la aerolínea.";

    }
}

// BUSCAR LOS DATOS ACTUALES

$aerolinea = obtenerAerolinea($conexion,$codAerolinea);

// SI NO EXISTE LA AEROLÍNEA

if (!$aerolinea) {

    header("Location: gestionAerolineas.php");

    exit;
}

?>


<!DOCTYPE html>

<html lang="es">

<head>

    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <title> Nuvia - Administrador </title>

    <link rel="icon" href="../../imagenes/logo.png" type="image/png">

    <link rel="stylesheet" href="../../css/bootstrap.min.css">
    <link rel="stylesheet" href="../../css/bootstrap-icons.css">

    <link rel="stylesheet" href="../../css/estilos.css">

    <link rel="stylesheet" href="../../css/footer.css">

</head>

<body class="nuvia-panel">

    <?php include __DIR__ . '/../includes/navbarAdmin.php'; ?>

    <main class="contenido-admin">

        <section class="encabezado-contenido">

            <div>

                <h1>
                    Modificar aerolínea
                </h1>

                <p>
                    Modificá los datos de la aerolínea seleccionada.
                </p>

            </div>

        </section>

        <!-- MENSAJE DE ERROR -->

        <?php if (isset($mensaje)) { ?>

            <div class="alert alert-danger">

                <?php echo $mensaje; ?>

            </div>

        <?php } ?>

        <!-- FORMULARIO -->

        <section class="container-fluid px-0">

            <div
                class="card border-0 shadow-sm mx-auto"
                style="max-width: 850px;"
            >

                <div class="card-body p-4 p-md-5">

                    <form
                        method="POST"
                        action="modificarAerolinea.php?id=<?php echo $codAerolinea; ?>"
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
                                value="<?php echo htmlspecialchars($aerolinea["nombreAerolinea"]); ?>"
                                required
                            >

                        </div>

                        <!-- IATA -->

                        <div class="mb-3">

                            <label
                                for="codigoIATA"
                                class="form-label fw-semibold"
                            >
                                Código IATA
                            </label>

                            <input
                                type="text"
                                class="form-control"
                                id="codigoIATA"
                                name="codigoIATA"
                                maxlength="3"
                                value="<?php echo htmlspecialchars($aerolinea["codigoIATA"]); ?>"
                                required
                            >

                        </div>

                        <!-- PAÍS -->

                        <div class="mb-3">

                            <label
                                for="codPais"
                                class="form-label fw-semibold"
                            >
                                Código de país
                            </label>


                            <input
                                type="text"
                                class="form-control"
                                id="codPais"
                                name="codPais"
                                maxlength="3"
                                value="<?php echo htmlspecialchars($aerolinea["codPais"]); ?>"
                                required
                            >

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
                            ><?php echo htmlspecialchars($aerolinea["descripcionAerolinea"]); ?></textarea>

                        </div>

                        <!-- BOTONES -->

                        <div class="d-flex justify-content-end gap-2">

                            <a
                                href="<?= htmlspecialchars(urlApp('admin/aerolineas/gestionAerolineas.php'), ENT_QUOTES, 'UTF-8') ?>"
                                class="btn btn-secondary"
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

                </div>

            </div>

        </section>

    </main>

    <?php include __DIR__ . '/../../includes/footer.php'; ?>

    <script src="<?= htmlspecialchars(urlApp('js/bootstrap.bundle.min.js'), ENT_QUOTES, 'UTF-8') ?>"></script>

</body>

</html>