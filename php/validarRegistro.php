<?php
// para recordar el gmail si es que existe y enviarlo usando una sesion
session_start();

if ($_SERVER["REQUEST_METHOD"] == "POST") {

    $gmail = $_POST["gmail"];

    if (empty($gmail)) {

        echo "Debe ingresar un mail.";

    } else {
        // Crear conexión       servidor  usuario  usuario  baseDatos
        $conexion = new mysqli("localhost","root","","Aerolineas");

         // Verificar si hubo error de conexión
        if ($conexion->connect_error) {
            die("Error de conexión: " . $conexion->connect_error);
        }
        // Preparar la consulta
        $consulta = $conexion->prepare(
            "SELECT * FROM Usuarios WHERE emailUsuario = ?"
        );

        // Colocar el gmail recibido en el signo ?
        $consulta->bind_param("s", $gmail);

        // Ejecutar consulta
        $consulta->execute();

        // Obtener resultado
        $resultado = $consulta->get_result();

        // Comprobar si encontró un usuario
        if ($resultado->num_rows > 0) {
            header("Location: ../html/registro.html?error=usuario_existente&gmail=" . urlencode($gmail));
            //mando también el Gmail en la URL
            exit();

        } else {

            $_SESSION["gmailRegistro"] = $gmail;
            header("Location: ../html/registro-DatosPersonales.html");
            exit();

        }

        // Cerrar consulta y conexión
        $consulta->close();
        $conexion->close();
    }

} else {

    echo "Acceso no válido.";
}

?>