<?php

session_start();

ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

include "../../php/conexionBD.php";


// =========================
// COMPROBAR QUE HAYA TOKEN
// =========================

if (!isset($_GET["token"]) && !isset($_POST["token"])) {

    echo "Enlace inválido.";

    exit();

}


// Tomamos el token de la URL o del formulario

$token = $_GET["token"] ?? $_POST["token"];


// =========================
// BUSCAR EL CEO
// =========================

$consulta = $conexion->prepare("
    SELECT
        codUsuario,
        nombreUsuario,
        emailUsuario,
        tokenVerificacion,
        fechaVerificacion,
        debeCambiarClave
    FROM Usuarios
    WHERE tokenVerificacion = ?
      AND tipoUsuario = 'ceo'
");

$consulta->bind_param(
    "s",
    $token
);

$consulta->execute();

$resultado = $consulta->get_result();


// Si no encontró el token

if ($resultado->num_rows != 1) {

    echo "El enlace no es válido o ya fue utilizado.";

    exit();

}


$ceo = $resultado->fetch_assoc();


// =========================
// COMPROBAR VENCIMIENTO
// =========================

if (
    empty($ceo["fechaVerificacion"]) ||
    strtotime($ceo["fechaVerificacion"]) < time()
) {

    echo "El enlace ha vencido. Solicite al administrador un nuevo enlace.";

    exit();

}


// =========================
// GUARDAR NUEVA CONTRASEÑA
// =========================

if ($_SERVER["REQUEST_METHOD"] == "POST") {


    $nuevaClave = $_POST["nuevaClave"];

    $repetirClave = $_POST["repetirClave"];


    // Comprobar que no estén vacías

    if (
        empty($nuevaClave) ||
        empty($repetirClave)
    ) {

        $error = "Debe completar ambos campos.";

    }


    // Comprobar que coincidan

    elseif ($nuevaClave !== $repetirClave) {

        $error = "Las contraseñas no coinciden.";

    }


    else {


        // =========================
        // ENCRIPTAR CONTRASEÑA
        // =========================

        $claveHash = password_hash(
            $nuevaClave,
            PASSWORD_DEFAULT
        );


        // =========================
        // ACTUALIZAR CEO
        // =========================

        $actualizar = $conexion->prepare("
            UPDATE Usuarios

            SET
                claveUsuario = ?,
                debeCambiarClave = 0,
                tokenVerificacion = NULL,
                fechaVerificacion = NULL

            WHERE codUsuario = ?
        ");


        $actualizar->bind_param(
            "si",
            $claveHash,
            $ceo["codUsuario"]
        );


        if ($actualizar->execute()) {

            $exito = true;

        } else {

            $error = "No se pudo guardar la contraseña.";

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

    <title>Crear contraseña</title>


    <!-- Bootstrap -->

    <link
        rel="stylesheet"
        href="../../css/bootstrap.min.css"
    >


    <!-- CSS principal -->

    <link
        rel="stylesheet"
        href="../../css/estilos.css"
    >


    <!-- Bootstrap Icons -->

    <link
        rel="stylesheet"
        href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css"
    >


    <!-- Favicon -->

    <link
        rel="icon"
        type="image/png"
        href="../../imagenes/logo.png"
    >

</head>


<body>


    <!-- =========================
         NAVBAR
    ========================== -->

    <header class="barra-superior navbar navbar-expand-lg">

        <a class="navbar-brand">

            <img
                src="../../imagenes/logo.png"
                alt="logo"
                width="60"
                height="60"
            >

        </a>


        <div class="ms-auto d-none d-lg-flex align-items-center">

            <a class="nav-link">
                Novedades
            </a>

            <a class="nav-link">
                Destinos
            </a>

            <a class="nav-link">
                Ofertas
            </a>

        </div>

    </header>



    <!-- =========================
         CONTENIDO
    ========================== -->

    <main class="d-flex justify-content-center align-items-center">


        


        <?php if (isset($exito)) { ?>

            <section class="rectangulo-formulario formulario-exito">


                <!-- =========================
                     CONTRASEÑA CREADA
                ========================== -->

                <div class="text-center">

                    <h1 class="mb-4 texto-negro">

                        ¡Contraseña creada!

                    </h1>


                    <p class="texto-negro mb-4">

                        Tu contraseña fue creada
                        correctamente.

                    </p>


                    <p class="texto-negro">

                        Ya podés utilizar tu cuenta
                        de CEO.

                    </p>


                    <div class="text-center mt-4">

                        <a
                            href="../iniciosesion.html"
                            class="btn btn-primary"
                        >

                            Iniciar sesión

                        </a>

                    </div>

                </div>

            </section>


        <?php } else { ?>

            <section class="rectangulo-formulario">


                <!-- =========================
                     FORMULARIO
                ========================== -->

                <h1 class="text-center mb-4 texto-negro">

                    Crear contraseña

                </h1>


                <p class="text-center texto-negro mb-4">

                    Hola

                    <strong>

                        <?php
                        echo htmlspecialchars(
                            $ceo["nombreUsuario"]
                        );
                        ?>

                    </strong>.

                </p>


                <p class="text-center texto-registro texto-negro mb-4">

                    Creá una contraseña para
                    acceder a tu cuenta.

                </p>


                <?php if (isset($error)) { ?>

                    <div class="alerta-en-rojo text-center mb-3">

                        <?php echo $error; ?>

                    </div>

                <?php } ?>


                <form method="POST">


                    <!-- TOKEN -->

                    <input
                        type="hidden"
                        name="token"
                        value="<?php echo htmlspecialchars($token); ?>"
                    >


                    <!-- =========================
                         NUEVA CONTRASEÑA
                    ========================== -->

                    <div class="mb-4">

                        <label
                            for="nuevaClave"
                            class="form-label texto-negro"
                        >

                            Nueva contraseña:

                        </label>


                        <div class="barra-contra">

                            <input
                                type="password"
                                class="form-control"
                                id="nuevaClave"
                                name="nuevaClave"
                                placeholder="Ingrese una contraseña"
                                required
                            >


                            <button
                                type="button"
                                class="boton-ojo"
                                onclick="mostrarContrasena('nuevaClave', 'boton-ojo-nueva')"
                                id="boton-ojo-nueva"
                            >

                                <i class="bi bi-eye"></i>

                            </button>

                        </div>

                    </div>



                    <!-- =========================
                         REPETIR CONTRASEÑA
                    ========================== -->

                    <div class="mb-4">

                        <label
                            for="repetirClave"
                            class="form-label texto-negro"
                        >

                            Repetir contraseña:

                        </label>


                        <div class="barra-contra">

                            <input
                                type="password"
                                class="form-control"
                                id="repetirClave"
                                name="repetirClave"
                                placeholder="Repita su contraseña"
                                required
                            >


                            <button
                                type="button"
                                class="boton-ojo"
                                onclick="mostrarContrasena('repetirClave', 'boton-ojo-repetir')"
                                id="boton-ojo-repetir"
                            >

                                <i class="bi bi-eye"></i>

                            </button>

                        </div>

                    </div>


                    <div class="text-center">

                        <button
                            type="submit"
                            class="btn btn-primary"
                        >

                            Crear contraseña

                        </button>

                    </div>


                </form>

            </section>

        <?php } ?>


    </main>



    <!-- Bootstrap JS -->

    <script
        src="../../js/bootstrap.bundle.min.js"
    ></script>


    <!-- =========================
         MOSTRAR / OCULTAR CONTRASEÑA
    ========================== -->

    <script>

        function mostrarContrasena(idInput, idBoton) {

            const input =
                document.getElementById(idInput);

            const icono =
                document.querySelector("#" + idBoton + " i");


            if (input.type === "password") {

                input.type = "text";

                icono.className =
                    "bi bi-eye-slash";

            } else {

                input.type = "password";

                icono.className =
                    "bi bi-eye";

            }

        }

    </script>


</body>

</html>