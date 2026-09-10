<?php

require "../includes/protegerUsuario.php";
include "../../php/conexionBD.php";

$origen = trim($_GET["origen"] ?? "");
$destino = trim($_GET["destino"] ?? "");
$fecha = trim($_GET["fecha"] ?? "");

$sql = "SELECT v.codVuelo, v.origenVuelo, v.destinoVuelo,
               v.fechaSalidaVuelo, v.horaSalidaVuelo, v.precioVuelo,
               v.asientosDisponibles, a.nombreAerolinea
        FROM Vuelos v
        INNER JOIN Aerolineas a ON a.codAerolinea = v.codAerolinea
        WHERE v.activoVuelo = 1
          AND v.asientosDisponibles > 0
          AND v.fechaSalidaVuelo >= CURDATE()";
$tipos = "";
$valores = [];

if ($origen !== "") {
    $sql .= " AND v.origenVuelo LIKE ?";
    $tipos .= "s";
    $valores[] = "%" . $origen . "%";
}
if ($destino !== "") {
    $sql .= " AND v.destinoVuelo LIKE ?";
    $tipos .= "s";
    $valores[] = "%" . $destino . "%";
}
if ($fecha !== "") {
    $sql .= " AND v.fechaSalidaVuelo = ?";
    $tipos .= "s";
    $valores[] = $fecha;
}

$sql .= " ORDER BY v.fechaSalidaVuelo, v.horaSalidaVuelo";
$consulta = $conexion->prepare($sql);
if ($tipos !== "") {
    $consulta->bind_param($tipos, ...$valores);
}
$consulta->execute();
$resultado = $consulta->get_result();

?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Nuvia - Buscar vuelos</title>
    <link rel="stylesheet" href="../../css/bootstrap.min.css">
    <link rel="stylesheet" href="../../css/bootstrap-icons.css">
    <link rel="stylesheet" href="../../css/estilos-admin.css">
    <link rel="icon" href="../../imagenes/logo.png" type="image/png">
</head>
<body>
    <?php include "../includes/navbarUsuario.php"; ?>
    <main class="contenido-admin">
        <section class="encabezado-contenido">
            <div><h1>Buscar vuelos</h1><p>Buscá por origen, destino y fecha.</p></div>
        </section>

        <section class="perfil-card mb-4">
            <form method="GET" class="row g-3 align-items-end">
                <div class="col-md-3">
                    <label for="origen" class="form-label">Origen</label>
                    <input id="origen" name="origen" class="form-control" value="<?php echo htmlspecialchars($origen); ?>">
                </div>
                <div class="col-md-3">
                    <label for="destino" class="form-label">Destino</label>
                    <input id="destino" name="destino" class="form-control" value="<?php echo htmlspecialchars($destino); ?>">
                </div>
                <div class="col-md-3">
                    <label for="fecha" class="form-label">Fecha</label>
                    <input type="date" id="fecha" name="fecha" class="form-control" min="<?php echo date('Y-m-d'); ?>" value="<?php echo htmlspecialchars($fecha); ?>">
                </div>
                <div class="col-md-3 d-flex gap-2">
                    <button class="btn btn-primary" type="submit"><i class="bi bi-search"></i> Buscar</button>
                    <a class="btn btn-outline-secondary" href="buscarVuelos.php">Limpiar</a>
                </div>
            </form>
        </section>

        <section class="tabla-contenedor">
            <table class="table align-middle">
                <thead><tr><th>Aerolínea</th><th>Origen</th><th>Destino</th><th>Salida</th><th>Precio</th><th>Asientos</th><th></th></tr></thead>
                <tbody>
                <?php if ($resultado->num_rows === 0) { ?>
                    <tr><td colspan="7" class="text-center text-muted py-4">No encontramos vuelos con esos criterios.</td></tr>
                <?php } ?>
                <?php while ($vuelo = $resultado->fetch_assoc()) { ?>
                    <tr>
                        <td><?php echo htmlspecialchars($vuelo["nombreAerolinea"]); ?></td>
                        <td><?php echo htmlspecialchars($vuelo["origenVuelo"]); ?></td>
                        <td><?php echo htmlspecialchars($vuelo["destinoVuelo"]); ?></td>
                        <td><?php echo date("d/m/Y", strtotime($vuelo["fechaSalidaVuelo"])) . " " . htmlspecialchars($vuelo["horaSalidaVuelo"]); ?></td>
                        <td>$<?php echo number_format((float) $vuelo["precioVuelo"], 0, ",", "."); ?></td>
                        <td><?php echo (int) $vuelo["asientosDisponibles"]; ?></td>
                        <td><a class="btn btn-sm btn-outline-primary" href="verVuelo.php?id=<?php echo (int) $vuelo["codVuelo"]; ?>">Ver detalle</a></td>
                    </tr>
                <?php } ?>
                </tbody>
            </table>
        </section>
    </main>
    <script src="../../js/bootstrap.bundle.min.js"></script>
</body>
</html>
<?php $consulta->close(); $conexion->close(); ?>
