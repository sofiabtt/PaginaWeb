
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

            $usuario = $resultado->fetch_assoc();

            //Todavía no se encuentra autenticado
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
```

Ahora la sesión va a saber, por ejemplo:

```text
gmailIngreso   → viole@gmail.com
codUsuario     → 5
tipoUsuario    → ceo
nombreUsuario  → Viole Magliaro
```

---

### 2. Pero hay algo más importante

Como me dijiste que **`resultadosVuelos.php` es para los usuarios/pasajeros**, yo no haría que un CEO vea ahí "Mis reservas" y su nombre.

En esa página podemos hacer:

```php
<?php if (!isset($_SESSION["gmailIngreso"])): ?>

    <!-- Navbar público -->

<?php elseif ($_SESSION["tipoUsuario"] == "usuario"): ?>

    <!-- Navbar del pasajero -->

<?php endif; ?>
