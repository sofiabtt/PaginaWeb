<?php
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

session_start();

if ($_SERVER["REQUEST_METHOD"] == "POST") {

    $contrasena = $_POST["contrasena"];
    $gmail = $_SESSION["gmailIngreso"];

    include "conexionBD.php";

    $consulta = $conexion->prepare(
        "SELECT codUsuario, nombreUsuario, claveUsuario, tipoUsuario,
                verificado
         FROM Usuarios
         WHERE emailUsuario = ?"
    );

    $consulta->bind_param("s", $gmail);

    $consulta->execute();

    $resultado = $consulta->get_result();

    $usuario = $resultado->fetch_assoc();

    if (
        $usuario
        && password_verify($contrasena, $usuario["claveUsuario"])
        && (int) $usuario["verificado"] !== 1
    ) {

        $destino = "../ingresoContra.php?error=no_verificado";

    } elseif ($usuario && password_verify($contrasena, $usuario["claveUsuario"])) {

        // Guardamos los datos del usuario en la sesión

        $_SESSION["codUsuario"] = $usuario["codUsuario"];
        $_SESSION["nombreUsuario"] = $usuario["nombreUsuario"];
        $_SESSION["emailUsuario"] = $usuario["emailUsuario"];
        $_SESSION["tipoUsuario"] = $usuario["tipoUsuario"];

        


        // Según el tipo de usuario, definimos a dónde entra

        $destino = "../home.php";

        if ($usuario["tipoUsuario"] == "usuario") {

            $destino = "../usuario/usuario.php";

        }


        if ($usuario["tipoUsuario"] == "administrador") {

            $destino = "../home.php";

        }


        if ($usuario["tipoUsuario"] == "ceo") {

            $destino = "../home.php";

        }


    } else {

        $destino = "../ingresoContra.php?error=contrasena";

    }


    $consulta->close();

    $conexion->close();


    header("Location: $destino");

    exit();

}

?>

