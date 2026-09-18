<?php

session_start();

if (!isset($_SESSION["tipoUsuario"]) || $_SESSION["tipoUsuario"] != "administrador") {

    header("Location: ../../inicioSesion.php");

    exit();

}

include "../../php/conexionBD.php";
include "../../php/consultasPromociones.php";
include "../../php/consultasActividad.php";


if ($_SERVER["REQUEST_METHOD"] == "POST") {

    $codPromocion = intval($_POST["codPromocion"]);

    $accion = $_POST["accion"] ?? "";

    if ($accion == "aprobar") {

        $nuevoEstado = "Aprobada";

    } elseif ($accion == "rechazar") {

        $nuevoEstado = "Rechazada";

    } else {

        $nuevoEstado = "";

    }


    if ($nuevoEstado != "") {

        if (cambiarEstadoPromocion($conexion, $codPromocion, $nuevoEstado)) {

            if ($nuevoEstado == "Aprobada") {

                $accionActividad = "Aprobó la promoción " . $codPromocion;

            } else {

                $accionActividad = "Rechazó la promoción " . $codPromocion;

            }

            registrarActividad($conexion, "Administrador", $accionActividad);

            header("Location: gestionPromociones.php?mensaje=actualizada");

            exit();

        }

    }

}

// OBTENER PROMOCIONES

$consulta = obtenerPromocionesActivas($conexion);

if (!$consulta) {

    die( "Error al cargar las promociones: ". $conexion->error);

}

?>


<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Nuvia - Administrador</title>
    <link rel="stylesheet" href="../../css/bootstrap.min.css">
    <link rel="stylesheet" href="../../css/bootstrap-icons.css">
    <link rel="stylesheet" href="../../css/estilos-admin.css">
    <link rel="icon" type="image/png" href="../../imagenes/logo.png">
</head>
<body>

<?php include "../includes/navbarAdmin.php"; ?>

<main class="contenido-admin">

    <section class="encabezado-contenido">
        <div>
            <h1>Gestión de Promociones</h1>
            <p>Revisa, aprueba o rechaza las promociones creadas por los CEOs.</p>
        </div>
    </section>

    <?php if (isset($_GET["mensaje"])) { ?>
        <div class="alert alert-success">
            El estado de la promoción fue actualizado correctamente.
        </div>
    <?php } ?>

    <section class="tabla-contenedor">
        <div class="table-responsive">
            <table class="table align-middle">
                <thead>
                    <tr>
                        <th>Promoción</th>
                        <th>Aerolínea</th>
                        <th>Descuento</th>
                        <th>Estado</th>
                        <th>Acciones</th>
                    </tr>
                </thead>
                <tbody>

                <?php if ($consulta->num_rows > 0) { ?>
                    <?php while ($promocion = $consulta->fetch_assoc()) { ?>

                        <tr>
                            <td><?php echo htmlspecialchars($promocion["descripcionPromocion"]); ?></td>
                            <td><?php echo htmlspecialchars($promocion["nombreAerolinea"]); ?></td>
                            <td><?php echo $promocion["descuentoPromocion"]; ?>%</td>
                            <td>
                                <?php if ($promocion["estadoPromocion"] == "Pendiente") { ?>
                                    <span class="badge bg-warning text-dark">Pendiente</span>
                                <?php } elseif ($promocion["estadoPromocion"] == "Aprobada") { ?>
                                    <span class="badge bg-success">Aprobada</span>
                                <?php } else { ?>
                                    <span class="badge bg-danger">Rechazada</span>
                                <?php } ?>
                            </td>
                            <td>
                                <button
                                    type="button"
                                    class="btn btn-sm btn-outline-primary"
                                    data-bs-toggle="modal"
                                    data-bs-target="#detallePromocion<?php echo $promocion["codPromocion"]; ?>"
                                    title="Ver detalle"
                                >
                                    <i class="bi bi-eye"></i>
                                </button>

                                <?php if ($promocion["estadoPromocion"] == "Pendiente") { ?>

                                    <form method="POST" class="d-inline">
                                        <input type="hidden" name="codPromocion" value="<?php echo $promocion["codPromocion"]; ?>">
                                        <input type="hidden" name="accion" value="aprobar">
                                        <button type="submit" class="btn btn-sm btn-outline-success" title="Aprobar promoción">
                                            <i class="bi bi-check-lg"></i>
                                        </button>
                                    </form>

                                    <form method="POST" class="d-inline">
                                        <input type="hidden" name="codPromocion" value="<?php echo $promocion["codPromocion"]; ?>">
                                        <input type="hidden" name="accion" value="rechazar">
                                        <button type="submit" class="btn btn-sm btn-outline-danger" title="Rechazar promoción">
                                            <i class="bi bi-x-lg"></i>
                                        </button>
                                    </form>

                                <?php } ?>
                            </td>
                        </tr>

                        <div class="modal fade" id="detallePromocion<?php echo $promocion["codPromocion"]; ?>" tabindex="-1" aria-hidden="true">
                            <div class="modal-dialog modal-dialog-centered">
                                <div class="modal-content">
                                    <div class="modal-header">
                                        <h5 class="modal-title">Detalle de promoción</h5>
                                        <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                                    </div>

                                    <div class="modal-body">

                                        <p>
                                            <strong>Código:</strong>
                                            <?php echo $promocion["codPromocion"]; ?>
                                        </p>

                                        <p>
                                            <strong>Aerolínea:</strong>
                                            <?php echo htmlspecialchars($promocion["nombreAerolinea"]); ?>
                                        </p>

                                        <p>
                                            <strong>CEO:</strong>

                                            <?php

                                            echo $promocion["nombreCeo"]
                                                ? htmlspecialchars($promocion["nombreCeo"])
                                                : "Sin CEO asignado";

                                            ?>

                                        </p>

                                        <p>
                                            <strong>Descripción:</strong>
                                            <?php echo htmlspecialchars($promocion["descripcionPromocion"]); ?>
                                        </p>

                                        <p>
                                            <strong>Descuento:</strong>
                                            <?php echo $promocion["descuentoPromocion"]; ?>%
                                        </p>

                                        <p>
                                            <strong>Estado:</strong>
                                            <?php echo htmlspecialchars($promocion["estadoPromocion"]); ?>
                                        </p>

                                    </div>

                                    <div class="modal-footer">
                                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cerrar</button>

                                        <?php if ($promocion["estadoPromocion"] == "Pendiente") { ?>

                                            <form method="POST">
                                                <input type="hidden" name="codPromocion" value="<?php echo $promocion["codPromocion"]; ?>">
                                                <input type="hidden" name="accion" value="rechazar">
                                                <button type="submit" class="btn btn-danger">Rechazar</button>
                                            </form>

                                            <form method="POST">
                                                <input type="hidden" name="codPromocion" value="<?php echo $promocion["codPromocion"]; ?>">
                                                <input type="hidden" name="accion" value="aprobar">
                                                <button type="submit" class="btn btn-success">Aprobar</button>
                                            </form>

                                        <?php } ?>
                                    </div>
                                </div>
                            </div>
                        </div>

                    <?php } ?>

                <?php } else { ?>

                    <tr>
                        <td colspan="5" class="text-center py-4">
                            <i class="bi bi-percent" style="font-size: 30px;"></i>
                            <p class="mt-2 mb-0">No hay promociones registradas.</p>
                        </td>
                    </tr>

                <?php } ?>

                </tbody>
            </table>
        </div>
    </section>
</main>

<script src="../../js/bootstrap.bundle.min.js"></script>
</body>
</html>

<?php
$conexion->close();
?>
