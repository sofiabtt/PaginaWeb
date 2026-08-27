<?php

include "../../php/conexionBD.php";


// VERIFICAR QUE SE RECIBIÓ EL ID

if (!isset($_GET["id"])) {

    header("Location: gestion-aerolineas.php");

    exit;

}


$codAerolinea = $_GET["id"];


// BUSCAR LA AEROLÍNEA

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


// SI NO EXISTE

if (!$aerolinea) {

    header("Location: gestion-aerolineas.php");

    exit;

}


// SI SE CONFIRMÓ LA ELIMINACIÓN

if ($_SERVER["REQUEST_METHOD"] == "POST") {


    // GUARDAMOS EL NOMBRE ANTES DE ELIMINAR

    $nombre = $aerolinea["nombreAerolinea"];


    // ELIMINAR

    $consulta = "DELETE FROM Aerolineas
                 WHERE codAerolinea = ?";


    $stmt = $conexion->prepare($consulta);


    $stmt->bind_param(
        "i",
        $codAerolinea
    );


    if ($stmt->execute()) {


        // REGISTRAR ACTIVIDAD

        $usuario = "Administrador";

        $accion = "Eliminó la aerolínea " . $nombre;


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


        // VOLVER A LA LISTA

        header(
            "Location: gestion-aerolineas.php?eliminada=1"
        );

        exit;


    } else {

        $mensaje = "Ocurrió un error al eliminar la aerolínea.";

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
        Admin - Eliminar Aerolínea
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
        rel="icon"
        type="image/png"
        href="../../imagenes/logo.png"
    >

</head>


<body>


    <!-- NAVBAR -->

    <?php include "../includes/navbarAdmin.php"; ?>


    <main class="contenido-admin">


        <section class="encabezado-contenido">

            <div>

                <h1>
                    Eliminar aerolínea
                </h1>

                <p>
                    Confirmá la eliminación de la aerolínea.
                </p>

            </div>

        </section>



        <?php if (isset($mensaje)) { ?>

            <div class="alert alert-danger">

                <?php echo $mensaje; ?>

            </div>

        <?php } ?>



        <!-- CONFIRMACIÓN -->

        <section class="container-fluid px-0">


            <div
                class="card border-0 shadow-sm mx-auto"
                style="max-width: 650px;"
            >

                <div class="card-body p-4 p-md-5 text-center">


                    <i
                        class="bi bi-exclamation-triangle text-warning"
                        style="font-size: 3rem;"
                    ></i>


                    <h2 class="mt-3">

                        ¿Eliminar esta aerolínea?

                    </h2>


                    <p class="text-muted">

                        Estás a punto de eliminar:

                    </p>


                    <h4>

                        <?php
                        echo htmlspecialchars(
                            $aerolinea["nombreAerolinea"]
                        );
                        ?>

                    </h4>


                    <p class="text-muted mt-3">

                        Esta acción no se puede deshacer.

                    </p>



                    <!-- BOTONES -->

                    <div
                        class="d-flex justify-content-center gap-2 mt-4"
                    >


                        <a
                            href="/PaginaWeb/admin/gestion-aerolineas/gestion-aerolineas.php"
                            class="btn btn-secondary"
                        >

                            Cancelar

                        </a>


                        <form
                            method="POST"
                            action="eliminar-aerolinea.php?id=<?php echo $codAerolinea; ?>"
                        >

                            <button
                                type="submit"
                                class="btn btn-danger"
                            >

                                <i class="bi bi-trash"></i>

                                Sí, eliminar

                            </button>

                        </form>


                    </div>


                </div>

            </div>


        </section>


    </main>


</body>

</html>