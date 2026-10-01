<?php

require "../includes/protegerUsuario.php";
include "../../php/consultasReservas.php";

$codUsuario = (int) $_SESSION["codUsuario"];

// Medios de pago y opciones permitidas
$metodosPago = [
    "tarjeta"       => ["nombre" => "Tarjeta de crédito o débito", "icono" => "bi-credit-card-2-front"],
    "transferencia" => ["nombre" => "Transferencia bancaria",      "icono" => "bi-bank"],
    "billetera"     => ["nombre" => "Billetera virtual",           "icono" => "bi-wallet2"],
];

$entidades = [
    "tarjeta" => [
        "visa" => "Visa", "mastercard" => "Mastercard",
        "amex" => "American Express",
    ],
    "transferencia" => [
        "nacion" => "Banco Nación", "provincia" => "Banco Provincia",
        "galicia" => "Banco Galicia", "santander" => "Santander",
    ],
    "billetera" => [
        "mercadopago" => "Mercado Pago", "uala" => "Ualá",
        "naranjax" => "Naranja X", "modo" => "MODO",
    ],
];

//Token de seguridad (CSRF) 
if (empty($_SESSION["csrfPago"])) {
    $_SESSION["csrfPago"] = bin2hex(random_bytes(32));
}

//Datos que llegan por GET (al abrir la página) o POST (al pagar) 
$datos = $_SERVER["REQUEST_METHOD"] === "POST" ? $_POST : $_GET;

$id = (int) ($datos["id"] ?? 0);

if ($id <= 0) {
    header("Location: gestionReservas.php");
    exit();
}

$reserva = obtenerDetalleReserva($conexion, $id, $codUsuario);

if (!$reserva) {
    header("Location: gestionReservas.php");
    exit();
}

//Datos del proceso "ida y vuelta": se reciben y se devuelven tal cual para que gestionReservas.php siga mostrando el progreso
$contexto = [];

if (($datos["desdeReserva"] ?? "") === "1" && ($datos["tipoViaje"] ?? "") === "idaVuelta") {
    $contexto = [
        "desdeReserva" => "1",
        "tipoViaje"    => "idaVuelta",
        "tramo"        => "vuelta",
        "reservaIda"   => (int) ($datos["reservaIda"] ?? 0),
        "reserva"      => (int) ($datos["reserva"] ?? 0),
    ];
}

$esPendiente = $reserva["estadoReserva"] === "pendiente de pago";

//Si ya está paga, no se muestra el formulario 
if (!$esPendiente && $_SERVER["REQUEST_METHOD"] === "GET") {
    header("Location: detalleReserva.php?id=" . $id);
    exit();
}

//Medio de pago guardado por el usuario 
$metodoGuardado = "";
$entidadGuardada = "";
$datoGuardado = "";
$ultimosGuardados = "";

$consulta = $conexion->prepare(
    "SELECT medioPago, entidadPago, datoPago, ultimosDigitos
     FROM PreferenciasPago WHERE codUsuario = ?"
);
$consulta->bind_param("i", $codUsuario);
$consulta->execute();
$preferencia = $consulta->get_result()->fetch_assoc();
$consulta->close();

if ($preferencia && isset($metodosPago[$preferencia["medioPago"]])) {
    $metodoGuardado = $preferencia["medioPago"];
    $entidadGuardada = (string) $preferencia["entidadPago"];
    $datoGuardado = (string) $preferencia["datoPago"];
    $ultimosGuardados = (string) $preferencia["ultimosDigitos"];
}

$metodoSeleccionado = $metodoGuardado;
$error = "";

//Procesar el pago (solo si el formulario trae el token) 
if ($_SERVER["REQUEST_METHOD"] === "POST" && isset($_POST["csrf"])) {
    $metodoSeleccionado = $_POST["medioPago"] ?? "";
    $entidad = $_POST["entidad_" . $metodoSeleccionado] ?? "";
    $dato = trim($_POST["dato_" . $metodoSeleccionado] ?? "");

    if (!hash_equals($_SESSION["csrfPago"], $_POST["csrf"])) {
        $error = "La sesión venció. Recargá la página e intentá nuevamente.";
    } elseif (!isset($metodosPago[$metodoSeleccionado])) {
        $error = "Seleccioná un medio de pago.";
    } elseif (!isset($entidades[$metodoSeleccionado][$entidad])) {
        $error = "Elegí una opción de la lista para continuar con el pago.";
    } elseif ($dato === "") {
        $error = "Completá todos los datos del medio de pago.";
    } elseif (!$esPendiente) {
        $error = "Esta reserva ya no está pendiente de pago.";
    } else {
        //Confirmar la reserva (solo si sigue pendiente de pago)
        $actualizar = $conexion->prepare(
            "UPDATE Reservas SET estadoReserva = 'confirmada'
             WHERE codReserva = ? AND codUsuario = ? AND estadoReserva = 'pendiente de pago'"
        );
        $actualizar->bind_param("ii", $id, $codUsuario);
        $actualizar->execute();
        $confirmada = $actualizar->affected_rows === 1;
        $actualizar->close();

        if (!$confirmada) {
            $error = "No se pudo procesar el pago. Verificá la reserva e intentá otra vez.";
        } else {
            //Recordar (o borrar) los datos del medio de pago
            if (isset($_POST["guardarPreferencia"])) {
                /* Del número de tarjeta se guardan SOLO los últimos 4 dígitos.
                   El vencimiento y el código de seguridad nunca se guardan. */
                $digitos = preg_replace("/\D/", "", $_POST["numeroTarjeta"] ?? "");
                $ultimos = ($metodoSeleccionado === "tarjeta" && strlen($digitos) >= 4)
                    ? substr($digitos, -4)
                    : "";
                $dato = mb_substr($dato, 0, 100);

                $guardar = $conexion->prepare(
                    "INSERT INTO PreferenciasPago
                        (codUsuario, medioPago, entidadPago, datoPago, ultimosDigitos)
                     VALUES (?, ?, ?, ?, ?)
                     ON DUPLICATE KEY UPDATE
                        medioPago = VALUES(medioPago),
                        entidadPago = VALUES(entidadPago),
                        datoPago = VALUES(datoPago),
                        ultimosDigitos = VALUES(ultimosDigitos)"
                );
                $guardar->bind_param("issss", $codUsuario, $metodoSeleccionado, $entidad, $dato, $ultimos);
            } else {
                $guardar = $conexion->prepare("DELETE FROM PreferenciasPago WHERE codUsuario = ?");
                $guardar->bind_param("i", $codUsuario);
            }
            $guardar->execute();
            $guardar->close();

            unset($_SESSION["csrfPago"]);

            header("Location: gestionReservas.php?" . http_build_query(["confirmada" => 1] + $contexto));
            exit();
        }
    }
}

//valores de los campos
$entidadActual = [];
$datoActual = [];

foreach ($metodosPago as $clave => $metodo) {
    $esGuardado = $metodoGuardado === $clave;
    $entidadActual[$clave] = $_POST["entidad_" . $clave] ?? ($esGuardado ? $entidadGuardada : "");
    $datoActual[$clave] = $_POST["dato_" . $clave] ?? ($esGuardado ? $datoGuardado : "");
}

// El botón "Volver" conserva los datos del proceso
$queryVolver = $contexto ? "?" . http_build_query($contexto) : "";
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Nuvia - Pagar reserva</title>
    <link rel="stylesheet" href="../../css/bootstrap.min.css">
    <link rel="stylesheet" href="../../css/bootstrap-icons.css">
    <link rel="stylesheet" href="../../css/navbar.css">
    <link rel="stylesheet" href="../../css/footer.css">
    <link rel="stylesheet" href="../../css/pago.css">
</head>
<body>
    <?php include "../../includes/navbar.php"; ?>

    <main class="pago-contenedor" id="contenido">
        <section class="pago-card" aria-labelledby="tituloPago">
            <a href="gestionReservas.php<?= htmlspecialchars($queryVolver) ?>" class="pago-volver">
                <i class="bi bi-arrow-left" aria-hidden="true"></i> Volver a mis reservas
            </a>

            <h1 id="tituloPago">Pagar reserva #<?= (int) $reserva["codReserva"] ?></h1>
            <p class="pago-subtitulo">Elegí cómo querés pagar. Todos los campos son obligatorios.</p>

            <dl class="pago-resumen">
                <div>
                    <dt>Vuelo</dt>
                    <dd>
                        <?= htmlspecialchars($reserva["origenVuelo"]) ?>
                        <i class="bi bi-arrow-right" aria-label="a"></i>
                        <?= htmlspecialchars($reserva["destinoVuelo"]) ?>
                    </dd>
                </div>
                <div>
                    <dt>Fecha de salida</dt>
                    <dd><?= date("d/m/Y", strtotime($reserva["fechaSalidaVuelo"])) ?></dd>
                </div>
                <div>
                    <dt>Aerolínea</dt>
                    <dd><?= htmlspecialchars($reserva["nombreAerolinea"]) ?></dd>
                </div>
                <div class="pago-resumen-total">
                    <dt>Total a pagar</dt>
                    <dd class="pago-total">
                        $<?= number_format((float) $reserva["precioFinalReserva"], 0, ",", ".") ?>
                    </dd>
                </div>
            </dl>

            <?php if ($error !== ""): ?>
                <div class="alert alert-danger" role="alert">
                    <i class="bi bi-exclamation-triangle-fill" aria-hidden="true"></i>
                    <?= htmlspecialchars($error) ?>
                </div>
            <?php endif; ?>

            <?php if (!$esPendiente): ?>
                <div class="alert alert-info" role="status">
                    Esta reserva ya no está pendiente de pago.
                </div>
            <?php else: ?>
                <form method="POST" action="pagarReserva.php">
                    <input type="hidden" name="id" value="<?= (int) $id ?>">
                    <input type="hidden" name="csrf" value="<?= htmlspecialchars($_SESSION["csrfPago"]) ?>">
                    <?php foreach ($contexto as $clave => $valor): ?>
                        <input type="hidden" name="<?= htmlspecialchars($clave) ?>" value="<?= htmlspecialchars((string) $valor) ?>">
                    <?php endforeach; ?>

                    <fieldset class="pago-metodos">
                        <legend>Medio de pago</legend>

                        <?php foreach ($metodosPago as $valor => $metodo): ?>
                            <label class="pago-opcion">
                                <input type="radio" name="medioPago" value="<?= $valor ?>"
                                    <?= $metodoSeleccionado === $valor ? "checked" : "" ?> required>
                                <i class="bi <?= $metodo["icono"] ?>" aria-hidden="true"></i>
                                <span><?= htmlspecialchars($metodo["nombre"]) ?></span>
                            </label>
                        <?php endforeach; ?>
                    </fieldset>

                    <!-- ============ TARJETA ============ -->
                    <div class="pago-datos" data-pago="tarjeta">
                        <p class="pago-ayuda">
                            Usá datos ficticios. Por ejemplo: <strong>4242 4242 4242 4242</strong>.
                            De la tarjeta solo se guardan los últimos 4 dígitos; el vencimiento
                            y el código de seguridad nunca se guardan.
                        </p>
                        <?php if ($metodoGuardado === "tarjeta" && $ultimosGuardados !== ""): ?>
                            <p class="pago-ayuda">
                                Tarjeta guardada terminada en <strong><?= htmlspecialchars($ultimosGuardados) ?></strong>.
                            </p>
                        <?php endif; ?>

                        <label class="form-label" for="entidad-tarjeta">Tipo de tarjeta</label>
                        <select class="form-select" id="entidad-tarjeta" name="entidad_tarjeta">
                            <option value="">Elegí una opción</option>
                            <?php foreach ($entidades["tarjeta"] as $v => $n): ?>
                                <option value="<?= $v ?>" <?= $entidadActual["tarjeta"] === $v ? "selected" : "" ?>>
                                    <?= htmlspecialchars($n) ?>
                                </option>
                            <?php endforeach; ?>
                        </select>

                        <label class="form-label" for="numeroTarjeta">Número de tarjeta</label>
                        <input class="form-control" id="numeroTarjeta" name="numeroTarjeta" type="text" inputmode="numeric"
                            autocomplete="cc-number" maxlength="19"
                            placeholder="<?= $ultimosGuardados !== "" ? "•••• •••• •••• " . htmlspecialchars($ultimosGuardados) : "0000 0000 0000 0000" ?>">

                        <div class="pago-fila">
                            <div>
                                <label class="form-label" for="vencimiento">Vencimiento</label>
                                <input class="form-control" id="vencimiento" type="text" inputmode="numeric"
                                    autocomplete="cc-exp" maxlength="5" placeholder="MM/AA">
                            </div>
                            <div>
                                <label class="form-label" for="codigoSeguridad">Código de seguridad</label>
                                <input class="form-control" id="codigoSeguridad" type="password" inputmode="numeric"
                                    autocomplete="cc-csc" maxlength="4" placeholder="123">
                            </div>
                        </div>

                        <label class="form-label" for="titularTarjeta">Nombre del titular</label>
                        <input class="form-control" id="titularTarjeta" name="dato_tarjeta" type="text"
                            autocomplete="cc-name" placeholder="Como figura en la tarjeta"
                            maxlength="100" value="<?= htmlspecialchars($datoActual["tarjeta"]) ?>">
                    </div>

                    <!-- ============ TRANSFERENCIA ============ -->
                    <div class="pago-datos" data-pago="transferencia">
                        <div class="pago-cuenta" aria-label="Datos de la cuenta de destino (ficticios)">
                            <p><span>Titular</span> <strong>Nuvia Viajes S.A.</strong></p>
                            <p><span>CBU</span> <strong>0000003100012345678901</strong></p>
                            <p><span>Alias</span> <strong>nuvia.viajes</strong></p>
                        </div>
                        <p class="pago-ayuda">Cuentas ficticias, solo para esta simulación.</p>

                        <label class="form-label" for="entidad-transferencia">Banco desde el que transferís</label>
                        <select class="form-select" id="entidad-transferencia" name="entidad_transferencia">
                            <option value="">Elegí un banco</option>
                            <?php foreach ($entidades["transferencia"] as $v => $n): ?>
                                <option value="<?= $v ?>" <?= $entidadActual["transferencia"] === $v ? "selected" : "" ?>>
                                    <?= htmlspecialchars($n) ?>
                                </option>
                            <?php endforeach; ?>
                        </select>

                        <label class="form-label" for="titularCuenta">Nombre del titular de la cuenta</label>
                        <input class="form-control" id="titularCuenta" name="dato_transferencia" type="text"
                            autocomplete="name" placeholder="Nombre y apellido"
                            maxlength="100" value="<?= htmlspecialchars($datoActual["transferencia"]) ?>">
                    </div>

                    <!-- ============ BILLETERA ============ -->
                    <div class="pago-datos" data-pago="billetera">
                        <label class="form-label" for="entidad-billetera">Billetera virtual</label>
                        <select class="form-select" id="entidad-billetera" name="entidad_billetera">
                            <option value="">Elegí una billetera</option>
                            <?php foreach ($entidades["billetera"] as $v => $n): ?>
                                <option value="<?= $v ?>" <?= $entidadActual["billetera"] === $v ? "selected" : "" ?>>
                                    <?= htmlspecialchars($n) ?>
                                </option>
                            <?php endforeach; ?>
                        </select>

                        <label class="form-label" for="emailBilletera">Correo de tu cuenta</label>
                        <input class="form-control" id="emailBilletera" name="dato_billetera" type="text"
                            autocomplete="email" placeholder="ejemplo@correo.com"
                            maxlength="100" value="<?= htmlspecialchars($datoActual["billetera"]) ?>">
                    </div>

                    <label class="pago-recordar">
                        <input type="checkbox" name="guardarPreferencia" value="1"
                            <?= $metodoGuardado !== "" ? "checked" : "" ?>>
                        <span>Recordar algunos de estos datos para próximas reservas</span>
                    </label>

                    <p class="pago-aviso">
                        <i class="bi bi-shield-check" aria-hidden="true"></i>
                        Simulación de pago: no se procesa dinero.
                    </p>

                    <button class="pago-boton" type="submit">
                        Pagar $<?= number_format((float) $reserva["precioFinalReserva"], 0, ",", ".") ?>
                    </button>
                </form>
            <?php endif; ?>
        </section>
    </main>

    <?php include "../../includes/footer.php"; ?>

    <script>
    const form = document.querySelector("main form");

    if (form) {
        form.addEventListener("submit", function (e) {
            e.preventDefault();

            const boton = form.querySelector(".pago-boton");
            boton.disabled = true;
            boton.textContent = "Procesando pago...";

            setTimeout(function () {
                form.submit();
            }, 2000);
        });
    }
    </script>
    
    <script src="../../js/bootstrap.bundle.min.js"></script>
</body>
</html>

<?php $conexion->close(); ?>