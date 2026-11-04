<?php

require_once __DIR__ . '/vendor/autoload.php';

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

$dotenv = Dotenv\Dotenv::createImmutable(__DIR__ . '/..');
$dotenv->safeLoad();

$estadoContacto = '';
$claseEstado = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {

    $nombre = trim($_POST['nombre'] ?? '');
    $email = trim($_POST['email'] ?? '');
    $motivo = $_POST['motivo'] ?? '';
    $mensaje = trim($_POST['mensaje'] ?? '');

    $motivosPermitidos = [
        'reserva' => 'Consultas sobre reservas',
        'pagos' => 'Pagos y comprobantes',
        'vuelos' => 'Información sobre vuelos',
        'problema' => 'Problemas con el sitio',
        'sugerencia' => 'Sugerencias',
        'otro' => 'Otro motivo'
    ];

    if ($nombre === '' || strlen($nombre) > 120 || !filter_var($email, FILTER_VALIDATE_EMAIL) || strlen($email) > 254 || !isset($motivosPermitidos[$motivo]) || $mensaje === '' || strlen($mensaje) > 5000) {
        $estadoContacto = 'Revisá los datos ingresados e intentá nuevamente.';
        $claseEstado = 'estado-error';

    } elseif (empty($_ENV['GMAIL_USUARIO']) || empty($_ENV['GMAIL_PASSWORD'])) {
        $estadoContacto ='El servicio de contacto no está configurado todavía.';
        $claseEstado = 'estado-error';

    } else {
        $mail = new PHPMailer(true);

        try {
            $mail->isSMTP();
            $mail->Host = 'smtp.gmail.com';
            $mail->SMTPAuth = true;
            $mail->Username = $_ENV['GMAIL_USUARIO'];
            $mail->Password = $_ENV['GMAIL_PASSWORD'];
            $mail->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS;
            $mail->Port = 587;

            $mail->CharSet = 'UTF-8';

            // El remitente debe ser la cuenta autenticada.
            $mail->setFrom(
                $_ENV['GMAIL_USUARIO'],
                'Nuvia - Contacto'
            );

            // Destinatario: la casilla de Nuvia (la misma cuenta de Gmail que envía).
            $mail->addAddress($_ENV['GMAIL_USUARIO']);

            // Permite responder directamente al usuario.
            $mail->addReplyTo($email, $nombre);

            $mail->isHTML(true);
            $mail->Subject = 'Nueva consulta de contacto - Nuvia';

            $nombreSeguro = htmlspecialchars($nombre, ENT_QUOTES,'UTF-8');

            $emailSeguro = htmlspecialchars($email, ENT_QUOTES, 'UTF-8');

            $motivoSeguro = htmlspecialchars($motivosPermitidos[$motivo], ENT_QUOTES, 'UTF-8');

            $mensajeSeguro = nl2br(htmlspecialchars($mensaje, ENT_QUOTES,'UTF-8'));

            $mail->Body = "
                <h2>Nueva consulta desde Nuvia</h2>
                <p><strong>Nombre:</strong> {$nombreSeguro}</p>
                <p><strong>Correo:</strong> {$emailSeguro}</p>
                <p><strong>Motivo:</strong> {$motivoSeguro}</p>
                <p><strong>Mensaje:</strong></p>
                <p>{$mensajeSeguro}</p>
            ";

            $mail->AltBody =
                "Nueva consulta desde Nuvia\n\n" .
                "Nombre: {$nombre}\n" .
                "Correo: {$email}\n" .
                "Motivo: {$motivosPermitidos[$motivo]}\n\n" .
                "Mensaje:\n{$mensaje}";

            $mail->send();

            $estadoContacto = '¡Tu consulta fue enviada correctamente! Gracias por contactarte con Nuvia.';
            $claseEstado = 'estado-exito';

            $_POST = [];

        } catch (Exception $e) {
            // El detalle técnico queda en el registro del servidor.
            error_log('Error de contacto Nuvia: ' . $mail->ErrorInfo);

            $estadoContacto = 'No pudimos enviar tu consulta en este momento. Intentá nuevamente más tarde.';
            $claseEstado = 'estado-error';
        }
    }
}

?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <title>Contacto | Nuvia</title>
    <link rel="icon" href="imagenes/logo.png" type="image/png">

    <link rel="stylesheet" href="css/bootstrap.min.css">
    <link rel="stylesheet" href="css/bootstrap-icons.css">
    <link rel="stylesheet" href="css/navbar.css">
    <link rel="stylesheet" href="css/footer.css">
    <link rel="stylesheet" href="css/contacto.css">
</head>

<body>

    <?php include("includes/navbar.php"); ?>

    <main class="contacto">

        <!-- ENCABEZADO -->
        <section class="contacto-encabezado">

            <h2>Contactate con nosotros</h2>

            <p>
                ¿Tenés alguna consulta sobre tu viaje?
                Estamos acá para ayudarte a resolver tus dudas.
            </p>
        </section>

        <!-- CONTENIDO PRINCIPAL -->
        <section class="contacto-contenido">

            <!-- FORMULARIO -->
            <div class="contacto-formulario">

                <h3>Envianos tu consulta</h3>

                <p class="contacto-descripcion">
                    Completá el formulario y contanos en qué podemos ayudarte.
                </p>

                <form id="formContacto">

                    <div class="campo-contacto">
                        <label for="nombre">Nombre y apellido</label>
                        <input type="text" id="nombre" name="nombre" placeholder="Ingresá tu nombre" autocomplete="name" required>
                    </div>

                    <div class="campo-contacto">
                        <label for="email">Correo electrónico</label>
                        <input type="email" id="email" name="email" placeholder="ejemplo@correo.com" autocomplete="email" required>
                    </div>

                    <div class="campo-contacto">
                        <label for="motivo">Motivo de la consulta</label>

                        <select id="motivo" name="motivo" required>
                            <option value="" disabled selected>
                                Seleccioná un motivo
                            </option>
                            <option value="reserva">
                                Consultas sobre reservas
                            </option>
                            <option value="pagos">
                                Pagos y comprobantes
                            </option>
                            <option value="vuelos">
                                Información sobre vuelos
                            </option>
                            <option value="problema">
                                Problemas con el sitio
                            </option>
                            <option value="sugerencia">
                                Sugerencias
                            </option>
                            <option value="otro">Otro motivo</option>
                        </select>
                    </div>

                    <div class="campo-contacto">
                        <label for="mensaje">Mensaje</label>
                        <textarea id="mensaje" name="mensaje" rows="5" placeholder="Escribí tu consulta..." required></textarea>
                    </div>

                    <button type="submit" class="boton-contacto">
                        Enviar consulta
                        <i class="bi bi-arrow-right"></i>
                    </button>

                    <p id="estadoContacto" class="estado-contacto" role="status" aria-live="polite"></p>

                </form>
            </div>

            <!-- INFORMACIÓN DE AYUDA -->
            <aside class="contacto-ayuda">

                <div class="ayuda-intro">
                    <div class="ayuda-icono">
                        <i class="bi bi-chat-dots"></i>
                    </div>

                    <h3>¿Cómo podemos ayudarte?</h3>

                    <p>
                        Queremos que organizar tu próximo viaje
                        sea una experiencia sencilla.
                    </p>
                </div>

                <div class="ayuda-item">
                    <i class="bi bi-ticket-perforated"></i>

                    <div>
                        <h4>Reservas y pasajes</h4>
                        <p>
                            Consultá sobre tus reservas y la
                            información de tus vuelos.
                        </p>
                    </div>
                </div>

                <div class="ayuda-item">
                    <i class="bi bi-credit-card"></i>

                    <div>
                        <h4>Pagos</h4>
                        <p>
                            Envianos tus dudas relacionadas con
                            los pagos y los comprobantes.
                        </p>
                    </div>
                </div>

                <div class="ayuda-item">
                    <i class="bi bi-lightbulb"></i>

                    <div>
                        <h4>Sugerencias</h4>
                        <p>
                            Tus ideas nos ayudan a mejorar
                            la experiencia en Nuvia.
                        </p>
                    </div>
                </div>

                <div class="ayuda-nota">
                    <i class="bi bi-info-circle"></i>
                    <p>
                        Si tu consulta está relacionada con una reserva,
                        evitá incluir datos bancarios o contraseñas
                        en el mensaje.
                    </p>
                </div>

            </aside>
        </section>
    </main>

    <?php include("includes/footer.php"); ?>

</body>

<script src="/js/bootstrap.bundle.min.js"></script>

</html>