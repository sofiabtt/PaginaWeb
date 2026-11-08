<?php

require_once __DIR__ . '/../../includes/verificarRol.php';
verificarRol('ceo');

include "../../php/consultasCeos.php";
include "../../php/consultasAerolineas.php";
include "../../php/consultasVuelos.php";


verificarCeo();
$codUsuario = obtenerCodCeo();

$aerolinea = obtenerAerolineaPorCeo($conexion,$codUsuario);

if (!$aerolinea) {

    echo "El CEO no tiene una aerolínea asignada.";
    exit();

}

$codAerolinea = $aerolinea["codAerolinea"];

$resultado = obtenerVuelosInactivos($conexion,$codAerolinea);

require_once __DIR__ . '/../../php/conexionBD.php';
?>


<!DOCTYPE html>

<html lang="es">

<head>

    <meta charset="UTF-8">

    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <title>
        Nuvia - CEO
    </title>


    <!-- Bootstrap -->
    <link rel="stylesheet" href="<?= htmlspecialchars(urlApp('css/bootstrap.min.css'), ENT_QUOTES, 'UTF-8') ?>">

    <!-- Bootstrap Icons -->
    <link rel="stylesheet" href="<?= htmlspecialchars(urlApp('css/bootstrap-icons.css'), ENT_QUOTES, 'UTF-8') ?>">

    <!-- CSS administrador -->
    <link rel="stylesheet" href="<?= htmlspecialchars(urlApp('css/estilos.css'), ENT_QUOTES, 'UTF-8') ?>">
    <link rel="stylesheet" href="<?= htmlspecialchars(urlApp('css/tablasResponsive.css'), ENT_QUOTES, 'UTF-8') ?>">


    <link rel="stylesheet" href="<?= htmlspecialchars(urlApp('css/footer.css'), ENT_QUOTES, 'UTF-8') ?>">

    <!-- Favicon -->
    <link rel="icon" type="image/png" href="<?= htmlspecialchars(urlApp('imagenes/logo.png'), ENT_QUOTES, 'UTF-8') ?>">

</head>


<body class="nuvia-panel nuvia-navbar">

    <!-- NAVBAR -->

    <?php include __DIR__ . "/../includes/navbarCeo.php"; ?>


    <main class="contenido-admin">


        <!-- ENCABEZADO -->

        <section class="encabezado-contenido">

            <div>

                <h1>
                    Vuelos dados de baja
                </h1>

                <p>
                    Consulta los vuelos que fueron dados de baja de tu aerolínea.
                </p>

            </div>


            <a
                href="gestionVuelos.php"
                class="btn btn-secondary"
            >

                <i class="bi bi-arrow-left"></i>

                Volver a vuelos

            </a>

        </section>



        <!-- TABLA -->

        <section class="tabla-contenedor tabla-tarjetas">

            <table class="table align-middle">


                <thead>

                    <tr>

                        <th>
                            Código
                        </th>

                        <th>
                            Origen
                        </th>

                        <th>
                            Destino
                        </th>

                        <th>
                            Fecha de salida
                        </th>

                        <th>
                            Hora
                        </th>

                        <th>
                            Fecha de baja
                        </th>

                        <th>
                            Acciones
                        </th>

                    </tr>

                </thead>



                <tbody>

                    <?php if ($resultado->num_rows > 0) { ?>


                        <?php while ($vuelo = $resultado->fetch_assoc()) { ?>

                            <tr>

                                <td data-label="Código">

                                    <?php echo $vuelo["codVuelo"]; ?>

                                </td>


                                <td data-label="Origen">

                                    <?php echo htmlspecialchars($vuelo["origenVuelo"]); ?>

                                </td>


                                <td data-label="Destino">

                                    <?php echo htmlspecialchars($vuelo["destinoVuelo"]); ?>

                                </td>


                                <td data-label="Fecha de salida">

                                    <?php echo date("d/m/Y", strtotime($vuelo["fechaSalidaVuelo"])); ?>

                                </td>


                                <td data-label="Hora">

                                    <?php echo htmlspecialchars($vuelo["horaSalidaVuelo"]); ?>

                                </td>


                                <td data-label="Fecha de baja">

                                    <?php echo date("d/m/Y H:i", strtotime($vuelo["fechaEliminacion"]));?>

                                </td>


                                <td data-label="Acciones">

                                    <a
                                        href="verVuelo.php?id=<?php echo $vuelo["codVuelo"]; ?>&origen=inactivas"
                                        class="btn btn-sm btn-outline-secondary"
                                    >

                                        <i class="bi bi-eye"></i>

                                        Ver

                                    </a>

                                </td>

                            </tr>

                        <?php } ?>


                    <?php } else { ?>

                        <tr>

                            <td
                                colspan="7"
                                class="text-center py-4"
                            >

                                No hay vuelos dados de baja.

                            </td>

                        </tr>

                    <?php } ?>

                </tbody>


            </table>

        </section>


    </main>

    <?php include __DIR__ . "/../../includes/footer.php"; ?>

    <script src="<?= htmlspecialchars(urlApp('js/bootstrap.bundle.min.js'), ENT_QUOTES, 'UTF-8') ?>"></script>

</body>

</html>


<?php


    $conexion->close();

?>