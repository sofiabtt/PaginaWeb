<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);

session_start();

include "../php/conexionBD.php";

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
                    "Recuperacion de contraseña - Nuvia";


                $mail->Body = "
                <!DOCTYPE html>
                <html lang='es'>

                <body style='
                    margin: 0;
                    padding: 0;
                    background-color: #f3f6f7;
                    font-family: Arial, Helvetica, sans-serif;
                '>

                    <div style='
                        width: 100%;
                        padding: 40px 20px;
                        box-sizing: border-box;
                    '>

                        <div style='
                            max-width: 520px;
                            margin: 0 auto;
                            background-color: #ffffff;
                            border-radius: 14px;
                            overflow: hidden;
                            box-shadow: 0 4px 15px rgba(0,0,0,0.08);
                        '>

                            <!-- ENCABEZADO -->

                            <div style='
                                background-color: #684028;
                                padding: 25px;
                                text-align: center;
                            '>

                                <h1 style='
                                    margin: 0;
                                    color: white;
                                    font-size: 30px;
                                '>
                                    Nuvia
                                </h1>

                            </div>


                            <!-- CONTENIDO -->

                            <div style='
                                padding: 35px;
                                text-align: center;
                            '>

                                <h2 style='
                                    color: #684028;
                                    margin-top: 0;
                                    margin-bottom: 20px;
                                    font-size: 24px;
                                '>
                                    Recuperá tu contraseña
                                </h2>


                                <p style='
                                    color: #333333;
                                    font-size: 16px;
                                    line-height: 1.6;
                                    margin-bottom: 12px;
                                '>
                                    Hola <strong>"
                                    . htmlspecialchars($usuario["nombreUsuario"])
                                    . "</strong>,
                                </p>


                                <p style='
                                    color: #555555;
                                    font-size: 15px;
                                    line-height: 1.6;
                                    margin-bottom: 28px;
                                '>
                                    Recibimos una solicitud para cambiar
                                    la contraseña de tu cuenta de Nuvia.
                                    Para crear una nueva contraseña,
                                    hacé clic en el siguiente botón.
                                </p>


                                <!-- BOTÓN -->

                                <a
                                    href='" . htmlspecialchars($linkRecuperacion) . "'
                                    style='
                                        display: inline-block;
                                        background-color: #684028;
                                        color: #ffffff;
                                        text-decoration: none;
                                        padding: 13px 30px;
                                        border-radius: 25px;
                                        font-size: 15px;
                                        font-weight: bold;
                                    '
                                >
                                    Crear nueva contraseña
                                </a>


                                <p style='
                                    color: #888888;
                                    font-size: 13px;
                                    line-height: 1.5;
                                    margin-top: 28px;
                                    margin-bottom: 0;
                                '>
                                    Este enlace estará disponible durante 1 hora.
                                </p>

                            </div>


                            <!-- PIE -->

                            <div style='
                                background-color: #f7f3f1;
                                padding: 18px 30px;
                                text-align: center;
                            '>

                                <p style='
                                    margin: 0;
                                    color: #888888;
                                    font-size: 12px;
                                '>
                                    Si no solicitaste un cambio de contraseña,
                                    podés ignorar este correo.
                                </p>

                            </div>

                        </div>

                    </div>

                </body>

                </html>
                ";


                $mail->AltBody =
                    "Hola " . $usuario["nombreUsuario"] . ". "
                    . "Recibimos una solicitud para cambiar la contraseña de tu cuenta de Nuvia. "
                    . "Para crear una nueva contraseña ingresá al siguiente enlace: "
                    . $linkRecuperacion
                    . " El enlace vence dentro de 1 hora.";


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

    <link rel="stylesheet" href="../css/bootstrap-icons.css">

    <link
        rel="stylesheet"
        href="../css/estiloshome.css"
    >
    <link
    rel="stylesheet"
    href="../css/estilosRecuperacion.css"
>
    <link rel="stylesheet" href="../css/navbar.css">

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