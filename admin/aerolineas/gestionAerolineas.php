<?php

ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

require_once __DIR__ . '/../../includes/verificarRol.php';
verificarRol('administrador');


include "../../php/consultasAerolineas.php";

require_once __DIR__ . '/../../php/paginacion.php';


// PAGINACIÓN

$totalRegistros = contarAerolineasActivas($conexion);

$paginacion = configurarPaginacion($totalRegistros);

$pagina = $paginacion["pagina"];
$totalPaginas = $paginacion["totalPaginas"];

$resultado = obtenerAerolineasActivasPaginadas(
    $conexion,
    $paginacion["porPagina"],
    $paginacion["offset"]
);

?>

<!DOCTYPE html>
<html lang="es">

<head>

    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <title>Nuvia - Gestión de Aerolíneas</title>
    <link rel="icon" type="image/png" href="../../imagenes/logo.png">

    <link rel="stylesheet" href="../../css/bootstrap.min.css">
    <link rel="stylesheet" href="../../css/bootstrap-icons.css">

    <link rel="stylesheet" href="<?= htmlspecialchars(urlApp('css/estilos.css'), ENT_QUOTES, 'UTF-8') ?>?v=20261108">

</head>

<body class="nuvia-panel nuvia-navbar">

    <?php include __DIR__ . '/../includes/navbarAdmin.php'; ?>

    <main class="contenido-admin">

        <section class="encabezado-contenido">

            <div>

                <h1>Gestión de Aerolíneas</h1>

                <p>
                    Administra las aerolíneas registradas en el sistema.
                </p>

            </div>

            <div class="d-flex gap-2">

                <a
                    href="aerolineasInactivas.php"
                    class="btn btn-outline-secondary"
                >
                    <i class="bi bi-archive"></i>
                    Aerolíneas dadas de baja
                </a>

                <a
                    href="crearAerolinea.php"
                    class="btn btn-primary"
                >
                    <i class="bi bi-plus-lg"></i>
                    Crear aerolínea
                </a>

            </div>

        </section>

        <!-- MENSAJES -->

        <?php if (isset($_GET["creada"])) { ?>

            <div class="alert alert-success">
                La aerolínea se creó correctamente.
            </div>

        <?php } ?>


        <?php if (isset($_GET["modificada"])) { ?>

            <div class="alert alert-success">
                La aerolínea se modificó correctamente.
            </div>

        <?php } ?>


        <?php if (isset($_GET["eliminada"])) { ?>

            <div class="alert alert-success">
                La aerolínea se eliminó correctamente.
            </div>

        <?php } ?>

        <!-- TABLA -->

        <section class="tabla-contenedor tabla-tarjetas">

            <table class="table align-middle">

                <thead>

                    <tr>
                        <th scope="col">Código</th>
                        <th scope="col">Aerolínea</th>
                        <th scope="col">IATA</th>
                        <th scope="col">País</th>
                        <th scope="col">Acciones</th>
                    </tr>

                </thead>

                <tbody>

                    <?php while ($aerolinea = $resultado->fetch_assoc()) { ?>

                        <tr>

                            <td data-label="Código">
                                <?php echo (int) $aerolinea["codAerolinea"]; ?>
                            </td>

                            <td data-label="Aerolínea">
                                <?php echo htmlspecialchars($aerolinea["nombreAerolinea"]); ?>
                            </td>

                            <td data-label="IATA">
                                <?php echo htmlspecialchars($aerolinea["codigoIATA"]); ?>
                            </td>

                            <td data-label="País">
                                <?php echo htmlspecialchars($aerolinea["nombrePais"]); ?>
                            </td>

                            <td data-label="Acciones" class="acciones-tabla">

                                <a
                                    href="verAerolinea.php?id=<?php echo (int) $aerolinea["codAerolinea"]; ?>"
                                    class="btn btn-sm btn-outline-secondary"
                                >
                                    <i class="bi bi-eye"></i>
                                    Ver
                                </a>

                                <a
                                    href="modificarAerolinea.php?id=<?php echo (int) $aerolinea["codAerolinea"]; ?>"
                                    class="btn btn-sm btn-outline-primary"
                                >
                                    <i class="bi bi-pencil"></i>
                                    Modificar
                                </a>

                                <a
                                    href="eliminarAerolinea.php?id=<?php echo (int) $aerolinea["codAerolinea"]; ?>"
                                    class="btn btn-sm btn-outline-danger"
                                >
                                    <i class="bi bi-trash"></i>
                                    Eliminar
                                </a>

                            </td>

                        </tr>

                    <?php } ?>

                    <?php if ($totalRegistros === 0) { ?>

                        <tr>
                            <td colspan="5" class="text-center py-4">
                                No hay aerolíneas activas registradas.
                            </td>
                        </tr>

                    <?php } ?>

                </tbody>

            </table>

        </section>

        <?php include __DIR__ . '/../../php/paginacionVista.php'; ?>

    </main>

    <?php include __DIR__ . '/../../includes/footer.php'; ?>

    <script src="<?= htmlspecialchars(urlApp('js/bootstrap.bundle.min.js'), ENT_QUOTES, 'UTF-8') ?>"></script>

</body>

</html>