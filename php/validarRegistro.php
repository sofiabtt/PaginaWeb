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

            $consulta->close();
            $conexion->close();

            header(
                "Location: ../registro.html?error=usuario_existente&gmail="
                . urlencode($gmail)
            );
            exit();

        } else {

            $_SESSION["gmailRegistro"] = $gmail;

            $consulta->close();
            $conexion->close();

            header("Location: ../registroDatosPersonales.html");
            exit();
        }
    }

} else {

    echo "Acceso no válido.";
}

?>