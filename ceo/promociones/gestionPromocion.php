<?php

session_start();

include "../../php/conexionBD.php";
include "../../php/consultasCeos.php";
include "../../php/consultasAerolineas.php";
include "../../php/consultasPromociones.php";


verificarCeo();
$codUsuario = obtenerCodCeo();

// OBTENER SU AEROLÍNEA

$aerolinea = obtenerAerolineaPorCeo($conexion, $codUsuario);

if (!$aerolinea) {

    echo "El CEO no tiene una aerolínea asignada.";
    exit();

}

$codAerolinea = $aerolinea["codAerolinea"];

// OBTENER PROMOCIONES

$resultado = obtenerPromocionesPorAerolinea($conexion,$codAerolinea);

?>

<!DOCTYPE html>

<html lang="es">

<head>

    <meta charset="UTF-8">

    <meta
        name="viewport"
        content="width=device-width, initial-scale=1.0"
    >

    <title>Nuvia - CEO</title>


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

    <link rel="stylesheet" href="/PaginaWeb/css/navbar.css">

</head>


<body>


    <?php include "../includes/navbarCeo.php"; ?>


    <main class="contenido-admin">


        <section class="bienvenida-admin">


            <div class="encabezado-contenido">


                <div>

                    <h1>
                        Gestión de promociones
                    </h1>

                    <p>
                        Administra las promociones
                        de tu aerolínea.
                    </p>

                </div>


                <a
                    href="crearPromocion.php"
                    class="btn btn-primary"
                >

                    <i class="bi bi-plus-lg"></i>

                    Crear promoción

                </a>


            </div>


        </section>



        <section class="actividad-admin">


            <div class="tabla-contenedor">


                <div class="table-responsive">


                    <table class="table align-middle">


                        <thead>

                            <tr>

                                <th>
                                    Código
                                </th>

                                <th>
                                    Descripción
                                </th>

                                <th>
                                    Descuento
                                </th>

                                <th>
                                    Estado
                                </th>

                                <th>
                                    Acciones
                                </th>

                            </tr>

                        </thead>


                        <tbody>


                        <?php if ($resultado->num_rows > 0) { ?>


                            <?php while ($promocion = $resultado->fetch_assoc()) { ?>


                                <tr>


                                    <td>

                                        <?php
                                        echo $promocion["codPromocion"];
                                        ?>

                                    </td>


                                    <td>

                                        <?php

                                        echo htmlspecialchars(
                                            $promocion["descripcionPromocion"]
                                        );

                                        ?>

                                    </td>


                                    <td>

                                        <?php
                                        echo $promocion["descuentoPromocion"];
                                        ?>%

                                    </td>


                                    <td>

                                        <?php

                                        if (
                                            $promocion["estadoPromocion"]
                                            == "Pendiente"
                                        ) {

                                        ?>

                                            <span
                                                class="badge bg-warning text-dark"
                                            >
                                                Pendiente
                                            </span>

                                        <?php

                                        } elseif (
                                            $promocion["estadoPromocion"]
                                            == "Aprobada"
                                        ) {

                                        ?>

                                            <span
                                                class="badge bg-success"
                                            >
                                                Aprobada
                                            </span>

                                        <?php

                                        } else {

                                        ?>

                                            <span
                                                class="badge bg-danger"
                                            >
                                                <?php
                                                echo htmlspecialchars(
                                                    $promocion["estadoPromocion"]
                                                );
                                                ?>
                                            </span>

                                        <?php

                                        }

                                        ?>

                                    </td>


                                    <td>


                                        <a
                                            href="modificarPromocion.php?id=<?php echo $promocion["codPromocion"]; ?>"
                                            class="btn btn-sm btn-outline-primary"
                                            title="Modificar promoción"
                                        >

                                            <i class="bi bi-pencil"></i>

                                        </a>


                                        <a
                                            href="eliminarPromocion.php?id=<?php echo $promocion["codPromocion"]; ?>"
                                            class="btn btn-sm btn-outline-danger"
                                            title="Eliminar promoción"
                                        >

                                            <i class="bi bi-trash"></i>

                                        </a>


                                    </td>


                                </tr>


                            <?php } ?>


                        <?php } else { ?>


                            <tr>

                                <td
                                    colspan="5"
                                    class="text-center py-4"
                                >

                                    <i
                                        class="bi bi-percent"
                                        style="font-size: 30px;"
                                    ></i>

                                    <p>

                                        No hay promociones registradas
                                        para tu aerolínea.

                                    </p>

                                </td>

                            </tr>


                        <?php } ?>


                        </tbody>


                    </table>


                </div>


            </div>


        </section>


    </main>


    <script src="../../js/bootstrap.bundle.min.js"></script>


</body>

</html>


<?php


$conexion->close();

?>