<?php

session_start();

if ($_SERVER["REQUEST_METHOD"] == "POST") {

    $contrasena = $_POST["contrasena"];
    $gmail = $_SESSION["gmailIngreso"];

    include "conexionBD.php";

    $consulta = $conexion->prepare(
        "SELECT codUsuario, nombreUsuario, claveUsuario, tipoUsuario,
                codAerolinea, verificado
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

        $_SESSION["tipoUsuario"] = $usuario["tipoUsuario"];

        $_SESSION["codAerolinea"] = $usuario["codAerolinea"];


        // Según el tipo de usuario, definimos a dónde entra

        $_SESSION["usuario"] = $gmail;
        $_SESSION["tipoUsuario"] = $usuario["tipoUsuario"];

        $destino = "../home.php";

        if ($usuario["tipoUsuario"] == "usuario") {

            $destino = "../usuario/vuelos/buscarVuelos.php";

        }


        if ($usuario["tipoUsuario"] == "administrador") {

            $destino = "../admin/admin.php";

        }


        if ($usuario["tipoUsuario"] == "ceo") {

            $destino = "../ceo/ceo.php";

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

