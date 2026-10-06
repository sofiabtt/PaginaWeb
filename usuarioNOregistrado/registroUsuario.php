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


    /* =========================
       VALIDACIONES
    ========================= */

    $errorNombre = false;
    $errorTelefono = false;
    $errorContrasena = false;


    // NOMBRE Y APELLIDO

    if ($nombreApellido === "") {

        $errorNombre = true;

    }


    // TELÉFONO

    if ($telefono === "") {

        $errorTelefono = true;

    }


    // CONTRASEÑA

    if (
        strlen($contrasena) < 6
        || !preg_match('/[A-Z]/', $contrasena)
        || !preg_match('/[0-9]/', $contrasena)
    ) {

        $errorContrasena = true;

    }


    /* =========================
       SI HAY ERRORES
    ========================= */

    if (
        $errorNombre
        || $errorTelefono
        || $errorContrasena
    ) {

        $parametros = [];


        // CONSERVAR NOMBRE SI ESTÁ BIEN

        if (!$errorNombre) {

            $parametros[] =
                "nombre=" . urlencode($nombreApellido);

        }


        // CONSERVAR TELÉFONO SI ESTÁ BIEN

        if (!$errorTelefono) {

            $parametros[] =
                "telefono=" . urlencode($telefono);

        }


        // INDICAR QUÉ CAMPO TIENE ERROR

        if ($errorNombre) {

            $parametros[] = "errorNombre=1";

        }


        if ($errorTelefono) {

            $parametros[] = "errorTelefono=1";

        }


        if ($errorContrasena) {

            $parametros[] = "errorContrasena=1";

        }


        header(
            "Location: ../registroDatosPersonales.php?"
            . implode("&", $parametros)
        );

        exit();

    }


    /* =========================
       VALIDAR GMAIL
    ========================= */

    if (!filter_var($gmail, FILTER_VALIDATE_EMAIL)) {

        echo "El correo electrónico no es válido.";
        exit();

    }


    /* =========================
       CONEXIÓN
    ========================= */

    include "../php/conexionBD.php";


    /* =========================
       PREPARAR DATOS
    ========================= */

    $claveHash = password_hash(
        $contrasena,
        PASSWORD_DEFAULT
    );

    $tipoUsuario = "usuario";

    $verificado = 0;

    $tokenVerificacion =
        bin2hex(random_bytes(32));

    $fechaVerificacion = date(
        'Y-m-d H:i:s',
        strtotime('+24 hours')
    );

    $enlaceVerificacion =
        "http://localhost/PaginaWeb/php/verificarCuenta.php?token="
        . urlencode($tokenVerificacion);


    /* =========================
       INSERTAR USUARIO
    ========================= */

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


    /* =========================
       REGISTRAR USUARIO
    ========================= */

    if ($consulta->execute()) {

        $mail = new PHPMailer(true);


        try {

            $mail->isSMTP();

            $mail->Host = 'smtp.gmail.com';

            $mail->SMTPAuth = true;

            $mail->Username =
                $_ENV['GMAIL_USUARIO'];

            $mail->Password =
                $_ENV['GMAIL_PASSWORD'];

            $mail->SMTPSecure =
                PHPMailer::ENCRYPTION_STARTTLS;

            $mail->Port = 587;


            /* REMITENTE */

            $mail->setFrom(
                $_ENV['GMAIL_USUARIO'],
                'Nuvia Aerolineas'
            );

            $mail->addAddress($gmail);

            $mail->isHTML(true);

            $mail->Subject = 'Verifica tu cuenta de Nuvia';

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
                                    Verificá tu cuenta
                                </h2>


                                <p style='
                                    color: #333333;
                                    font-size: 16px;
                                    line-height: 1.6;
                                    margin-bottom: 12px;
                                '>
                                    Hola <strong>"
                                    . htmlspecialchars($nombreApellido)
                                    . "</strong>,
                                </p>


                                <p style='
                                    color: #555555;
                                    font-size: 15px;
                                    line-height: 1.6;
                                    margin-bottom: 28px;
                                '>
                                    Gracias por registrarte en Nuvia.
                                    Para completar tu registro y activar tu cuenta,
                                    verificá tu correo electrónico.
                                </p>


                                <!-- BOTÓN -->

                                <a
                                    href='" . htmlspecialchars($enlaceVerificacion) . "'
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
                                    Verificar mi cuenta
                                </a>


                                <p style='
                                    color: #888888;
                                    font-size: 13px;
                                    line-height: 1.5;
                                    margin-top: 28px;
                                    margin-bottom: 0;
                                '>
                                    Este enlace estará disponible durante 24 horas.
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
                                    Si no creaste una cuenta en Nuvia,
                                    podés ignorar este correo.
                                </p>

                            </div>

                        </div>

                    </div>

                </body>

                </html>
                ";


            $mail->send();


            unset($_SESSION["gmailRegistro"]);


            $destino =
                "verificacionPendiente.php";


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