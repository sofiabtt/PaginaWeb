<?php
if (isset($_SESSION["tipoUsuario"])) {
    $estaLogueado = true;
    $tipoUsuario = $_SESSION["tipoUsuario"];
} else {
    $estaLogueado = false;
    $tipoUsuario = null;
}

$paginaActual = basename($_SERVER["PHP_SELF"]);
?>

<footer class="footer mt-4">
    <div class="container">
        <div class="row">
            <div class="footer-logo col-12 col-lg-2">
                <img src="../imagenes/logo.png" alt="logo" class="img-fluid">
            </div>

            <div class="footer-columna col-6 col-lg-3">
                <h4>Mi cuenta</h4>

                <ul>
                <?php if ($estaLogueado) { ?>
                    <li><a href="#">Mi perfil</a></li>
                    <li><a href="#">Cerrar sesión</a></li>
                    <li><a href="#">Eliminar cuenta</a></li>
                <?php } else { ?>
                    <li><a href="inicioSesion.php">Iniciar sesión</a></li>
                    <li><a href="registroDatosPersonales.html">Registrarse</a></li>
                    <li><a href="#">Recuperar contraseña</a></li> 
                <?php } ?>
                
                <?php if($tipoUsuario === "usuario") { ?>
                    <li><a href="#">Mis viajes</a></li>
                <?php } elseif($tipoUsuario === "ceo"){ ?>
                    <li><a href="#">Mis aerolíneas</a></li>
                <?php } ?>
                </ul>
            </div>

            <div class="footer-columna col-6 col-lg-3">
                <h4>NOMBRE-EMPRESA</h4>
                <ul>
                    <li><a href="#">Preguntas frecuentes</a></li>
                    <li><a href="#">Acerca de nosotros</a></li>
                    <li><a href="tel:341 9 6551718">341 9 6551718</a></li>
                    <li><a href="mailto:contacto@nombreEmpresa.com">contacto@nombreEmpresa.com</a></li>
                </ul>
            </div>

            <div class="footer-columna col-12 col-lg-4">
                <h4>Mapa del sitio</h4>
                
                <?php if($tipoUsuario === null) { ?>
                    <div class="row">
                        <div class="col-6">
                            <ul>
                                <li><a href="#">Inicio</a></li>
                                <li><a href="#">Buscar vuelos</a></li>
                                <li><a href="#">Promociones vigentes</a></li>
                                <li><a href="#">Novedades</a></li>
                            </ul>
                        </div>
                        <div class="col-6">
                            <ul>
                                <li><a href="inicioSesion.php">Iniciar sesión</a></li>
                                <li><a href="registroDatosPersonales.html">Registrarse</a></li>
                            </ul>
                        </div>
                    </div>
                <?php } ?>

                <?php if($tipoUsuario === "usuario") { ?>
                    <div class="row">
                        <div class="col-6">
                            <ul>
                                <li><a href="#">Inicio</a></li>
                                <li><a href="#">Buscar Vuelos</a></li>
                                <li><a href="#">Promociones vigentes</a></li>
                                <li><a href="#">Novedades</a></li>
                            </ul>
                        </div>
                    
                        <div class="col-6">
                            <ul>
                                <li><a href="#">Mis reservas</a></li>
                                <li><a href="#">Historial de compras</a></li>
                            </ul>
                        </div>
                    </div>
                <?php } ?>

                <?php if($tipoUsuario === "admin") { ?>
                    <div class="row">
                        <div class="col-6">
                            <ul>
                                <li><a href="#">Inicio</a></li>
                                <li><a href="#">Gestión de aerolíneas</a></li>
                                <li><a href="#">Gestión de novedades</a></li>
                            </ul>
                        </div>
                        <div class="col-6">
                            <ul>
                                <li><a href="#">Aprobar promociones</a></li>
                                <li><a href="#">Reportes</a></li>
                            </ul>
                        </div>
                    </div>
                <?php } ?>

                <?php if($tipoUsuario === "ceo") { ?>
                    <ul>
                        <li><a href="#">Inicio</a></li>
                        <li><a href="#">Gestión de vuelos</a></li>
                        <li><a href="#">Gestión de promociones</a></li>
                        <li><a href="#">Reportes</a></li>
                    </ul>
                <?php } ?>
            </div>
        </div>

    <div class="footer-final">
        <div class="container">
            <div class="row">
                <div class="col-12 text-center">
                    <p>
                        &copy; <?php echo date("Y"); ?> NOMBRE-EMPRESA. Todos los derechos reservados. 
                        <span class="separator">|</span>
                        <a href="#">Términos y condiciones</a>
                        <span class="separator">|</span>
                        <a href="#">Política de privacidad</a>
                    </p>
                </div>
            </div>
        </div>
    </div>
</footer>