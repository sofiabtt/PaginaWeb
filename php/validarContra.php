<?php

session_start();

if ($_SERVER["REQUEST_METHOD"] == "POST") {

    $contrasena = $_POST["contrasena"];
    $gmail = $_SESSION["gmailIngreso"];

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
        "SELECT claveUsuario, tipoUsuario
         FROM Usuarios
         WHERE emailUsuario = ?"
    );

    $consulta->bind_param("s", $gmail);

    $consulta->execute();

    $resultado = $consulta->get_result();

    if ($resultado->num_rows > 0) {

        $usuario = $resultado->fetch_assoc();

        if (password_verify($contrasena, $usuario["claveUsuario"])) {

            header("Location: ../html/administrador.html");
            exit();

        } else {

            echo "Contraseña incorrecta.";

        }

    } else {

        echo "Usuario no encontrado.";

    }

    $consulta->close();
    $conexion->close();
}
?>