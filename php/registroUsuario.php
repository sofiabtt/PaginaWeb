<?php

session_start();

ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);


/*
------------------------------
Agrego PHPMailer
------------------------------
*/

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

require '../vendor/autoload.php';


$dotenv = Dotenv\Dotenv::createImmutable(__DIR__ . '/..');

$dotenv->load();

// crea un objeto de la librería Dotenv,
// lee el archivo .env y carga sus variables.

/*------------------------------ */


if ($_SERVER["REQUEST_METHOD"] == "POST") {


    /*
    Verifico que existan los datos
    guardados anteriormente en la sesión
    */

    if (!isset($_SESSION["gmailRegistro"])) {

        echo "No se encontro el gmail ingresado";

        exit();

    }


    /*
    Recupero los datos
    */

    $gmail = $_SESSION["gmailRegistro"];

    $nombreApellido = $_POST["nombreApellido"];

    $telefono = $_POST["telefono"];

    $contrasena = $_POST["contrasena"];


    /*
    Conexión con la base de datos
    */

    $conexion = new mysqli(
        "localhost",
        "root",
        "",
        "Aerolineas"
    );


    if ($conexion->connect_error) {

        die(
            "Error de conexión: "
            . $conexion->connect_error
        );

    }


    /*
    Encripto la contraseña
    */

    $claveHash = password_hash(
        $contrasena,
        PASSWORD_DEFAULT
    );


    /*
    Tipo de usuario
    */

    $tipoUsuario = "usuario";


    /*
    Al principio el usuario todavía
    no verificó su correo
    */

    $verificado = 0;


    /*
    Genero un código aleatorio
    para verificar el correo
    */

    $codigoVerificacion = random_int(100000, 999999);

    /*
    Preparo el INSERT
    */

    $consulta = $conexion->prepare(

        "INSERT INTO Usuarios
        (
            emailUsuario,
            nombreUsuario,
            telefonoUsuario,
            claveUsuario,
            tipoUsuario,
            verificado,
            tokenVerificacion
        )
        VALUES (?, ?, ?, ?, ?, ?, ?)"

    );


    /*
    Indico el tipo de dato
    de cada variable

    s = string
    i = integer
    */

    $consulta->bind_param(

    "sssssis",

    $gmail,
    $nombreApellido,
    $telefono,
    $claveHash,
    $tipoUsuario,
    $verificado,
    $codigoVerificacion

);


    /*
    Intento insertar el usuario
    */

    if ($consulta->execute()) {


        /*
        Si el usuario se guardó correctamente,
        recién ahora envío el correo
        */

        $mail = new PHPMailer(true);


        try {


            /*
            Configuración SMTP de Gmail
            */

            $mail->isSMTP();

            $mail->Host = 'smtp.gmail.com';

            $mail->SMTPAuth = true;


            /*
            Estos datos vienen del archivo .env
            */

            $mail->Username =
                $_ENV['GMAIL_USUARIO'];

            $mail->Password =
                $_ENV['GMAIL_PASSWORD'];


            /*
            Seguridad y puerto
            */

            $mail->SMTPSecure =
                PHPMailer::ENCRYPTION_STARTTLS;

            $mail->Port = 587;


            /*
            Quién envía el correo
            */

            $mail->setFrom(
                $_ENV['GMAIL_USUARIO'],
                'Aerolineas'
            );


            /*
            A quién se envía
            */

            $mail->addAddress($gmail);


            /*
            El contenido del mail será HTML
            */

            $mail->isHTML(true);


            /*
            Asunto
            */

            $mail->Subject =
                'Verifica tu cuenta';



            /*
            Contenido del correo
            */

            $mail->Body = "

                <h2>Verificación de cuenta</h2>

                <p>Tu código de verificación es:</p>

                <h1>$codigoVerificacion</h1>

                <p>Ingresalo en la página para verificar tu cuenta.</p>

            ";


            /*
            Envío el correo
            */

            $mail->send();
            header("Location: ../html/registro-CodVerif.html");
            exit();


        } catch (Exception $e) {


            echo
                "El usuario se registró correctamente, "
                . "pero no se pudo enviar el correo: "
                . $mail->ErrorInfo;


        }


    } else {


        echo "Error al registrar el usuario.";


    }


    /*
    Cierro consulta y conexión
    */

    $consulta->close();

    $conexion->close();


}

?>