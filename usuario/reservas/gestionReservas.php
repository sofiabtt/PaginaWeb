<?php

require "../includes/protegerUsuario.php";
include "../../php/consultasReservas.php";

$codUsuario = (int) $_SESSION["codUsuario"];

$desdeReserva =
    isset($_GET["desdeReserva"])
    && $_GET["desdeReserva"] === "1";

$reservaDestacada =
    isset($_GET["reserva"])
    ? (int) $_GET["reserva"]
    : 0;

$resultado = obtenerReservasUsuario($conexion, $codUsuario);

$cantidadReservas = $resultado->num_rows;

$reservas = [];

while ($filaReserva = $resultado->fetch_assoc()) {
    $reservas[] = $filaReserva;
}

// Si venimos directamente de reservar, ponemos esa reserva primero
// para que se vea junto con la línea de progreso.
if ($desdeReserva && $reservaDestacada > 0) {

    usort(
        $reservas,
        function ($a, $b) use ($reservaDestacada) {

            $aEsDestacada =
                (int) $a["codReserva"] === $reservaDestacada;

            $bEsDestacada =
                (int) $b["codReserva"] === $reservaDestacada;

            if ($aEsDestacada && !$bEsDestacada) {
                return -1;
            }

            if (!$aEsDestacada && $bEsDestacada) {
                return 1;
            }

            return 0;
        }
    );
}

?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8"><meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Nuvia - Mis reservas</title>
    <link rel="stylesheet" href="../../css/bootstrap.min.css">
    <link rel="stylesheet" href="../../css/bootstrap-icons.css">
    <link rel="stylesheet" href="../../css/estilos-usuario.css">
    <link rel="stylesheet" href="../../css/progresoReserva.css">

    <link rel="stylesheet" href="../../css/navbar.css">
</head>
<body>
    <?php include ("../../includes/navbar.php"); ?>
    <main class="contenido-admin gestion-reservas">

        <?php if ($desdeReserva && $reservaDestacada > 0) { ?>
            <section class="progreso-reserva">
                <div class="progreso-titulo">
                    <h2 class="meta-valor">Obtener vuelo</h2>
                </div>

                <div class="timeline-pasos">
                    <div class="timeline-paso completado">
                        <span class="timeline-punto"></span>
                        <div class="timeline-contenido">
                            <span class="timeline-etiqueta">Paso 1</span>
                            <div class="timeline-nombre">Elegir vuelo de ida</div>
                            <p class="timeline-detalle">Vuelo seleccionado.</p>
                        </div>
                    </div>

                    <div class="timeline-paso completado">
                        <span class="timeline-punto"></span>
                        <div class="timeline-contenido">
                            <span class="timeline-etiqueta">Paso 2</span>
                            <div class="timeline-nombre">Completar la reserva</div>
                            <p class="timeline-detalle">Datos cargados y reserva creada.</p>
                        </div>
                    </div>

                    <div class="timeline-paso activo">
                        <span class="timeline-punto"></span>
                        <div class="timeline-contenido">
                            <span class="timeline-etiqueta">Paso 3</span>
                            <div class="timeline-nombre">Finalizar en Mis Reservas</div>
                            <p class="timeline-detalle">
                                Confirmá la reserva destacada para completar el proceso.
                            </p>
                        </div>
                    </div>
                </div>
            </section>
        <?php } ?>

        <section class="encabezado-contenido"><div><h1>Mis reservas</h1><p>Consultá, confirmá o cancelá tus reservas.</p></div></section>
        <?php if (isset($_GET["creada"])) { ?><div class="alert alert-success">La reserva se creó y quedó pendiente de pago.</div><?php } ?>
        <?php if (isset($_GET["confirmada"])) { ?><div class="alert alert-success">La reserva se confirmó correctamente.</div><?php } ?>
        <?php if (isset($_GET["cancelada"])) { ?><div class="alert alert-success">La reserva se canceló correctamente.</div><?php } ?>
        <?php if (isset($_GET["error"])) { ?><div class="alert alert-danger">No se pudo realizar la operación. Recordá que solo podés cancelar hasta 72 horas antes.</div><?php } ?>
        
        <p>
            Cantidad de reservas encontradas:
            <?php echo $cantidadReservas; ?>
        </p>
        <section class="tabla-contenedor">
            <table class="table align-middle">
                <thead>
                    <tr>
                        <th>Código</th>
                        <th>Vuelo</th>
                        <th>Salida</th>
                        <th>Precio</th>
                        <th>Estado</th>
                        <th></th>
                    </tr>
                </thead>

                <tbody>

                    <?php if ($cantidadReservas === 0) { ?>

                        <tr>
                            <td
                                colspan="6"
                                class="text-center text-muted py-4"
                            >
                                No tenés reservas activas.
                            </td>
                        </tr>

                    <?php } ?>


                    <?php foreach ($reservas as $reserva) { ?>

                        <?php
                            $esReservaDestacada =
                                $desdeReserva
                                && $reservaDestacada > 0
                                && (int) $reserva["codReserva"] === $reservaDestacada;
                        ?>

                        <tr
                            id="<?php echo $esReservaDestacada ? 'reserva-destacada' : ''; ?>"
                            class="fila-reserva<?php echo $esReservaDestacada ? ' reserva-destacada' : ''; ?>"
                            onclick="window.location.href='detalleReserva.php?id=<?php echo (int) $reserva["codReserva"]; ?>'"
                        >


                            <td class="align-middle">
                                <?php
                                echo (int) $reserva["codReserva"];
                                ?>

                                <?php if ($esReservaDestacada) { ?>
                                    <br>
                                    <span class="marca-reserva-nueva">
                                        Recién reservada
                                    </span>
                                <?php } ?>
                            </td>


                            <td class="align-middle">

                                <?php
                                echo htmlspecialchars(
                                    $reserva["origenVuelo"]
                                    . " - "
                                    . $reserva["destinoVuelo"]
                                );
                                ?>

                                <br>

                                <small>
                                    <?php
                                    echo htmlspecialchars(
                                        $reserva["nombreAerolinea"]
                                    );
                                    ?>
                                </small>

                            </td>


                            <td class="align-middle">

                                <?php
                                echo date(
                                    "d/m/Y",
                                    strtotime(
                                        $reserva["fechaSalidaVuelo"]
                                    )
                                );

                                echo " ";

                                echo htmlspecialchars(
                                    $reserva["horaSalidaVuelo"]
                                );
                                ?>

                            </td>


                            <td class="align-middle">

                                $
                                <?php
                                echo number_format(
                                    (float) $reserva["precioFinalReserva"],
                                    0,
                                    ",",
                                    "."
                                );
                                ?>

                            </td>


                            <td class="align-middle">

                                <span
                                    class="badge <?php
                                    echo $reserva["estadoReserva"] === "confirmada"
                                        ? "bg-success"
                                        : "bg-warning text-dark";
                                    ?>"
                                >

                                    <?php
                                    echo htmlspecialchars(
                                        ucfirst(
                                            $reserva["estadoReserva"]
                                        )
                                    );
                                    ?>

                                </span>

                            </td>


                            <td class="align-middle">

                                <div
                                    class="acciones-reserva"
                                    onclick="event.stopPropagation();"
                                >

                                    <?php
                                    if (
                                        $reserva["estadoReserva"]
                                        === "pendiente de pago"
                                    ) {
                                    ?>

                                        <!-- CONFIRMAR -->

                                        <form
                                            method="POST"
                                            action="confirmarReserva.php"
                                        >

                                            <input
                                                type="hidden"
                                                name="id"
                                                value="<?php
                                                echo (int) $reserva["codReserva"];
                                                ?>"
                                            >

                                            <button
                                                class="btn btn-sm btn-success"
                                                type="submit"
                                            >
                                                Confirmar
                                            </button>

                                        </form>


                                        <!-- CANCELAR -->

                                        <button
                                            type="button"
                                            class="btn btn-sm btn-outline-danger"
                                            onclick="abrirModalCancelar(
                                                <?php
                                                echo (int) $reserva["codReserva"];
                                                ?>
                                            )"
                                        >
                                            Cancelar
                                        </button>

                                    <?php
                                    }
                                    ?>

                                </div>

                            </td>

                        </tr>

                    <?php } ?>

                </tbody>
            </table>
        </section>
    </main>
    <div id="modalCancelar" class="modal-cancelar">

    <div class="modal-cancelar-contenido">

        <div class="modal-cancelar-icono">
            <i class="bi bi-exclamation-circle"></i>
        </div>

        <h3>Cancelar reserva</h3>

        <p>
            ¿Estás segura de que querés cancelar esta reserva?
        </p>

        <p class="texto-modal-secundario">
            Esta acción no se puede deshacer.
        </p>

        <div class="modal-cancelar-botones">

            <button
                type="button"
                class="btn-volver"
                onclick="cerrarModalCancelar()"
            >
                Volver
            </button>

            <form
                id="formCancelarReserva"
                method="POST"
                action="cancelarReserva.php"
            >

                <input
                    type="hidden"
                    name="id"
                    id="idReservaCancelar"
                >

                <button
                    type="submit"
                    class="btn-confirmar-cancelacion"
                >
                    Sí, cancelar
                </button>

            </form>

        </div>

    </div>

</div>
    <script src="../../js/bootstrap.bundle.min.js"></script>
    <script>

function abrirModalCancelar(idReserva) {

    document.getElementById(
        "idReservaCancelar"
    ).value = idReserva;

    document.getElementById(
        "modalCancelar"
    ).style.display = "flex";

}


function cerrarModalCancelar() {

    document.getElementById(
        "modalCancelar"
    ).style.display = "none";

}

</script>
</body>
</html>
<?php $consulta->close(); 
