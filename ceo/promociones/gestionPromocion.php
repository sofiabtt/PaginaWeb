<?php

require_once __DIR__ . '/../../includes/verificarRol.php';
verificarRol('ceo');

require_once __DIR__ . '/../../php/conexionBD.php';
require_once __DIR__ . '/../../php/consultasCeos.php';
require_once __DIR__ . '/../../php/consultasAerolineas.php';
require_once __DIR__ . '/../../php/consultasPromociones.php';


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

    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <title>Nuvia - CEO</title>


    <link rel="icon" href="<?= htmlspecialchars(urlApp('imagenes/logo.png'), ENT_QUOTES, 'UTF-8')?>" type="image/png">
    <link rel="stylesheet" href="<?= htmlspecialchars(urlApp('css/bootstrap.min.css'), ENT_QUOTES, 'UTF-8')?>">
    <link rel="stylesheet" href="<?= htmlspecialchars(urlApp('css/bootstrap-icons.css'), ENT_QUOTES, 'UTF-8')?>">
    <link rel="stylesheet" href="<?= htmlspecialchars(urlApp('css/estilosAdmin.css'), ENT_QUOTES, 'UTF-8')?>">
    <link rel="stylesheet" href="<?= htmlspecialchars(urlApp('css/tablasResponsive.css'), ENT_QUOTES, 'UTF-8') ?>">
    <link rel="stylesheet" href="<?= htmlspecialchars(urlApp('css/navbar.css'), ENT_QUOTES, 'UTF-8') ?>">
    <link rel="stylesheet" href="<?= htmlspecialchars(urlApp('css/footer.css'), ENT_QUOTES, 'UTF-8') ?>">

</head>


<body>


    <?php include __DIR__ . "/../includes/navbarCeo.php"; ?> 


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


                <div>
                    <a href="crearPromocion.php" class="btn btn-primary">
                        <i class="bi bi-plus-lg"></i>
                        Crear promoción
                    </a>
                </div>


            </div>


        </section>



        <section class="actividad-admin">


            <div class="tabla-contenedor tabla-tarjetas">

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


                                    <td data-label="Código">

                                        <?php
                                        echo $promocion["codPromocion"];
                                        ?>

                                    </td>


                                    <td data-label="Descripción">

                                        <?php

                                        echo htmlspecialchars(
                                            $promocion["descripcionPromocion"]
                                        );

                                        ?>

                                    </td>


                                    <td data-label="Descuento">

                                        <?php
                                        echo $promocion["descuentoPromocion"];
                                        ?>%

                                    </td>


                                    <td data-label="Estado">

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


                                    <td class="acciones-tabla" data-label="Acciones">


                                        <a
                                            href="modificarPromocion.php?id=<?php echo $promocion["codPromocion"]; ?>"
                                            class="btn btn-sm btn-outline-primary"
                                            title="Modificar promoción"
                                        >

                                            <i class="bi bi-pencil"></i>
                                            Modificar

                                        </a>


                                        <a
                                            href="eliminarPromocion.php?id=<?php echo $promocion["codPromocion"]; ?>"
                                            class="btn btn-sm btn-outline-danger"
                                            title="Eliminar promoción"
                                        >

                                            <i class="bi bi-trash"></i>
                                            Eliminar

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


        </section>


    </main>

    <?php include __DIR__ . "/../../includes/footer.php"; ?>

    <script src="<?= htmlspecialchars(urlApp('js/bootstrap.bundle.min.js'), ENT_QUOTES, 'UTF-8') ?>"></script>


</body> 

</html>


<?php


$conexion->close();

?>