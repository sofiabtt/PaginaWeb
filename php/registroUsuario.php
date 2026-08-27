<?php

session_start();

ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

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

    $gmail = $_SESSION["gmailRegistro"];
    $nombreApellido = $_POST["nombreApellido"];
    $telefono = $_POST["telefono"];
    $contrasena = $_POST["contrasena"];

    include "conexionBD.php";

    $claveHash = password_hash(
        $contrasena,
        PASSWORD_DEFAULT
    );

    $tipoUsuario = "usuario";

    $verificado = 0;

    $codigoVerificacion = random_int(100000, 999999);

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
        "ssssssis",
        $gmail,
        $nombreApellido,
        $telefono,
        $claveHash,
        $tipoUsuario,
        $verificado,
        $codigoVerificacion,
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
                'Aerolineas'
            );

            /* Destinatario */
            $mail->addAddress($gmail);

            /* Contenido */
            $mail->isHTML(true);

            $mail->Subject = 'Verifica tu cuenta';

            $mail->Body = "

                <h2>Verificación de cuenta</h2>

                <p>Tu código de verificación es:</p>

                <h1>$codigoVerificacion</h1>

                <p>Ingresalo en la página para verificar tu cuenta.</p>

            ";

            $mail->send();

            $destino = "../registro-CodVerif.php";


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