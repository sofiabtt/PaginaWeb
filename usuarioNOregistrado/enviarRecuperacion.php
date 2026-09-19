<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);

session_start();

include "../php/conexionBD.php";

require "../vendor/autoload.php";
require "../vendor/autoload.php";

$dotenv = Dotenv\Dotenv::createImmutable(__DIR__ . '/..');
$dotenv->load();

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;




$mensaje = "";
$error = "";


if ($_SERVER["REQUEST_METHOD"] === "POST") {

    $email = trim($_POST["email"] ?? "");

    if ($email === "") {

        $error = "Ingresá tu correo electrónico.";

    } else {

        // Buscar usuario por email
        $consulta = $conexion->prepare("
            SELECT
                codUsuario,
                nombreUsuario,
                emailUsuario
            FROM Usuarios
            WHERE emailUsuario = ?
        ");

        $consulta->bind_param("s", $email);

        $consulta->execute();

        $resultado = $consulta->get_result();

        $usuario = $resultado->fetch_assoc();


        if ($usuario) {

            // Crear token seguro
            $token = bin2hex(random_bytes(32));

            // El enlace vence en 1 hora
            $expiracion = date(
                "Y-m-d H:i:s",
                strtotime("+1 hour")
            );


            // Guardar token en la base de datos
            $actualizar = $conexion->prepare("
                UPDATE Usuarios
                SET
                    tokenRecuperacion = ?,
                    expiracionTokenRecuperacion = ?
                WHERE codUsuario = ?
            ");

            $actualizar->bind_param(
                "ssi",
                $token,
                $expiracion,
                $usuario["codUsuario"]
            );

            $actualizar->execute();


            // Link que recibirá el usuario por correo
            $linkRecuperacion =
            "http://localhost/PaginaWeb/usuarioNOregistrado/nuevaContrasena.php?token="
            . urlencode($token);


            // ===========================
            // ENVIAR EMAIL CON PHPMAILER
            // ===========================

            try {

                $mail = new PHPMailer(true);

                // IMPORTANTE:
                // Acá tenés que poner LA MISMA configuración
                // que ya usás para mandar emails de verificación.

                $mail->isSMTP();

                $mail->Host = "smtp.gmail.com";

                $mail->SMTPAuth = true;

                /*
                    Reemplazá estas dos líneas por
                    la configuración que ya usás en tu proyecto.
                */

                $mail->Username = $_ENV["GMAIL_USUARIO"];
                $mail->Password = $_ENV["GMAIL_PASSWORD"];

                $mail->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS;

                $mail->Port = 587;


                $mail->CharSet = "UTF-8";


                // Remitente
                $mail->setFrom(
                    $_ENV["GMAIL_USUARIO"],
                    "Nuvia"
                );


                // Destinatario
                $mail->addAddress(
                    $usuario["emailUsuario"],
                    $usuario["nombreUsuario"]
                );


                $mail->isHTML(true);

                $mail->Subject =
                    "Recuperación de contraseña - Nuvia";


                $mail->Body = "

                    <h2>Recuperación de contraseña</h2>

                    <p>
                        Hola " .
                        htmlspecialchars(
                            $usuario["nombreUsuario"]
                        ) .
                        ".
                    </p>

                    <p>
                        Recibimos una solicitud para cambiar
                        la contraseña de tu cuenta de Nuvia.
                    </p>

                    <p>
                        Hacé clic en el siguiente botón
                        para crear una nueva contraseña:
                    </p>

                    <p>
                        <a
                            href='" . $linkRecuperacion . "'
                            style='
                                display:inline-block;
                                padding:12px 20px;
                                background:#7a482b;
                                color:white;
                                text-decoration:none;
                                border-radius:8px;
                                font-weight:bold;
                            '
                        >
                            Crear nueva contraseña
                        </a>
                    </p>

                    <p>
                        Este enlace vence en 1 hora.
                    </p>

                    <p>
                        Si no solicitaste este cambio,
                        podés ignorar este correo.
                    </p>

                ";


                $mail->send();


                $mensaje =
                    "Te enviamos un correo a "
                    . htmlspecialchars($email)
                    . ". Revisá tu bandeja de entrada.";

            } catch (Exception $e) {

                $error =
                    "No se pudo enviar el correo: "
                    . $mail->ErrorInfo;
            }


        } else {

            $error =
                "No existe una cuenta asociada a ese correo.";

        }
    }
}

?>


<!DOCTYPE html>

<html lang="es">

<head>

    <meta charset="UTF-8">

    <meta
        name="viewport"
        content="width=device-width, initial-scale=1.0"
    >

    <title>Recuperar contraseña</title>

    <link
        rel="stylesheet"
        href="../css/bootstrap.min.css"
    >

    <link
        rel="stylesheet"
        href="../css/estiloshome.css"
    >
    <link
    rel="stylesheet"
    href="../css/estilosRecuperacion.css"
>

</head>


<body>


<?php include "../includes/navbar.php"; ?>

<main class="contenedor-recuperacion">

    <section class="tarjeta-recuperacion">

        <h1>
            Recuperar contraseña
        </h1>

        <p class="descripcion-recuperacion">
            Ingresá el correo asociado a tu cuenta.
            Te enviaremos un enlace para que puedas crear
            una nueva contraseña.
        </p>


        <?php if ($mensaje !== ""): ?>

            <div class="mensaje-exito">
                <?= $mensaje ?>
            </div>

        <?php endif; ?>


        <?php if ($error !== ""): ?>

            <div class="mensaje-error">
                <?= htmlspecialchars($error) ?>
            </div>

        <?php endif; ?>


        <form method="POST">

            <div class="campo-recuperacion">

                <label for="email">
                    Correo electrónico
                </label>

                <input
                    type="email"
                    id="email"
                    name="email"
                    placeholder="ejemplo@gmail.com"
                    required
                >

            </div>


            <button
                type="submit"
                class="boton-recuperacion"
            >
                Enviar enlace de recuperación
            </button>

        </form>


        <a
            href="../inicioSesion.php"
            class="volver-login"
        >
            ← Volver al inicio de sesión
        </a>

    </section>

</main>
<script src="../js/bootstrap.bundle.min.js"></script>

</body>

</html>