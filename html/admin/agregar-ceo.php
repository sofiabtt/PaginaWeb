<?php

session_start();

ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);


// =========================
// PHPMailer
// =========================

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

require '../../vendor/autoload.php';


// =========================
// .ENV
// =========================

$dotenv = Dotenv\Dotenv::createImmutable(__DIR__ . '/../..');

$dotenv->load();


// =========================
// CONEXIÓN
// =========================

include "../../php/conexionBD.php";


// =========================
// CREAR CEO
// =========================

if ($_SERVER["REQUEST_METHOD"] == "POST") {

    $nombre = trim($_POST["nombre"]);
    $email = trim($_POST["email"]);
    $telefono = trim($_POST["telefono"]);
    $codAerolinea = $_POST["codAerolinea"];


    // Generar token
    $token = bin2hex(random_bytes(32));


    // El enlace vence en 24 horas
    $fechaExpiracion = date(
        "Y-m-d H:i:s",
        strtotime("+24 hours")
    );


    // Datos del CEO
    $tipoUsuario = "ceo";

    $verificado = 1;

    $debeCambiarClave = 1;

    // Todavía no tiene contraseña
    $clave = NULL;


    // =========================
    // INSERTAR CEO
    // =========================

    $consulta = $conexion->prepare("
        INSERT INTO Usuarios (
            nombreUsuario,
            claveUsuario,
            tipoUsuario,
            emailUsuario,
            telefonoUsuario,
            verificado,
            tokenVerificacion,
            fechaVerificacion,
            codAerolinea,
            debeCambiarClave
        )
        VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?)
    ");


    $consulta->bind_param(
        "sssssissis",
        $nombre,
        $clave,
        $tipoUsuario,
        $email,
        $telefono,
        $verificado,
        $token,
        $fechaExpiracion,
        $codAerolinea,
        $debeCambiarClave
    );


    // =========================
    // GUARDAR CEO
    // =========================

    if ($consulta->execute()) {


        // =========================
        // ENVIAR EMAIL
        // =========================

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

            $mail->setFrom(
                $_ENV['GMAIL_USUARIO'],
                'Aerolineas'
            );


            // Destinatario

            $mail->addAddress($email);


            // HTML

            $mail->isHTML(true);


            // Asunto

            $mail->Subject =
                'Activa tu cuenta de CEO';


            // Enlace para crear contraseña

            $enlace =
                "http://localhost/PaginaWeb/html/admin/crear-clave.php?token="
                . urlencode($token);


            // Contenido del correo

            $mail->Body = "

                <h2>Bienvenido a Aerolineas</h2>

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


            // Volver a gestión de CEOs

            header("Location: gestion-ceos.php");

            exit();


        } catch (Exception $e) {

            echo
                "El CEO fue creado correctamente, "
                . "pero no se pudo enviar el correo: "
                . $mail->ErrorInfo;

        }


    } else {

        echo
            "Error al crear el CEO: "
            . $consulta->error;

    }

}


// =========================
// OBTENER AEROLÍNEAS
// =========================

$consultaAerolineas = $conexion->query("
    SELECT codAerolinea, nombreAerolinea
    FROM Aerolineas
    ORDER BY nombreAerolinea
");

?>

<!DOCTYPE html>
<html lang="es">

<head>

    <meta charset="UTF-8">

    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <title>AeroFly Admin - Agregar CEO</title>

    <!-- Bootstrap -->
    <link rel="stylesheet" href="../../css/bootstrap.min.css">

    <!-- Bootstrap Icons -->
    <link rel="stylesheet" href="../../css/bootstrap-icons.css">

    <!-- CSS del administrador -->
    <link rel="stylesheet" href="../../css/estilos-admin.css">

    <!-- Favicon -->
    <link rel="icon" type="image/png" href="../../imagenes/logo.png">

</head>


<body>


    <!-- =========================
         NAVBAR
    ========================== -->

    <?php include "includes/navbar-admin.php"; ?>



    <!-- =========================
         CONTENIDO
    ========================== -->

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



        <!-- =========================
             FORMULARIO
        ========================== -->

        <section class="tabla-contenedor">


            <form method="POST">


                <!-- NOMBRE Y APELLIDO -->

                <div class="mb-3">

                    <label for="nombre" class="form-label">
                        Nombre y apellido
                    </label>

                    <input
                        type="text"
                        class="form-control"
                        id="nombre"
                        name="nombre"
                        required
                    >

                </div>



                <!-- EMAIL -->

                <div class="mb-3">

                    <label for="email" class="form-label">
                        Email
                    </label>

                    <input
                        type="email"
                        class="form-control"
                        id="email"
                        name="email"
                        required
                    >

                </div>



                <!-- TELÉFONO -->

                <div class="mb-3">

                    <label for="telefono" class="form-label">
                        Teléfono
                    </label>

                    <input
                        type="text"
                        class="form-control"
                        id="telefono"
                        name="telefono"
                        required
                    >

                </div>



                <!-- AEROLÍNEA -->

                <div class="mb-3">

                    <label for="codAerolinea" class="form-label">
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


                        <?php while ($aerolinea = $consultaAerolineas->fetch_assoc()) { ?>

                            <option value="<?php echo $aerolinea["codAerolinea"]; ?>">

                                <?php echo htmlspecialchars($aerolinea["nombreAerolinea"]); ?>

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
                        class="btn btn-primary"
                    >

                        <i class="bi bi-check-lg"></i>

                        Crear CEO

                    </button>


                    <a
                        href="ceos.php"
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