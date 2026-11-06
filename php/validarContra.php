<?php

session_start();

require_once __DIR__ . "/conexionBD.php";
require_once __DIR__ . "/consultasUsuarios.php";

// Si el usuario ya inició sesión, enviarlo al home
if (isset($_SESSION["codUsuario"])) {
    header("Location: " . urlApp("index.php"));
    exit();
}

// Solo aceptar solicitudes POST
if ($_SERVER["REQUEST_METHOD"] !== "POST") {
    header("Location: " . urlApp("inicioSesion.php"));
    exit();
}

// Obtener los datos ingresados
$contrasena = $_POST["contrasena"] ?? "";
$gmail = $_SESSION["gmailIngreso"] ?? "";

// Si no hay un correo guardado, volver al inicio de sesión
if ($gmail === "") {
    header("Location: " . urlApp("inicioSesion.php"));
    exit();
}

// Buscar el usuario en la base de datos
$usuario = obtenerUsuarioPorEmail($conexion, $gmail);

// Verificar la contraseña una sola vez
$contrasenaCorrecta = $usuario &&
    password_verify($contrasena, $usuario["claveUsuario"]);

if (!$contrasenaCorrecta) {

    // Contraseña incorrecta
    $destino = urlApp("ingresoContra.php?error=contrasena");

} elseif ((int) $usuario["verificado"] !== 1) {

    // Cuenta sin verificar
    $destino = urlApp("ingresoContra.php?error=no_verificado");

} else {

    // Inicio de sesión exitoso
    session_regenerate_id(true);

    $_SESSION["codUsuario"] = $usuario["codUsuario"];
    $_SESSION["nombreUsuario"] = $usuario["nombreUsuario"];
    $_SESSION["emailUsuario"] = $usuario["emailUsuario"];
    $_SESSION["tipoUsuario"] = $usuario["tipoUsuario"];

    // Por defecto, todos ingresan al home
    $destino = urlApp("index.php");

    // Si había una reserva pendiente, retomarla
    if ($usuario["tipoUsuario"] === "usuario") {

        if (
            isset($_SESSION["reservaTemporal"]) &&
            isset($_SESSION["reservaTemporal"]["codVuelo"])
        ) {

            $_SESSION["restaurarReservaTemporal"] = true;

            $codVuelo = $_SESSION["reservaTemporal"]["codVuelo"];

            $destino = urlApp(
                "vueloElegido.php?codVuelo=" . urlencode((string) $codVuelo)
            );
        }
    }
}

// Cerrar conexión
$conexion->close();

// Redirigir
header("Location: " . $destino);
exit();

?>