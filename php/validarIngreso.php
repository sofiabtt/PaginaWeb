<?php

session_start();

require_once __DIR__ . "/conexionBD.php";

if ($_SERVER["REQUEST_METHOD"] == "POST") {

    $gmail = trim($_POST["gmail"] ?? "");

    if (empty($gmail)) {

        echo "Debe ingresar un mail.";

    } else {

        $consulta = $conexion->prepare(
            "SELECT * FROM usuarios WHERE emailUsuario = ?"
        );

        $consulta->bind_param("s", $gmail);

        $consulta->execute();

        $resultado = $consulta->get_result();

        if ($resultado->num_rows > 0) {

            $usuario = $resultado->fetch_assoc();

            // Todavía no se encuentra autenticado.
            // Guardamos el correo para el siguiente paso.

            $_SESSION["gmailIngreso"] = $gmail;

            $consulta->close();
            $conexion->close();

            header("Location: " . urlApp("ingresoContra.php"));
            exit();

        } else {

            $consulta->close();
            $conexion->close();

            header(
                "Location: " .
                urlApp("inicioSesion.php?error=usuario")
            );
            exit();

        }

    }

} else {

    echo "Acceso no válido.";

}

?>