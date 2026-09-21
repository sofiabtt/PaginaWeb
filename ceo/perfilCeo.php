<?php 

session_start();

if (!isset($_SESSION["codUsuario"]) || strtolower($_SESSION["tipoUsuario"] ?? "") !== "ceo") {

    header("Location: ../inicioSesion.php");
    exit;

}

include "../php/conexionBD.php";
include "../php/consultasUsuarios.php";

$codCeo = (int) $_SESSION["codUsuario"];

//Si se envió el formulario
if ($_SERVER["REQUEST_METHOD"] == "POST") {

    $nombre = $_POST["nombreUsuario"];
    $email = $_POST["emailUsuario"];
    $telefono = $_POST["telefonoUsuario"];
    $claveNueva = $_POST["claveNueva"] ?? "";
    $confirmarClave = $_POST["confirmarClave"] ?? "";

    //Validar contraseña si se cargó 

    if ($claveNueva !== "" && strlen($claveNueva) < 8) {

        $mensaje = "La contraseña nueva debe tener al menos 8 caracteres.";
        $error = true;

    } elseif ($claveNueva !== $confirmarClave) {

        $mensaje = "Las contraseñas no coinciden.";
        $error = true;

    } else {
        //Verificar que el email no esté en uso por otra cuenta
        $existente = obtenerUsuarioPorEmail($conexion, $email);

        if($existente && (int) $existente["codUsuario"] !== $codCeo) {
            $mensaje = "Ese email ya está en uso por otra cuenta.";
            $error = true;

        } else {

            //Actualizar datos del ceo
            $actualizado = modificarUsuario($conexion, $codCeo, $nombre, $email, $telefono);

            //Si se cargó una contraseña nueva, actualizarla
            if ($actualizado && $claveNueva !== "") {

                $actualizado = modificarContraseñaUsuario($conexion, $codCeo, $claveNueva);

            }

            if ($actualizado) {

                //Mantener actualizado el nombre en la sesión 
                $_SESSION["nombreUsuario"] = $nombre;

                //Redirigir después de guardar 
                header("Location: perfilCeo.php?actualizado=1");
                exit;

            } else {

                $mensaje = "Ocurrió un error al actualizar los datos.";
                $error = true;

            }
        }
    }
}

if (isset($_GET["actualizado"])) {

    $mensaje = "Los datos se actualizaron correctamente.";

}

//Buscar los datos del ceo
$ceo = obtenerUsuario($conexion, $codCeo);

?>

<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <title>Nuvia - Ceo</title>

    <link rel="stylesheet" href="../css/bootstrap.min.css">
    <link rel="stylesheet" href="../css/bootstrap-icons.css">

    <link rel="icon" type="image/png" href="../imagenes/logo.png">

    <link rel="stylesheet" href="../css/estilos-admin.css">
    <link rel="stylesheet" href="../css/navbar.css">
    <link rel="stylesheet" href="../css/footer.css">

</head>

<body>

    <?php include"includes/navbarCeo.php"; ?>

    <main class="contenido-admin">

        <section class="encabezado-contenido">

            <div>
                <h1>Mi perfil</h1>

                <p>Gestioná tus datos personales.</p>
            </div>

        </section>

        <?php if(isset($mensaje)) { ?>

            <div class="alert <?php echo isset($error) ? 'alert-danger' : 'alert-success'; ?>" role="alert">
                <?php echo $mensaje; ?>
            </div>

        <?php } ?>

        <!-- Perfil -->
        
        <section class="container-fluid px-0">

            <div class="card border-0 shadow-sm mx-auto" style="max-width: 850px">

                <div class="card-body text-center p-4 p-md-5">

                    <div class="icono rounded-circle d-flex align-items-center justify-content-center mx-auto mb-3">
    
                        <i class="bi bi-person-fill"></i>
    
                    </div>
    
                    <h2 class="text-muted mb-4">Perfil del CEO</h2>
    
                    <p class="text-muted mb-4">Gestioná la información de tu cuenta.</p>
    
                    <!--Formulario-->
                    <form method="POST" action="perfilCeo.php" class="text-start">
    
                        <div class="row g-4">
    
                            <div class="col-md-6">
    
                                <label for="nombreUsuario" class="form-label fw-semibold">Nombre</label>
    
                                <div class="input-group">
    
                                    <span class="input-group-text">
                                        <i class="bi bi-person"></i>
                                    </span>
    
                                    <input type="text" class="form-control" id="nombreUsuario" name="nombreUsuario" value="<?php echo htmlspecialchars($ceo["nombreUsuario"]); ?>" required>
    
                                </div>
    
                            </div>

                            <div class="col-md-6">
        
                                <label for="emailUsuario" class="form-label fw-semibold">Email</label>
        
                                <div class="input-group">
        
                                    <span class="input-group-text">
                                        <i class="bi bi-envelope"></i>
                                    </span>
        
                                    <input type="email" class="form-control" id="emailUsuario" name="emailUsuario" value="<?php echo htmlspecialchars($ceo["emailUsuario"]); ?>" required>
        
                                </div>
        
                            </div>
        
                            <div class="col-md-6">
        
                                <label for="telefonoUsuario" class="form-label fw-semibold">Teléfono</label>
        
                                <div class="input-group">
        
                                    <span class="input-group-text">
                                        <i class="bi bi-telephone"></i>
                                    </span>
        
                                    <input type="tel" class="form-control" id="telefonoUsuario" name="telefonoUsuario" value="<?php echo htmlspecialchars($ceo["telefonoUsuario"]); ?>" required>
        
                                </div>

                            </div>

                        </div>

                        <div class="row g-4 mt-1">

                            <!--Nueva contraseña-->
                            <div class="col-md-6">

                                <label for="claveNueva" class="form-label fw-semibold">
                                    Nueva contraseña (opcional)
                                </label>

                                <div class="input-group">

                                    <span class="input-group-text">
                                        <i class="bi bi-lock"></i>
                                    </span>

                                    <input type="password" class="form-control" id="claveNueva" name="claveNueva" minlength="8" autocomplete="new-password">

                                    <button class="input-group-text toggle-password" type="button" data-target="claveNueva" style="cursor: pointer;" aria-label="Mostrar u ocultar contraseña">
                                        <i class="bi bi-eye"></i>
                                    </button>

                                </div>

                            </div>

                            <!--Confirmar contraseña-->
                            <div class="col-md-6">

                                <label for="confirmarClave" class="form-label fw-semibold">
                                    Confirmar contraseña
                                </label>

                                <div class="input-group">

                                    <span class="input-group-text">
                                        <i class="bi bi-lock-fill"></i>
                                    </span>

                                    <input type="password" class="form-control" id="confirmarClave" name="confirmarClave" minlength="8" autocomplete="new-password">

                                    <button class="input-group-text toggle-password" type="button" data-target="confirmarClave" style="cursor: pointer;" aria-label="Mostrar u ocultar contraseña">
                                        <i class="bi bi-eye"></i>
                                    </button>

                                </div>

                            </div>

                        </div>

                        <p class="small text-muted mt-2 mb-4">
                            Deja estos campos en blanco si no querés cambiar tu contraseña.
                        </p>

                        <div class="text-end">

                                <button type="submit" class="btn btn-primary">
                                    <i class="bi bi-check-lg"></i>
                                    Guardar cambios
                                </button>

                        </div>
    
                    </form>

                </div>

            </div>

        </section>

    </main>

    <?php include"../includes/footer.php"; ?>

    <script src="/PaginaWeb/js/bootstrap.bundle.min.js"></script> 

    <script>

        //Mostrar/ocultar contraseña

        document.querySelectorAll(".toggle-password").forEach(function (boton) {

            boton.addEventListener("click", function () {

                var input = document.getElementById(boton.dataset.target);
                var icono = boton.querySelector("i");

                if (input.type === "password") {

                    input.type = "text";
                    icono.classList.remove("bi-eye");
                    icono.classList.add("bi-eye-slash");

                } else {

                    input.type = "password";
                    icono.classList.remove("bi-eye-slash");
                    icono.classList.add("bi-eye");

                }

            });

        });

    </script>

</body>

</html>