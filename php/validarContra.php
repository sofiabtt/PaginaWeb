<?php

session_start();

if ($_SERVER["REQUEST_METHOD"] == "POST") {

    $contrasena = $_POST["contrasena"];
    $gmail = $_SESSION["gmailIngreso"];

    include "conexionBD.php";

    $consulta = $conexion->prepare(
        "SELECT claveUsuario, tipoUsuario
         FROM Usuarios
         WHERE emailUsuario = ?"
    );

    $consulta->bind_param("s", $gmail);

    $consulta->execute();

    $resultado = $consulta->get_result();

    $usuario = $resultado->fetch_assoc();

    if (password_verify($contrasena, $usuario["claveUsuario"])) {

        $destino = "../home.php";

        if ($usuario["tipoUsuario"] == "administrador") {
            $destino = "../admin/admin.php";
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