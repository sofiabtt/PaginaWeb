<?php

session_start();

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

require '../../vendor/autoload.php';

// .ENV

$dotenv = Dotenv\Dotenv::createImmutable(__DIR__ . '/../..');

$dotenv->load();

// CONEXIÓN Y CONSULTAS

include "../../php/conexionBD.php";
include "../../php/consultasAerolineas.php";
include "../../php/consultasCeos.php";
include "../../php/consultasActividad.php";


// Variable para mostrar errores

$error = "";

// CREAR CEO

if ($_SERVER["REQUEST_METHOD"] == "POST") {

    $nombre = trim($_POST["nombre"]);
    $email = trim($_POST["email"]);
    $telefono = trim($_POST["telefono"]);
    $codAerolinea = $_POST["codAerolinea"];

    // VERIFICAR EMAIL

    if (emailExiste($conexion, $email)) {

        $error = "El email ingresado ya pertenece a otro usuario.";

    }

    // CONTINUAR SI NO HAY ERROR

    if ($error == "") {

        // GENERAR TOKEN

        $token = bin2hex(random_bytes(32));

        // El enlace vence en 24 horas

        $fechaVerificacion = date(
            "Y-m-d H:i:s",
            strtotime("+24 hours")
        );

        // DATOS DEL CEO

        $tipoUsuario = "ceo";
        $verificado = 0;
        $clave = NULL;

        // CREAR CEO

        $codUsuarioNuevo = crearCeo(
            $conexion,
            $nombre,
            $clave,
            $tipoUsuario,
            $email,
            $telefono,
            $verificado,
            $token,
            $fechaVerificacion
        );


        if ($codUsuarioNuevo !== false) {

            asignarCeoAerolinea($conexion, $codUsuarioNuevo, $codAerolinea);

            registrarActividad($conexion, "Administrador", "Creó un nuevo CEO: " . $nombre);

            // ENVIAR EMAIL

            $mail = new PHPMailer(true);

            try {

                // SMTP de Gmail

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

                // Remitente

                $mail->setFrom($_ENV['GMAIL_USUARIO'], 'Nuvia');

                // Destinatario

                $mail->addAddress($email);

                // HTML

                $mail->isHTML(true);

                // Asunto

                $mail->Subject =
                    'Activa tu cuenta de CEO';


                // Enlace para crear contraseña

                $enlace =
                    "http://localhost/PaginaWeb/admin/ceos/crearClave.php?token="
                    . urlencode($token);


                // Contenido del correo

                $mail->Body = "

                    <h2>Bienvenido a Nuvia</h2>

                    <p>
                        Hola <strong>$nombre</strong>,
                    </p>

                    <p>
                        El administrador ha creado una cuenta de CEO
                        para vos.
                    </p>

                    <p>
                        Para comenzar a utilizar tu cuenta,
                        necesitás crear una contraseña.
                    </p>

                    <p>
                        Hacé clic en el siguiente botón:
                    </p>

                    <p>

                        <a href='$enlace'
                           style='
                           background-color:#684028;
                           color:white;
                           padding:12px 20px;
                           text-decoration:none;
                           border-radius:5px;
                           display:inline-block;
                           '>

                            Crear mi contraseña

                        </a>

                    </p>

                    <p>
                        Este enlace será válido durante 24 horas.
                    </p>

                ";

                // Enviar email

                $mail->send();

                header("Location: gestionCeos.php");

                exit();


            } catch (Exception $e) {

                $error =
                    "El CEO fue creado correctamente, "
                    . "pero no se pudo enviar el correo: "
                    . $mail->ErrorInfo;
            }

        } else {

            $error =
                "No se pudo crear el CEO. Intente nuevamente.";

        }

    }

}

// OBTENER AEROLÍNEAS ACTIVAS

$consultaAerolineas = obtenerAerolineasActivas($conexion);

?>

<!DOCTYPE html>

<html lang="es">

<head>

    <meta charset="UTF-8">

    <meta
        name="viewport"
        content="width=device-width, initial-scale=1.0"
    >

    <title>Nuvia - Administrador</title>


    <!-- Bootstrap -->

    <link
        rel="stylesheet"
        href="../../css/bootstrap.min.css"
    >


    <!-- Bootstrap Icons -->

    <link
        rel="stylesheet"
        href="../../css/bootstrap-icons.css"
    >


    <!-- CSS del administrador -->

    <link
        rel="stylesheet"
        href="../../css/estilos-admin.css"
    >


    <!-- Favicon -->

    <link
        rel="icon"
        type="image/png"
        href="../../imagenes/logo.png"
    >

</head>


<body>

    <?php include "../includes/navbarAdmin.php"; ?>


    <main class="contenido-admin">


        <section class="encabezado-contenido">

            <div>

                <h1>
                    Agregar CEO
                </h1>

                <p>
                    Crea una cuenta para un nuevo CEO.
                </p>

            </div>

        </section>


        <section class="tabla-contenedor">


            <?php if ($error != "") { ?>

                <div class="alert alert-danger">

                    <?php echo htmlspecialchars($error); ?>

                </div>

            <?php } ?>


            <form method="POST">


                <!-- NOMBRE Y APELLIDO -->

                <div class="mb-3">

                    <label
                        for="nombre"
                        class="form-label"
                    >
                        Nombre y apellido
                    </label>

                    <input
                        type="text"
                        class="form-control"
                        id="nombre"
                        name="nombre"
                        value="<?php echo htmlspecialchars($_POST["nombre"] ?? ""); ?>"
                        required
                    >

                </div>


                <!-- EMAIL -->

                <div class="mb-3">

                    <label
                        for="email"
                        class="form-label"
                    >
                        Email
                    </label>

                    <input
                        type="email"
                        class="form-control"
                        id="email"
                        name="email"
                        value="<?php echo htmlspecialchars($_POST["email"] ?? ""); ?>"
                        required
                    >

                </div>


                <!-- TELÉFONO -->

                <div class="mb-3">

                    <label
                        for="telefono"
                        class="form-label"
                    >
                        Teléfono
                    </label>

                    <input
                        type="text"
                        class="form-control"
                        id="telefono"
                        name="telefono"
                        value="<?php echo htmlspecialchars($_POST["telefono"] ?? ""); ?>"
                        required
                    >

                </div>


                <!-- AEROLÍNEA -->

                <div class="mb-3">

                    <label
                        for="codAerolinea"
                        class="form-label"
                    >
                        Aerolínea
                    </label>

                    <select
                        class="form-select"
                        id="codAerolinea"
                        name="codAerolinea"
                        required
                    >

                        <option value="">
                            Seleccionar aerolínea
                        </option>


                        <?php while (
                            $aerolinea =
                            $consultaAerolineas->fetch_assoc()
                        ) { ?>

                            <option
                                value="<?php echo $aerolinea["codAerolinea"]; ?>"
                                <?php
                                if (
                                    isset($_POST["codAerolinea"])
                                    &&
                                    $_POST["codAerolinea"]
                                    == $aerolinea["codAerolinea"]
                                ) {
                                    echo "selected";
                                }
                                ?>
                            >

                                <?php
                                echo htmlspecialchars(
                                    $aerolinea["nombreAerolinea"]
                                );
                                ?>

                            </option>

                        <?php } ?>

                    </select>

                </div>


                <!-- INFORMACIÓN -->

                <div class="alert alert-info">

                    Al crear la cuenta, el CEO recibirá un correo electrónico
                    con un enlace para establecer su contraseña.

                </div>


                <!-- BOTONES -->

                <div class="d-flex gap-2">

                    <button
                        type="submit"
                        class="btn btn-primary fw-bold"
                    >

                        <i class="bi bi-check-lg"></i>

                        Crear CEO

                    </button>


                    <a
                        href="gestionCeos.php"
                        class="btn btn-secondary"
                    >

                        Cancelar

                    </a>

                </div>


            </form>


        </section>


    </main>


    <script src="../../js/bootstrap.bundle.min.js"></script>

</body>

</html>