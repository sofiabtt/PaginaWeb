<?php

session_start();

include "../php/conexionBD.php";

$token = $_GET["token"] ?? $_POST["token"] ?? "";

$error = "";
$usuario = null;


// Validar token
if ($token !== "") {

    $consulta = $conexion->prepare("
        SELECT
            codUsuario,
            emailUsuario
        FROM Usuarios
        WHERE tokenRecuperacion = ?
        AND expiracionTokenRecuperacion > NOW()
    ");

    $consulta->bind_param("s", $token);
    $consulta->execute();

    $resultado = $consulta->get_result();
    $usuario = $resultado->fetch_assoc();
}


// Si el token no existe o venció
if (!$usuario) {
    $error = "El enlace de recuperación es inválido o ha expirado.";
}


// Si envió el formulario
if (
    $_SERVER["REQUEST_METHOD"] === "POST"
    && $usuario
) {

    $contrasena = $_POST["contrasena"] ?? "";
    $confirmar = $_POST["confirmarContrasena"] ?? "";

    if ($contrasena === "") {

        $error = "Ingresá una nueva contraseña.";

    } elseif ($contrasena !== $confirmar) {

        $error = "Las contraseñas no coinciden.";

    } elseif (strlen($contrasena) < 6) {

        $error = "La contraseña debe tener al menos 6 caracteres.";

    } else {

        $hash = password_hash(
            $contrasena,
            PASSWORD_DEFAULT
        );

        $actualizar = $conexion->prepare("
            UPDATE Usuarios
            SET
                claveUsuario = ?,
                tokenRecuperacion = NULL,
                expiracionTokenRecuperacion = NULL
            WHERE codUsuario = ?
        ");

        $actualizar->bind_param(
            "si",
            $hash,
            $usuario["codUsuario"]
        );

        $actualizar->execute();

        header(
            "Location: ../inicioSesion.php?contrasenaActualizada=1"
        );

        exit;
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

    <title>Nueva contraseña</title>

    <link rel="stylesheet" href="../css/bootstrap.min.css">
    <link rel="stylesheet" href="../css/estiloshome.css">
    <link rel="stylesheet" href="../css/estilosRecuperacion.css">

    <link
        rel="stylesheet"
        href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css"
    >

    <style>

        .contenedor-nueva-contrasena {
            min-height: 100vh;
            background: #dff6fb;
            display: flex;
            justify-content: center;
            align-items: center;
            padding: 70px 20px 50px;
        }

        .tarjeta-nueva-contrasena {
            width: 100%;
            max-width: 440px;
            background: white;
            border-radius: 18px;
            padding: 36px;
            box-shadow: 0 10px 30px rgba(0, 0, 0, 0.12);
        }

        .tarjeta-nueva-contrasena h1 {
            margin: 0 0 28px;
            font-size: 30px;
            font-weight: 600;
            color: #222;
        }

        .campo-nueva-contrasena {
            margin-bottom: 20px;
        }

        .campo-nueva-contrasena label {
            display: block;
            margin-bottom: 8px;
            font-weight: 600;
            color: #333;
        }

        .grupo-contrasena {
            display: flex;
            width: 100%;
        }

        .grupo-contrasena input {
            flex: 1;
            min-width: 0;
            height: 48px;
            padding: 0 14px;
            border: 1px solid #ced4da;
            border-right: none;
            border-radius: 9px 0 0 9px;
            font-size: 15px;
            outline: none;
        }

        .grupo-contrasena input:focus {
            border-color: #7a482b;
            box-shadow: 0 0 0 3px rgba(122, 72, 43, 0.10);
        }

        .boton-ojo-recuperacion {
            width: 58px;
            min-width: 58px;
            height: 48px;

            display: flex;
            justify-content: center;
            align-items: center;

            background: white;
            color: #222;

            border: 1px solid #ced4da;
            border-radius: 0 9px 9px 0;

            font-size: 20px;
            cursor: pointer;
        }

        .boton-ojo-recuperacion:hover {
            background: #f1f1f1;
        }

        .boton-cambiar-contrasena {
            width: 100%;
            margin-top: 4px;
            padding: 13px;

            border: none;
            border-radius: 9px;

            background: #7a482b;
            color: white;

            font-size: 16px;
            font-weight: 600;

            cursor: pointer;
        }

        .boton-cambiar-contrasena:hover {
            background: #653a23;
        }

        .mensaje-error-recuperacion {
            padding: 12px 14px;
            margin-bottom: 20px;
            border-radius: 9px;
            background: #fdeaea;
            color: #9b2c2c;
        }

    </style>

</head>


<body>

<?php include "../includes/navbar.php"; ?>


<main class="contenedor-nueva-contrasena">

    <section class="tarjeta-nueva-contrasena">

        <h1>
            Crear nueva contraseña
        </h1>


        <?php if ($error !== ""): ?>

            <div class="mensaje-error-recuperacion">
                <?= htmlspecialchars($error) ?>
            </div>

        <?php endif; ?>


        <?php if ($usuario): ?>

            <form method="POST">

                <input
                    type="hidden"
                    name="token"
                    value="<?= htmlspecialchars($token) ?>"
                >


                <div class="campo-nueva-contrasena">

                    <label for="contrasena">
                        Nueva contraseña
                    </label>

                    <div class="grupo-contrasena">

                        <input
                            type="password"
                            id="contrasena"
                            name="contrasena"
                            required
                            minlength="6"
                        >

                        <button
                            type="button"
                            class="boton-ojo-recuperacion"
                            id="boton-ojo-nueva"
                            onclick="mostrarNuevaContrasena()"
                            aria-label="Mostrar u ocultar nueva contraseña"
                        >
                            <i class="bi bi-eye"></i>
                        </button>

                    </div>

                </div>


                <div class="campo-nueva-contrasena">

                    <label for="confirmarContrasena">
                        Repetir contraseña
                    </label>

                    <div class="grupo-contrasena">

                        <input
                            type="password"
                            id="confirmarContrasena"
                            name="confirmarContrasena"
                            required
                            minlength="6"
                        >

                        <button
                            type="button"
                            class="boton-ojo-recuperacion"
                            id="boton-ojo-confirmar"
                            onclick="mostrarConfirmacionContrasena()"
                            aria-label="Mostrar u ocultar confirmación de contraseña"
                        >
                            <i class="bi bi-eye"></i>
                        </button>

                    </div>

                </div>


                <button
                    type="submit"
                    class="boton-cambiar-contrasena"
                >
                    Cambiar contraseña
                </button>

            </form>

        <?php endif; ?>

    </section>

</main>


<script>

function mostrarNuevaContrasena() {

    const input =
        document.getElementById("contrasena");

    const icono =
        document.querySelector("#boton-ojo-nueva i");

    if (input.type === "password") {

        input.type = "text";
        icono.className = "bi bi-eye-slash";

    } else {

        input.type = "password";
        icono.className = "bi bi-eye";
    }
}


function mostrarConfirmacionContrasena() {

    const input =
        document.getElementById("confirmarContrasena");

    const icono =
        document.querySelector("#boton-ojo-confirmar i");

    if (input.type === "password") {

        input.type = "text";
        icono.className = "bi bi-eye-slash";

    } else {

        input.type = "password";
        icono.className = "bi bi-eye";
    }
}

</script>

<script src="../js/bootstrap.bundle.min.js"></script>

</body>

</html>
