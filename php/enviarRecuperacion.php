<?php

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

if ($_SERVER["REQUEST_METHOD"] !== "POST") {
    header("Location: ../recuperarContrasena.php");
    exit();
}

require "../vendor/autoload.php";
$dotenv = Dotenv\Dotenv::createImmutable(__DIR__ . '/..');
$dotenv->load();
include "conexionBD.php";

$email = trim($_POST["email"] ?? "");
if (filter_var($email, FILTER_VALIDATE_EMAIL)) {
    $consulta = $conexion->prepare("SELECT codUsuario, nombreUsuario FROM Usuarios WHERE emailUsuario = ? AND verificado = 1");
    $consulta->bind_param("s", $email); $consulta->execute(); $usuario = $consulta->get_result()->fetch_assoc();

    if ($usuario) {
        $token = bin2hex(random_bytes(32));
        $vence = date('Y-m-d H:i:s', strtotime('+1 hour'));
        $actualizar = $conexion->prepare("UPDATE Usuarios SET tokenVerificacion = ?, fechaVerificacion = ? WHERE codUsuario = ?");
        $actualizar->bind_param("ssi", $token, $vence, $usuario["codUsuario"]); $actualizar->execute(); $actualizar->close();

        $protocolo = isset($_SERVER['HTTPS']) && $_SERVER['HTTPS'] === 'on' ? 'https' : 'http';
        $host = $_SERVER['HTTP_HOST'] ?? 'localhost';
        $rutaProyecto = rtrim(dirname(dirname($_SERVER['SCRIPT_NAME'])), '/');
        $enlace = $protocolo . '://' . $host . $rutaProyecto . '/restablecerContrasena.php?token=' . urlencode($token);

        try {
            $mail = new PHPMailer(true); $mail->isSMTP(); $mail->Host = 'smtp.gmail.com'; $mail->SMTPAuth = true;
            $mail->Username = $_ENV['GMAIL_USUARIO']; $mail->Password = $_ENV['GMAIL_PASSWORD'];
            $mail->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS; $mail->Port = 587;
            $mail->setFrom($_ENV['GMAIL_USUARIO'], 'Nuvia'); $mail->addAddress($email); $mail->isHTML(true);
            $mail->Subject = 'Recupera tu contraseña';
            $mail->Body = '<h2>Recuperación de contraseña</h2><p>Hacé clic en este enlace:</p><p><a href="' . htmlspecialchars($enlace) . '">Cambiar mi contraseña</a></p><p>Vence en una hora.</p>';
            $mail->send();
        } catch (Exception $e) {
            // La respuesta no revela si el correo existe.
        }
    }
}

$conexion->close();
header("Location: ../recuperarContrasena.php?enviado=1");
exit();

