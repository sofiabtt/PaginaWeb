<?php

include "../../php/conexionBD.php";
include "../../php/consultasCeos.php";
include "../../php/consultasActividad.php";

if (!isset($_GET["id"])) {

    header("Location: gestionCeos.php");

    exit;
}

$codUsuario = $_GET["id"];

// BUSCAR EL CEO

$ceo = obtenerCeo($conexion, $codUsuario);

// SI NO EXISTE

if (!$ceo) {

    header("Location: gestionCeos.php");

    exit;
}

// SI SE CONFIRMÓ LA ELIMINACIÓN

if ($_SERVER["REQUEST_METHOD"] == "POST") {

    // GUARDAMOS LOS DATOS ANTES DE ELIMINAR

    $nombre = $ceo["nombreUsuario"];
    $aerolinea = $ceo["nombreAerolinea"];

    // BAJA LÓGICA Y DESVINCULACIÓN DE LA AEROLÍNEA

    if (eliminarCeo($conexion, $codUsuario)) {

        // REGISTRAR ACTIVIDAD

        registrarActividad($conexion, "Administrador", "Eliminó el CEO " . $nombre);

        header("Location: gestionCeos.php?eliminado=1");

        exit;

    } else {

        $mensaje = "Ocurrió un error al eliminar el CEO.";

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
                    Eliminar CEO
                </h1>

                <p>
                    Confirmá la eliminación del CEO.
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

                        ¿Eliminar este CEO?

                    </h2>


                    <p class="text-muted">

                        Estás a punto de eliminar:

                    </p>


                    <h4>

                        <?php
                        echo htmlspecialchars(
                            $ceo["nombreUsuario"]
                        );
                        ?>

                    </h4>


                    <?php if ($aerolinea) { ?>

                        <p class="text-muted mt-3">

                            El CEO está vinculado a la aerolínea:

                        </p>

                        <h5>

                            <?php
                            echo htmlspecialchars($aerolinea);
                            ?>

                        </h5>

                        <div class="alert alert-warning mt-3">

                            <i class="bi bi-info-circle"></i>

                            Al eliminar este CEO, también se
                            <strong>desvinculará de la aerolínea</strong>.
                            La aerolínea no será eliminada.

                        </div>

                    <?php } else { ?>

                        <p class="text-muted mt-3">

                            Este CEO no está vinculado a ninguna aerolínea.

                        </p>

                    <?php } ?>


                    <p class="text-muted mt-3">

                        Esta acción no se puede deshacer.

                    </p>



                    <!-- BOTONES -->

                    <div
                        class="d-flex justify-content-center gap-2 mt-4"
                    >


                        <a
                            href="/PaginaWeb/admin/ceos/gestionCeos.php"
                            class="btn btn-secondary"
                        >

                            Cancelar

                        </a>


                        <form
                            method="POST"
                            action="eliminarCeo.php?id=<?php echo $codUsuario; ?>"
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