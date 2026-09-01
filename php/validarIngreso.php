<?php

session_start();

if ($_SERVER["REQUEST_METHOD"] == "POST") {

    $gmail = $_POST["gmail"];

    if (empty($gmail)) {

        echo "Debe ingresar un mail.";

    } else {

        include "conexionBD.php";

        $consulta = $conexion->prepare(
            "SELECT * FROM Usuarios WHERE emailUsuario = ?"
        );

        $consulta->bind_param("s", $gmail);

        $consulta->execute();

        $resultado = $consulta->get_result();

        if ($resultado->num_rows > 0) {

            $_SESSION["gmailIngreso"] = $gmail;

            $consulta->close();
            $conexion->close();

            header("Location: ../ingresoContra.php");
            exit();

        } else {

            $consulta->close();
            $conexion->close();

            header("Location: ../inicioSesion.php?error=usuario");
            exit();
        }
    }

} else {

    echo "Acceso no válido.";
}

?>