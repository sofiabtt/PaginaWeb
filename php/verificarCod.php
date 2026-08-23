<?php

session_start();

ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

if ($_SERVER["REQUEST_METHOD"] == "POST") {

    $codigoIngresado = $_POST["codigoVerificacion"];

    if (!isset($_SESSION["gmailRegistro"])) {
        echo "No se encontró el correo del registro.";
        exit();
    }

    $gmail = $_SESSION["gmailRegistro"];

    $conexion = new mysqli(
        "localhost",
        "root",
        "",
        "Aerolineas"
    );

    if ($conexion->connect_error) {
        die("Error de conexión: " . $conexion->connect_error);
    }

    $consulta = $conexion->prepare(
        "SELECT tokenVerificacion
         FROM Usuarios
         WHERE emailUsuario = ?"
    );

    $consulta->bind_param(
        "s",
        $gmail
    );

    $consulta->execute();

    $resultado = $consulta->get_result();

    if ($resultado->num_rows == 1) {

        $usuario = $resultado->fetch_assoc();

        if ($codigoIngresado == $usuario["tokenVerificacion"]) {

            $actualizar = $conexion->prepare(
                "UPDATE Usuarios
                 SET verificado = 1
                 WHERE emailUsuario = ?"
            );

            $actualizar->bind_param(
                "s",
                $gmail
            );

            $actualizar->execute();

            echo "Cuenta verificada correctamente.";

        } else {

           echo "Código ingresado: " . $codigoIngresado . "<br>";
           echo "Código guardado: " . $usuario["tokenVerificacion"] . "<br>";

        }

    }

}
?>