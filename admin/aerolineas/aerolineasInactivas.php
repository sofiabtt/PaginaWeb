<?php
require_once __DIR__ . '/../../includes/verificarRol.php';
verificarRol('administrador');

include "../../php/consultasAerolineas.php";

$resultado = obtenerAerolineasInactivas($conexion);

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
                    Aerolíneas dadas de baja
                </h1>

                <p>
                    Consulta las aerolíneas que fueron dadas de baja del sistema.
                </p>

            </div>

            <a href="gestionAerolineas.php" class="btn btn-secondary">

                <i class="bi bi-arrow-left"></i>

                Volver a aerolíneas

            </a>

        </section>

        <!-- TABLA -->

        <section class="tabla-contenedor">

            <table class="table align-middle">

                <thead>

                    <tr>

                        <th>Código</th>

                        <th>Aerolínea</th>

                        <th>IATA</th>

                        <th>País</th>

                        <th>Fecha de baja</th>

                        <th>Acciones</th>

                    </tr>

                </thead>

                <tbody>

                    <?php while ($aerolinea = $resultado->fetch_assoc()) { ?>

                        <tr>

                            <td>
                                <?php echo $aerolinea["codAerolinea"]; ?>
                            </td>

                            <td>
                                <?php echo htmlspecialchars($aerolinea["nombreAerolinea"]); ?>
                            </td>

                            <td>
                                <?php echo htmlspecialchars($aerolinea["codigoIATA"]); ?>
                            </td>

                            <td>
                                <?php echo htmlspecialchars($aerolinea["nombrePais"]); ?>
                            </td>

                            <td>
                                <?php
                                echo date(
                                    "d/m/Y H:i",
                                    strtotime($aerolinea["fechaEliminacion"])
                                );
                                ?>
                            </td>

                            <td> 
                                <a
                                    href="verAerolinea.php?id=<?php echo $aerolinea["codAerolinea"]; ?>&origen=inactivas"
                                    class="btn btn-sm btn-outline-secondary"
                                >

                                    <i class="bi bi-eye"></i>

                                    Ver

                                </a>
                            </td>

                        </tr>

                    <?php } ?>

                </tbody>

            </table>

        </section>

    </main>

    <?php include __DIR__ . '/../../includes/footer.php'; ?>

    <script src="<?= htmlspecialchars(urlApp('js/bootstrap.bundle.min.js'), ENT_QUOTES, 'UTF-8') ?>"></script>

</body>

</html>

