<?php
require_once __DIR__ . '/../php/conexionBD.php';

$estaLogueado = isset($_SESSION["tipoUsuario"]);
$tipoUsuario = $_SESSION["tipoUsuario"] ?? null;
?>

<footer class="footer mt-4">

    <div class="container">

        <div class="row">

            <!-- =====================================
                 LOGO
            ===================================== -->

            <div class="footer-logo col-12 col-lg-2">

                <a href="<?= htmlspecialchars(urlApp('index.php'), ENT_QUOTES, 'UTF-8') ?>">

                    <img
                        src="<?= htmlspecialchars(urlApp('imagenes/logo.png'), ENT_QUOTES, 'UTF-8') ?>"
                        alt="Nuvia"
                        class="img-fluid"
                    >

                </a>

            </div>


            <!-- =====================================
                 MI CUENTA
            ===================================== -->

            <div class="footer-columna col-6 col-lg-3">

                <h3>Mi cuenta</h3>

                <ul>

                    <?php if ($estaLogueado) { ?>

                        <li>
                            <a href="<?= htmlspecialchars(urlApp('usuario/perfil.php'), ENT_QUOTES, 'UTF-8') ?>">
                                Mi perfil
                            </a>
                        </li>

                        <li>
                            <a href="<?= htmlspecialchars(urlApp('php/cerrarSesion.php'), ENT_QUOTES, 'UTF-8') ?>">
                                Cerrar sesión
                            </a>
                        </li>

                    <?php } else { ?>

                        <li>
                            <a href="<?= htmlspecialchars(urlApp('inicioSesion.php'), ENT_QUOTES, 'UTF-8') ?>">
                                Iniciar sesión
                            </a>
                        </li>

                        <li>
                            <a href="<?= htmlspecialchars(urlApp('registroDatosPersonales.php'), ENT_QUOTES, 'UTF-8') ?>">
                                Registrarse
                            </a>
                        </li>

                        <li>
                            <a href="<?= htmlspecialchars(urlApp('usuarioNOregistrado/enviarRecuperacion.php'), ENT_QUOTES, 'UTF-8') ?>">
                                Recuperar contraseña
                            </a>
                        </li>

                    <?php } ?>

                </ul>

            </div>


            <!-- =====================================
                 NUVIA
            ===================================== -->

            <div class="footer-columna col-6 col-lg-3">

                <h4>Nuvia</h4>

                <ul>

                    <li>
                        <a href="<?= htmlspecialchars(urlApp('preguntasFrecuentes.php'), ENT_QUOTES, 'UTF-8') ?>">
                            Preguntas frecuentes
                        </a>
                    </li>

                    <li>
                        <a href="#">
                            Acerca de nosotros
                        </a>
                    </li>

                    <li>
                        <a href="<?= htmlspecialchars(urlApp('contacto.php'), ENT_QUOTES, 'UTF-8') ?>">
                            Contacto
                        </a>
                    </li>

                </ul>

            </div>


            <!-- =====================================
                 MAPA DEL SITIO
            ===================================== -->

            <div class="footer-columna col-12 col-lg-4">

                <h4>Mapa del sitio</h4>


                <!-- USUARIO NO REGISTRADO -->

                <?php if (!$estaLogueado) { ?>

                    <div class="row">

                        <div class="col-12">

                            <ul>

                                <li>
                                    <a href="<?= htmlspecialchars(urlApp('index.php'), ENT_QUOTES, 'UTF-8') ?>">
                                        Inicio
                                    </a>
                                </li>

                                <li>
                                    <a href="<?= htmlspecialchars(urlApp('index.php#buscador'), ENT_QUOTES, 'UTF-8') ?>">
                                        Buscar vuelos
                                    </a>
                                </li>

                            </ul>

                        </div>

                    </div>

                <?php } ?>


                <!-- USUARIO REGISTRADO -->

                <?php if ($tipoUsuario === "usuario") { ?>

                    <div class="row">

                        <div class="col-6">

                            <ul>

                                <li>
                                    <a href="<?= htmlspecialchars(urlApp('index.php'), ENT_QUOTES, 'UTF-8') ?>">
                                        Inicio
                                    </a>
                                </li>

                                <li>
                                    <a href="<?= htmlspecialchars(urlApp('index.php#buscador'), ENT_QUOTES, 'UTF-8') ?>">
                                        Buscar vuelos
                                    </a>
                                </li>

                                <li>
                                    <a href="<?= htmlspecialchars(urlApp('usuario/novedades.php'), ENT_QUOTES, 'UTF-8') ?>">
                                        Novedades
                                    </a>
                                </li>

                            </ul>

                        </div>


                        <div class="col-6">

                            <ul>

                                <li>
                                    <a href="<?= htmlspecialchars(urlApp('usuario/reservas/gestionReservas.php'), ENT_QUOTES, 'UTF-8') ?>">
                                        Mis reservas
                                    </a>
                                </li>

                                <li>
                                    <a href="<?= htmlspecialchars(urlApp('usuario/historialCompras.php'), ENT_QUOTES, 'UTF-8') ?>">
                                        Historial de compras
                                    </a>
                                </li>

                            </ul>

                        </div>

                    </div>

                <?php } ?>


                <!-- ADMINISTRADOR -->

                <?php if ($tipoUsuario === "administrador") { ?>

                    <div class="row">

                        <div class="col-6">

                            <ul>

                                <li>
                                    <a href="<?= htmlspecialchars(urlApp('index.php'), ENT_QUOTES, 'UTF-8') ?>">
                                        Inicio
                                    </a>
                                </li>

                                <li>
                                    <a href="<?= htmlspecialchars(urlApp('admin/admin.php'), ENT_QUOTES, 'UTF-8') ?>">
                                        Panel de administración
                                    </a>
                                </li>

                                <li>
                                    <a href="<?= htmlspecialchars(urlApp('admin/aerolineas/gestionAerolineas.php'), ENT_QUOTES, 'UTF-8') ?>">
                                        Gestión de aerolíneas
                                    </a>
                                </li>

                                <li>
                                    <a href="<?= htmlspecialchars(urlApp('admin/novedades/gestionNovedades.php'), ENT_QUOTES, 'UTF-8') ?>">
                                        Gestión de novedades
                                    </a>
                                </li>

                                <li>
                                    <a href="<?= htmlspecialchars(urlApp('admin/vuelos/gestionVuelos.php'), ENT_QUOTES, 'UTF-8') ?>">
                                        Reporte de vuelos
                                    </a>
                                </li>

                            </ul>

                        </div>


                        <div class="col-6">

                            <ul>

                                <li>
                                    <a href="<?= htmlspecialchars(urlApp('admin/usuarios/gestionUsuarios.php'), ENT_QUOTES, 'UTF-8') ?>">
                                        Reporte de usuarios
                                    </a>
                                </li>

                                <li>
                                    <a href="<?= htmlspecialchars(urlApp('admin/ceos/gestionCeos.php'), ENT_QUOTES, 'UTF-8') ?>">
                                        Gestión de CEOs
                                    </a>
                                </li>

                                <li>
                                    <a href="<?= htmlspecialchars(urlApp('admin/promociones/gestionPromocion.php'), ENT_QUOTES, 'UTF-8') ?>">
                                        Aprobar promociones
                                    </a>
                                </li>

                                <li>
                                    <a href="<?= htmlspecialchars(urlApp('admin/reportes.php'), ENT_QUOTES, 'UTF-8') ?>">
                                        Reportes
                                    </a>
                                </li>

                            </ul>

                        </div>

                    </div>

                <?php } ?>


                <!-- CEO -->

                <?php if ($tipoUsuario === "ceo") { ?>

                    <ul>

                        <li>
                            <a href="<?= htmlspecialchars(urlApp('index.php'), ENT_QUOTES, 'UTF-8') ?>">
                                Inicio
                            </a>
                        </li>

                        <li>
                            <a href="<?= htmlspecialchars(urlApp('ceo/ceo.php'), ENT_QUOTES, 'UTF-8') ?>">
                                Panel de gestión
                            </a>
                        </li>

                        <li>
                            <a href="<?= htmlspecialchars(urlApp('ceo/vuelos/gestionVuelos.php'), ENT_QUOTES, 'UTF-8') ?>">
                                Gestión de vuelos
                            </a>
                        </li>

                        <li>
                            <a href="<?= htmlspecialchars(urlApp('ceo/promociones/gestionPromocion.php'), ENT_QUOTES, 'UTF-8') ?>">
                                Gestión de promociones
                            </a>
                        </li>

                        <li>
                            <a href="<?= htmlspecialchars(urlApp('ceo/reportes.php'), ENT_QUOTES, 'UTF-8') ?>">
                                Reportes
                            </a>
                        </li>

                    </ul>

                <?php } ?>

            </div>

        </div>


        <!-- =====================================
             PARTE FINAL DEL FOOTER
        ===================================== -->

        <div class="footer-final">

            <div class="row">

                <div class="col-12 text-center">

                    <p>

                        &copy; <?= date("Y") ?> Nuvia.
                        Todos los derechos reservados.

                        <span class="separator">|</span>

                        <a href="#">
                            Términos y condiciones
                        </a>

                        <span class="separator">|</span>

                        <a href="#">
                            Política de privacidad
                        </a>

                    </p>

                </div>

            </div>

        </div>

    </div>

</footer>