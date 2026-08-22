<?php

include "../../../php/conexionBD.php";


if ($_SERVER["REQUEST_METHOD"] == "POST") {


    $nombre = $_POST["nombreAerolinea"];
    $iata = $_POST["codigoIATA"];
    $descripcion = $_POST["descripcionAerolinea"];
    $pais = $_POST["codPais"];


    // INSERTAR AEROLÍNEA

    $consulta = "INSERT INTO Aerolineas
                 (
                     nombreAerolinea,
                     codigoIATA,
                     descripcionAerolinea,
                     codPais
                 )
                 VALUES (?, ?, ?, ?)";


    $stmt = $conexion->prepare($consulta);


    $stmt->bind_param(
        "ssss",
        $nombre,
        $iata,
        $descripcion,
        $pais
    );


    if ($stmt->execute()) {


        // GUARDAR ACTIVIDAD

        $accion = "Creó la aerolínea " . $nombre;

        $usuario = "Administrador";


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


        // VOLVER A GESTIÓN

        header(
            "Location: gestion-aerolineas.php?creada=1"
        );

        exit;


    } else {

        $mensaje = "Ocurrió un error al crear la aerolínea.";

    }


    $stmt->close();

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
        AeroFly Admin - Crear Aerolínea
    </title>


    <link
        rel="stylesheet"
        href="../../../css/bootstrap.min.css"
    >

    <link
        rel="stylesheet"
        href="../../../css/bootstrap-icons.css"
    >

    <link
        rel="stylesheet"
        href="../../../css/estilos-admin.css"
    >

    <link
        rel="icon"
        type="image/png"
        href="../../../imagenes/logo.png"
    >

</head>


<body>


    <?php include "../includes/navbar-admin.php"; ?>


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



        <?php if (isset($mensaje)) { ?>

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
                        action="crear-aerolinea.php"
                    >


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
                                required
                            >

                        </div>



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
                                required
                            >

                        </div>



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
                                required
                            >

                        </div>



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
                            ></textarea>

                        </div>



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