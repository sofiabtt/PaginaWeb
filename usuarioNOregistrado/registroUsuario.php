<?php

session_start();

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

require '../vendor/autoload.php';

$dotenv = Dotenv\Dotenv::createImmutable(__DIR__ . '/..');
$dotenv->load();


if ($_SERVER["REQUEST_METHOD"] == "POST") {

    if (!isset($_SESSION["gmailRegistro"])) {

        echo "No se encontró el gmail ingresado.";
        exit();

    }

    $gmail = trim($_SESSION["gmailRegistro"]);
    $nombreApellido = trim($_POST["nombreApellido"] ?? "");
    $telefono = trim($_POST["telefono"] ?? "");
    $contrasena = $_POST["contrasena"] ?? "";

    if (
        !filter_var($gmail, FILTER_VALIDATE_EMAIL)
        || $nombreApellido === ""
        || $telefono === ""
        || strlen($contrasena) < 8
    ) {
        header("Location: ../registroDatosPersonales.php?error=datos_invalidos");
        exit();
    }

    include "../php/conexionBD.php";

    $claveHash = password_hash(
        $contrasena,
        PASSWORD_DEFAULT
    );

    $tipoUsuario = "usuario";

    $verificado = 0;

    $tokenVerificacion = bin2hex(random_bytes(32));

    $fechaVerificacion = date(
        'Y-m-d H:i:s',
        strtotime('+24 hours')
    );

    $consulta = $conexion->prepare(

        "INSERT INTO Usuarios
        (
            emailUsuario,
            nombreUsuario,
            telefonoUsuario,
            claveUsuario,
            tipoUsuario,
            verificado,
            tokenVerificacion,
            fechaVerificacion
        )
        VALUES (?, ?, ?, ?, ?, ?, ?, ?)"

    );

    $consulta->bind_param(
        "sssssiss",
        $gmail,
        $nombreApellido,
        $telefono,
        $claveHash,
        $tipoUsuario,
        $verificado,
        $tokenVerificacion,
        $fechaVerificacion
    );


    if ($consulta->execute()) {

        $mail = new PHPMailer(true);

        try {

            $mail->isSMTP();

            $mail->Host = 'smtp.gmail.com';

            $mail->SMTPAuth = true;

            $mail->Username = $_ENV['GMAIL_USUARIO'];

            $mail->Password = $_ENV['GMAIL_PASSWORD'];

            $mail->SMTPSecure =
                PHPMailer::ENCRYPTION_STARTTLS;

            $mail->Port = 587;

            /* Remitente */
            $mail->setFrom(
                $_ENV['GMAIL_USUARIO'],
                'Nuvia Aerolineas'
            );

            $mail->addAddress($gmail);

            $mail->isHTML(true);

            $mail->Subject = 'Verificá tu cuenta de Nuvia';

            $mail->Body = "
                <h2>Verificación de cuenta</h2>
                <p>Hola " . htmlspecialchars($nombreApellido) . ",</p>
                <p>Gracias por registrarte en Nuvia.</p>
                <p>Hacé clic en el siguiente enlace para validar tu cuenta:</p>
                <p><a href=\"$enlaceVerificacion\">Verificar mi cuenta</a></p>
                <p>El enlace vence dentro de 24 horas.</p>
            ";

            $mail->send();

            unset($_SESSION["gmailRegistro"]);
            $destino = "../inicioSesion.php?registro=pendiente";


        } catch (Exception $e) {

            echo
                "El usuario se registró correctamente, "
                . "pero no se pudo enviar el correo: "
                . $mail->ErrorInfo;

        }


    } else {

        echo "Error al registrar el usuario.";

    }

    $consulta->close();
    $conexion->close();

    if (isset($destino)) {

        header("Location: $destino");
        exit();

    }

}

?>
