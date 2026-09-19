<?php

ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

session_start();

include "consultasUsuarios.php";


if ($_SERVER["REQUEST_METHOD"] == "POST") {

    $contrasena = $_POST["contrasena"];

    $gmail = $_SESSION["gmailIngreso"];


    $usuario = obtenerUsuarioPorEmail(
        $conexion,
        $gmail
    );


    if (
        $usuario &&
        password_verify(
            $contrasena,
            $usuario["claveUsuario"]
        ) &&
        (int) $usuario["verificado"] !== 1
    ) {

        $destino =
            "../ingresoContra.php?error=no_verificado";


    } elseif (
        $usuario &&
        password_verify(
            $contrasena,
            $usuario["claveUsuario"]
        )
    ) {

        // Guardamos los datos del usuario en la sesión

        $_SESSION["codUsuario"] =
            $usuario["codUsuario"];

        $_SESSION["nombreUsuario"] =
            $usuario["nombreUsuario"];

        $_SESSION["tipoUsuario"] =
            $usuario["tipoUsuario"];

        $_SESSION["usuario"] =
            $gmail;


        // Según el tipo de usuario,
        // definimos a dónde entra

        $destino = "../home.php";


        if ($usuario["tipoUsuario"] == "usuario") {

            if (
                isset($_SESSION["reservaTemporal"]) &&
                isset(
                    $_SESSION["reservaTemporal"]["codVuelo"]
                )
            ) {

                $_SESSION["restaurarReservaTemporal"] =
                    true;

                $codVuelo =
                    $_SESSION["reservaTemporal"]["codVuelo"];

                $destino =
                    "../vueloElegido.php?codVuelo="
                    . $codVuelo;

            } else {

                $destino =
                    "../usuario/usuario.php";
            }
        }


        if (
            $usuario["tipoUsuario"] ==
            "administrador"
        ) {

            $destino =
                "../admin/admin.php";
        }


        if ($usuario["tipoUsuario"] == "ceo") {

            $destino =
                "../ceo/ceo.php";
        }


    } else {

        $destino =
            "../ingresoContra.php?error=contrasena";
    }


    $conexion->close();


    header("Location: $destino");

    exit();
}

?>