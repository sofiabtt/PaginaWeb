
<?php

session_start();

if ($_SERVER["REQUEST_METHOD"] == "POST") {

    $gmail = $_POST["gmail"];

    if (empty($gmail)) {

        echo "Debe ingresar un mail.";

    } else {

        require_once __DIR__ . "/conexionBD.php";

        $consulta = $conexion->prepare(
            "SELECT * FROM usuarios WHERE emailUsuario = ?"
        );

        $consulta->bind_param("s", $gmail);

        $consulta->execute();

        $resultado = $consulta->get_result();

        if ($resultado->num_rows > 0) {

            $usuario = $resultado->fetch_assoc();

            //Todavía no se encuentra autenticado
            $_SESSION["gmailIngreso"] = $gmail;

            $consulta->close();
            $conexion->close();

            header("Location: /ingresoContra.php");
            exit();

        } else {

            $consulta->close();
            $conexion->close();

            header("Location: /inicioSesion.php?error=usuario");
            exit();
        }
    }

} else {

    echo "Acceso no válido.";
}

?>

<?php if (!isset($_SESSION["gmailIngreso"])): ?>

    <!-- Navbar público -->

<?php elseif ($_SESSION["tipoUsuario"] == "usuario"): ?>

    <!-- Navbar del pasajero -->

<?php endif; ?>
