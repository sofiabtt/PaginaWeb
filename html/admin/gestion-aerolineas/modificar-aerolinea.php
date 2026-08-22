<?php

include "../../../php/conexionBD.php";


// OBTENER EL CÓDIGO DE LA AEROLÍNEA

if (!isset($_GET["id"])) {

    header("Location: gestion-aerolineas.php");

    exit;

}

$codAerolinea = $_GET["id"];


// SI SE ENVIÓ EL FORMULARIO

if ($_SERVER["REQUEST_METHOD"] == "POST") {

    $nombre = $_POST["nombreAerolinea"];
    $iata = $_POST["codigoIATA"];
    $descripcion = $_POST["descripcionAerolinea"];
    $pais = $_POST["codPais"];


    // ACTUALIZAR AEROLÍNEA

    $consulta = "UPDATE Aerolineas
                 SET nombreAerolinea = ?,
                     codigoIATA = ?,
                     descripcionAerolinea = ?,
                     codPais = ?
                 WHERE codAerolinea = ?";


    $stmt = $conexion->prepare($consulta);


    $stmt->bind_param(
        "ssssi",
        $nombre,
        $iata,
        $descripcion,
        $pais,
        $codAerolinea
    );


    if ($stmt->execute()) {


        // REGISTRAR ACTIVIDAD

        $usuario = "Administrador";

        $accion = "Modificó la aerolínea " . $nombre;


        $consultaActividad = "INSERT INTO Actividad
                              (
                                  usuarioActividad,
                                  accionActividad
                              )
                              VALUES (?, ?)";


        $stmtActividad = $conexion->prepare(
            $consultaActividad
        );


        $stmtActividad->bind_param(
            "ss",
            $usuario,
            $accion
        );


        $stmtActividad->execute();

        $stmtActividad->close();


        // VOLVER A GESTIÓN DE AEROLÍNEAS

        header(
            "Location: gestion-aerolineas.php?modificada=1"
        );

        exit;


    } else {

        $mensaje = "Ocurrió un error al modificar la aerolínea.";

    }


    $stmt->close();

}


// BUSCAR LOS DATOS ACTUALES

$consulta = "SELECT *
             FROM Aerolineas
             WHERE codAerolinea = ?";


$stmt = $conexion->prepare($consulta);


$stmt->bind_param(
    "i",
    $codAerolinea
);


$stmt->execute();


$resultado = $stmt->get_result();


$aerolinea = $resultado->fetch_assoc();


$stmt->close();


// SI NO EXISTE LA AEROLÍNEA

if (!$aerolinea) {

    header("Location: gestion-aerolineas.php");

    exit;

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
        AeroFly Admin - Modificar Aerolínea
    </title>


    <!-- Bootstrap -->

    <link
        rel="stylesheet"
        href="../../../css/bootstrap.min.css"
    >


    <!-- Bootstrap Icons -->

    <link
        rel="stylesheet"
        href="../../../css/bootstrap-icons.css"
    >


    <!-- CSS administrador -->

    <link
        rel="stylesheet"
        href="../../../css/estilos-admin.css"
    >


    <!-- Favicon -->

    <link
        rel="icon"
        type="image/png"
        href="../../../imagenes/logo.png"
    >

</head>


<body>


    <!-- NAVBAR -->

    <?php include "../includes/navbar-admin.php"; ?>


    <main class="contenido-admin">


        <!-- ENCABEZADO -->

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
                        action="modificar-aerolinea.php?id=<?php echo $codAerolinea; ?>"
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
                                href="/PaginaWeb/html/admin/gestion-aerolineas/gestion-aerolineas.php"
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


</body>

</html>