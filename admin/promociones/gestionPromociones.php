<?php

require_once __DIR__ . '/../../includes/verificarRol.php';
verificarRol('administrador');

include "../../php/conexionBD.php";
include "../../php/consultasPromociones.php";
include "../../php/consultasActividad.php";

// APROBAR O RECHAZAR PROMOCIÓN

if ($_SERVER["REQUEST_METHOD"] === "POST") {

    $codPromocion = filter_input(
        INPUT_POST,
        "codPromocion",
        FILTER_VALIDATE_INT
    );

    $accion = $_POST["accion"] ?? "";

    $nuevoEstado = "";

    if ($accion === "aprobar") {

        $nuevoEstado = "Aprobada";

    } elseif ($accion === "rechazar") {

        $nuevoEstado = "Rechazada";

    }

    if ($codPromocion && $nuevoEstado !== "") {

        if (cambiarEstadoPromocion($conexion, $codPromocion, $nuevoEstado)) {

            if ($nuevoEstado === "Aprobada") {

                $accionActividad = "Aprobó la promoción " . $codPromocion;

            } else {

                $accionActividad = "Rechazó la promoción " . $codPromocion;

            }

            registrarActividad(
                $conexion,
                "Administrador",
                $accionActividad
            );

            header("Location: gestionPromociones.php?mensaje=actualizada");
            exit();

        }

    }

}


// PAGINACIÓN DE PROMOCIONES

require_once __DIR__ . '/../../php/paginacion.php';

// Obtener todas las promociones activas
$consulta = obtenerPromocionesActivas($conexion);

if (!$consulta) {
    die("Error al cargar las promociones: " . $conexion->error);
}

// Guardar las promociones
$todasPromociones = $consulta->fetch_all(MYSQLI_ASSOC);

// Calcular paginación
$totalRegistros = count($todasPromociones);

$paginacion = configurarPaginacion($totalRegistros);

$pagina = $paginacion["pagina"];
$totalPaginas = $paginacion["totalPaginas"];

// Obtener solamente las promociones de la página actual
$promociones = array_slice(
    $todasPromociones,
    $paginacion["offset"],
    $paginacion["porPagina"]
);

?>

<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <title>Nuvia - Administrador</title>
    <link rel="icon" type="image/png" href="../../imagenes/logo.png">

    <link rel="stylesheet" href="../../css/bootstrap.min.css">
    <link rel="stylesheet" href="../../css/bootstrap-icons.css">

    <link rel="stylesheet" href="../../css/estilosAdmin.css">
    <link rel="stylesheet" href="../../css/navbar.css">
    <link rel="stylesheet" href="../../css/footer.css">
    <link rel="stylesheet" href="../../css/paginacion.css">
</head>

<body>
    <?php include __DIR__ . '/../includes/navbarAdmin.php'; ?>

    <main class="contenido-admin">
        <section class="encabezado-contenido">
            <div>
                <h1>Gestión de Promociones</h1>
                <p>
                    Revisa, aprueba o rechaza las promociones creadas por los CEOs.
                </p>
            </div>
        </section>

        <!-- MENSAJE DE CONFIRMACIÓN -->
        <?php if (
            isset($_GET["mensaje"]) &&
            $_GET["mensaje"] === "actualizada"
        ) { ?>

            <div class="alert alert-success" role="alert">
                El estado de la promoción fue actualizado correctamente.
            </div>

        <?php } ?>

        <!-- TABLA DE PROMOCIONES -->
        <section class="tabla-contenedor">
            <div class="table-responsive">
                <table class="table align-middle tabla-promociones">
                    <thead>
                        <tr>
                            <th scope="col">Promoción</th>
                            <th scope="col">Aerolínea</th>
                            <th scope="col">Descuento</th>
                            <th scope="col">Estado</th>
                            <th scope="col">Acciones</th>
                        </tr>
                    </thead>

                    <tbody>
                        <?php if (count($promociones) > 0) { ?>
                            <?php foreach ($promociones as $promocion) { ?>
                                <tr>
                                    <!-- PROMOCIÓN -->
                                    <td data-label="Promoción">
                                        <?php
                                        echo htmlspecialchars(
                                            $promocion["descripcionPromocion"],
                                            ENT_QUOTES,
                                            "UTF-8"
                                        );
                                        ?>
                                    </td>

                                    <!-- AEROLÍNEA -->
                                    <td data-label="Aerolínea">
                                        <?php
                                        echo htmlspecialchars(
                                            $promocion["nombreAerolinea"],
                                            ENT_QUOTES,
                                            "UTF-8"
                                        );
                                        ?>
                                    </td>

                                    <!-- DESCUENTO -->
                                    <td data-label="Descuento">
                                        <?php
                                        echo htmlspecialchars(
                                            (string) $promocion["descuentoPromocion"],
                                            ENT_QUOTES,
                                            "UTF-8"
                                        );
                                        ?>%
                                    </td>

                                    <!-- ESTADO -->
                                    <td data-label="Estado">
                                        <?php if ($promocion["estadoPromocion"] === "Pendiente") { ?>
                                            <span class="badge bg-warning text-dark">
                                                Pendiente
                                            </span>
                                        <?php } elseif ($promocion["estadoPromocion"] === "Aprobada") { ?>
                                            <span class="badge bg-success">
                                                Aprobada
                                            </span>
                                        <?php } else { ?>
                                            <span class="badge bg-danger">
                                                Rechazada
                                            </span>
                                        <?php } ?>
                                    </td>

                                    <!-- ACCIONES -->
                                    <td data-label="Acciones">

                                        <!-- VER DETALLE -->
                                        <button
                                            type="button"
                                            class="btn btn-sm btn-outline-primary"
                                            data-bs-toggle="modal"
                                            data-bs-target="#detallePromocion<?php echo (int) $promocion["codPromocion"]; ?>"
                                            aria-label="Ver detalle de la promoción <?php echo (int) $promocion["codPromocion"]; ?>"
                                            title="Ver detalle"
                                        >

                                            <i class="bi bi-eye" aria-hidden="true"></i>
                                        </button>

                                        <?php if ($promocion["estadoPromocion"] === "Pendiente") { ?>

                                            <!-- APROBAR -->
                                            <form method="POST" class="d-inline">
                                                <input
                                                    type="hidden"
                                                    name="codPromocion"
                                                    value="<?php echo (int) $promocion["codPromocion"]; ?>"
                                                >
                                                <input
                                                    type="hidden"
                                                    name="accion"
                                                    value="aprobar"
                                                >
                                                <button
                                                    type="submit"
                                                    class="btn btn-sm btn-outline-success"
                                                    aria-label="Aprobar promoción <?php echo (int) $promocion["codPromocion"]; ?>"
                                                    title="Aprobar promoción"
                                                >

                                                    <i class="bi bi-check-lg" aria-hidden="true"></i>

                                                </button>
                                            </form>

                                            <!-- RECHAZAR -->
                                            <form method="POST" class="d-inline">
                                                <input
                                                    type="hidden"
                                                    name="codPromocion"
                                                    value="<?php echo (int) $promocion["codPromocion"]; ?>"
                                                >
                                                <input
                                                    type="hidden"
                                                    name="accion"
                                                    value="rechazar"
                                                >
                                                <button
                                                    type="submit"
                                                    class="btn btn-sm btn-outline-danger"
                                                    aria-label="Rechazar promoción <?php echo (int) $promocion["codPromocion"]; ?>"
                                                    title="Rechazar promoción"
                                                >
                                                    <i class="bi bi-x-lg" aria-hidden="true"></i>

                                                </button>
                                            </form>
                                        <?php } ?>
                                    </td>
                                </tr>
                            <?php } ?>
                        <?php } else { ?>
                            <tr>
                                <td colspan="5" class="text-center py-4">
                                    <i class="bi bi-percent fs-2" aria-hidden="true"></i>
                                    <p class="mt-2 mb-0">
                                        No hay promociones registradas.
                                    </p>
                                </td>
                            </tr>
                        <?php } ?>
                    </tbody>
                </table>
            </div>
        </section>

        <?php include __DIR__ . '/../../php/paginacionVista.php'; ?>

        <?php foreach ($promociones as $promocion) { ?>

            <div
                class="modal fade"
                id="detallePromocion<?php echo (int) $promocion["codPromocion"]; ?>"
                tabindex="-1"
                aria-labelledby="tituloPromocion<?php echo (int) $promocion["codPromocion"]; ?>"
                aria-hidden="true"
            >

                <div class="modal-dialog modal-dialog-centered">
                    <div class="modal-content">
                        <div class="modal-header">
                            <h5
                                class="modal-title"
                                id="tituloPromocion<?php echo (int) $promocion["codPromocion"]; ?>"
                            >
                                Detalle de promoción
                            </h5>

                            <button
                                type="button"
                                class="btn-close"
                                data-bs-dismiss="modal"
                                aria-label="Cerrar"
                            ></button>
                        </div>

                        <!-- CONTENIDO -->
                        <div class="modal-body">
                            <p>
                                <strong>Código:</strong>

                                <?php echo (int) $promocion["codPromocion"]; ?>
                            </p>

                            <p>
                                <strong>Aerolínea:</strong>
                                <?php
                                echo htmlspecialchars(
                                    $promocion["nombreAerolinea"],
                                    ENT_QUOTES,
                                    "UTF-8"
                                );
                                ?>
                            </p>

                            <p>
                                <strong>CEO:</strong>
                                <?php
                                echo !empty($promocion["nombreCeo"])
                                    ? htmlspecialchars(
                                        $promocion["nombreCeo"],
                                        ENT_QUOTES,
                                        "UTF-8"
                                    )
                                    : "Sin CEO asignado";
                                ?>
                            </p>

                            <p>
                                <strong>Descripción:</strong>
                                <?php
                                echo htmlspecialchars(
                                    $promocion["descripcionPromocion"],
                                    ENT_QUOTES,
                                    "UTF-8"
                                );
                                ?>
                            </p>

                            <p>
                                <strong>Descuento:</strong>
                                <?php
                                echo htmlspecialchars(
                                    (string) $promocion["descuentoPromocion"],
                                    ENT_QUOTES,
                                    "UTF-8"
                                );
                                ?>%
                            </p>

                            <p>
                                <strong>Estado:</strong>
                                <?php
                                echo htmlspecialchars(
                                    $promocion["estadoPromocion"],
                                    ENT_QUOTES,
                                    "UTF-8"
                                );
                                ?>
                            </p>
                        </div>

                        <!-- BOTONES -->
                        <div class="modal-footer">

                            <button
                                type="button"
                                class="btn btn-secondary"
                                data-bs-dismiss="modal"
                            >
                                Cerrar
                            </button>

                            <?php if ($promocion["estadoPromocion"] === "Pendiente") { ?>

                                <!-- RECHAZAR -->
                                <form method="POST">
                                    <input
                                        type="hidden"
                                        name="codPromocion"
                                        value="<?php echo (int) $promocion["codPromocion"]; ?>"
                                    >
                                    <input
                                        type="hidden"
                                        name="accion"
                                        value="rechazar"
                                    >
                                    <button
                                        type="submit"
                                        class="btn btn-danger"
                                    >
                                        Rechazar
                                    </button>
                                </form>

                                <!-- APROBAR -->
                                <form method="POST">
                                    <input
                                        type="hidden"
                                        name="codPromocion"
                                        value="<?php echo (int) $promocion["codPromocion"]; ?>"
                                    >
                                    <input
                                        type="hidden"
                                        name="accion"
                                        value="aprobar"
                                    >
                                    <button
                                        type="submit"
                                        class="btn btn-success"
                                    >
                                        Aprobar
                                    </button>
                                </form>
                            <?php } ?>
                        </div>
                    </div>
                </div>
            </div>
        <?php } ?>
    </main>

    <?php include __DIR__ . '/../../includes/footer.php'; ?>

    <script src="<?= htmlspecialchars(
        urlApp('js/bootstrap.bundle.min.js'),
        ENT_QUOTES,
        'UTF-8'
    ) ?>"></script>

</body>

</html>

<?php

$conexion->close();

?>