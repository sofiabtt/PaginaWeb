<?php

require "../includes/protegerUsuario.php";
include "../../php/consultasReservas.php";

$codUsuario = (int) $_SESSION["codUsuario"];

$resultado = obtenerReservasUsuario($conexion, $codUsuario);

?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8"><meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Nuvia - Mis reservas</title>
    <link rel="stylesheet" href="../../css/bootstrap.min.css">
    <link rel="stylesheet" href="../../css/bootstrap-icons.css">
    <link rel="stylesheet" href="../../css/estilos-usuario.css">
    <style>

        html,
        body {
            height: auto !important;
            min-height: 100% !important;
            overflow-y: scroll !important;
            overflow-x: hidden;
        }

        body {
            position: static !important;
        }

        .contenido-admin {
            height: auto !important;
            min-height: 100vh !important;
            max-height: none !important;
            overflow: visible !important;
        }

        .tabla-contenedor {
            height: auto !important;
            max-height: none !important;
            overflow: visible !important;
        }

        </style>
    <link rel="stylesheet" href="../../css/navbar.css">
</head>
<body>
    <?php include ("../../includes/navbar.php"); ?>
    <main class="contenido-admin">
        <section class="encabezado-contenido"><div><h1>Mis reservas</h1><p>Consultá, confirmá o cancelá tus reservas.</p></div></section>
        <?php if (isset($_GET["creada"])) { ?><div class="alert alert-success">La reserva se creó y quedó pendiente de pago.</div><?php } ?>
        <?php if (isset($_GET["confirmada"])) { ?><div class="alert alert-success">La reserva se confirmó correctamente.</div><?php } ?>
        <?php if (isset($_GET["cancelada"])) { ?><div class="alert alert-success">La reserva se canceló correctamente.</div><?php } ?>
        <?php if (isset($_GET["error"])) { ?><div class="alert alert-danger">No se pudo realizar la operación. Recordá que solo podés cancelar hasta 72 horas antes.</div><?php } ?>
        
        <p>
            Cantidad de reservas encontradas:
            <?php echo $resultado->num_rows; ?>
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

                    <?php if ($resultado->num_rows === 0) { ?>

                        <tr>
                            <td
                                colspan="6"
                                class="text-center text-muted py-4"
                            >
                                No tenés reservas activas.
                            </td>
                        </tr>

                    <?php } ?>


                    <?php while ($reserva = $resultado->fetch_assoc()) { ?>

                        <tr
                            class="fila-reserva"
                            onclick="window.location.href='detalleReserva.php?id=<?php echo (int) $reserva["codReserva"]; ?>'"
                        >


                            <td class="align-middle">
                                <?php
                                echo (int) $reserva["codReserva"];
                                ?>
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
