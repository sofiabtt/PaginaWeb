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
                <img src="/imagenes/logo.png" alt="logo" class="img-fluid">
            </div>

            <div class="footer-columna col-6 col-lg-3">
                <h4>Mi cuenta</h4>

                <ul>
                <?php if ($estaLogueado) { ?>
                    <li><a href="/usuario/perfil.php">Mi perfil</a></li>
                    <li><a href="/php/cerrarSesion.php">Cerrar sesión</a></li>
                <?php } else { ?>
                    <li><a href="/inicioSesion.php">Iniciar sesión</a></li>
                    <li><a href="/registroDatosPersonales.php">Registrarse</a></li>
                    <li><a href="/usuarioNOregistrado/enviarRecuperacion.php">Recuperar contraseña</a></li> 
                <?php } ?>
                
                </ul>
            </div>

            <div class="footer-columna col-6 col-lg-3">
                <h4>Nuvia</h4>
                <ul>
                    <li><a href="#">Preguntas frecuentes</a></li>
                    <li><a href="#">Acerca de nosotros</a></li>
                    <li><a href="/contacto.php">Contacto</a></li>
                </ul>
            </div>

            <div class="footer-columna col-12 col-lg-4">
                <h4>Mapa del sitio</h4>
                
                <?php if(!$estaLogueado) { ?>
                    <div class="row">
                        <div class="col-6">
                            <ul>
                                <li><a href="/index.php">Inicio</a></li>
                                <li><a href="/index.php#buscador">Buscar vuelos</a></li>
                            </ul>
                        
                    </div>
                <?php } ?>

                <?php if($tipoUsuario === "usuario") { ?>
                    <div class="row">
                        <div class="col-6">
                            <ul>
                                <li><a href="/index.php">Inicio</a></li>
                                <li><a href="/index.php#buscador">Buscar Vuelos</a></li>
                                <li><a href="/usuario/novedades.php">Novedades</a></li>
                            </ul>
                        </div>
                    
                        <div class="col-6">
                            <ul>
                                <li><a href="/usuario/reservas/gestionReservas.php">Mis reservas</a></li>
                                <li><a href="/usuario/historialCompras.php">Historial de compras</a></li>
                            </ul>
                        </div>
                    </div>
                <?php } ?>

                <?php if($tipoUsuario === "administrador") { ?>
                    <div class="row">
                        <div class="col-6">
                            <ul>
                                <li><a href="/index.php">Inicio</a></li>
                                <li><a href="/admin/admin.php">Panel de administración</a></li>
                                <li><a href="/admin/aerolineas/gestionAerolineas.php">Gestión de aerolíneas</a></li>
                                <li><a href="/admin/novedades/gestionNovedades.php">Gestión de novedades</a></li>
                                <li><a href="/admin/vuelos/gestionVuelos.php">Reporte de vuelos</a></li>
                            </ul>
                        </div>
                        <div class="col-6">
                            <ul>
                                <li><a href="/admin/usuarios/gestionUsuarios.php">Reporte de usuarios</a></li>
                                <li><a href="/admin/ceos/gestionCeos.php">Gestión de CEOs</a></li>
                                <li><a href="/admin/promociones/gestionPromocion.php">Aprobar promociones</a></li>
                                <li><a href="/admin/reportes.php">Reportes</a></li>
                            </ul>
                        </div>
                    </div>
                <?php } ?>

                <?php if($tipoUsuario === "ceo") { ?>
                    <ul>
                        <li><a href="/index.php">Inicio</a></li>
                        <li><a href="/ceo/ceo.php">Panel de gestión</a></li>
                        <li><a href="/ceo/vuelos/gestionVuelos.php">Gestión de vuelos</a></li>
                        <li><a href="/ceo/promociones/gestionPromocion.php">Gestión de promociones</a></li>
                        <li><a href="/ceo/reportes.php">Reportes</a></li>
                    </ul>
                <?php } ?>
            </div>
        </div>

    <div class="footer-final">
        <div class="container">
            <div class="row">
                <div class="col-12 text-center">
                    <p>
                        &copy; <?php echo date("Y"); ?> Nuvia. Todos los derechos reservados. 
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
