<?php

require_once __DIR__ . '/../../php/conexionBD.php';

if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

$primerNombre = null;

if (isset($_SESSION["nombreUsuario"])) {
    $primerNombre = explode(" ", $_SESSION["nombreUsuario"])[0];
}

?>

<nav class="navbar navbar-expand-xl">
    <div class="container-fluid">

        <!-- LOGO -->
        <a class="navbar-brand"
           href="<?= htmlspecialchars(urlApp('admin/admin.php'), ENT_QUOTES, 'UTF-8') ?>">

            <img src="<?= htmlspecialchars(urlApp('imagenes/logo.png'), ENT_QUOTES, 'UTF-8') ?>"
                 alt="Logo Nuvia">

            <h1>Nuvia</h1>
        </a>

        <!-- PERFIL Y BOTÓN HAMBURGUESA EN CELULARES -->
        <div class="acciones-navbar-mobile d-xl-none">

            <?php if ($primerNombre !== null) { ?>

                <a class="perfil-navbar-mobile"
                   href="<?= htmlspecialchars(urlApp('admin/perfilAdmin.php'), ENT_QUOTES, 'UTF-8') ?>"
                   aria-label="Ir a mi perfil">

                    <i class="bi bi-person-circle"></i>

                    <span>
                        <?= htmlspecialchars($primerNombre, ENT_QUOTES, 'UTF-8') ?>
                    </span>

                </a>

            <?php } ?>

            <button class="navbar-toggler"
                    type="button"
                    data-bs-toggle="collapse"
                    data-bs-target="#navbarNavAltMarkup"
                    aria-controls="navbarNavAltMarkup"
                    aria-expanded="false"
                    aria-label="Abrir menú de navegación">

                <span class="navbar-toggler-icon"></span>

            </button>

        </div>

        <!-- MENÚ DE NAVEGACIÓN -->
        <div class="collapse navbar-collapse" id="navbarNavAltMarkup">

            <div class="navbar-nav-general">

                <!-- INICIO -->
                <a class="nav-link"
                   href="<?= htmlspecialchars(urlApp('index.php'), ENT_QUOTES, 'UTF-8') ?>">
                    Inicio
                </a>

                <!-- PANEL ADMIN -->
                <a class="nav-link"
                   href="<?= htmlspecialchars(urlApp('admin/admin.php'), ENT_QUOTES, 'UTF-8') ?>">
                    Panel
                </a>

                <!-- AEROLÍNEAS -->
                <a class="nav-link"
                   href="<?= htmlspecialchars(urlApp('admin/aerolineas/gestionAerolineas.php'), ENT_QUOTES, 'UTF-8') ?>">
                    Aerolíneas
                </a>

                <!-- VUELOS -->
                <a class="nav-link"
                   href="<?= htmlspecialchars(urlApp('admin/vuelos/gestionVuelos.php'), ENT_QUOTES, 'UTF-8') ?>">
                    Vuelos
                </a>

                <!-- PROMOCIONES -->
                <a class="nav-link"
                   href="<?= htmlspecialchars(urlApp('admin/promociones/gestionPromociones.php'), ENT_QUOTES, 'UTF-8') ?>">
                    Promociones
                </a>

                <!-- NOVEDADES -->
                <a class="nav-link"
                   href="<?= htmlspecialchars(urlApp('admin/novedades/gestionNovedades.php'), ENT_QUOTES, 'UTF-8') ?>">
                    Novedades
                </a>

                <!-- REPORTES -->
                <a class="nav-link"
                   href="<?= htmlspecialchars(urlApp('admin/reportes/gestionReportes.php'), ENT_QUOTES, 'UTF-8') ?>">
                    Reportes
                </a>

                <!-- USUARIOS -->
                <a class="nav-link"
                   href="<?= htmlspecialchars(urlApp('admin/usuarios/gestionUsuarios.php'), ENT_QUOTES, 'UTF-8') ?>">
                    Usuarios
                </a>

                <!-- CEOs -->
                <a class="nav-link"
                   href="<?= htmlspecialchars(urlApp('admin/ceos/gestionCeos.php'), ENT_QUOTES, 'UTF-8') ?>">
                    CEOs
                </a>

            </div>

            <!-- PERFIL EN ESCRITORIO -->
            <div class="opciones-cuenta dropdown">

                <button class="info-usuario dropdown-toggle"
                        type="button"
                        data-bs-toggle="dropdown"
                        aria-expanded="false">

                    <i class="bi bi-person-circle"></i>

                    Hola, <?= htmlspecialchars($primerNombre ?? '', ENT_QUOTES, 'UTF-8') ?>!

                </button>

                <ul class="menu-usuarios dropdown-menu dropdown-menu-end">

                    <li>
                        <a class="dropdown-item"
                           href="<?= htmlspecialchars(urlApp('admin/perfilAdmin.php'), ENT_QUOTES, 'UTF-8') ?>">

                            <i class="bi bi-person"></i>
                            Mi perfil

                        </a>
                    </li>

                    <li>
                        <hr class="dropdown-divider">
                    </li>

                    <li>
                        <a class="dropdown-item"
                           href="<?= htmlspecialchars(urlApp('php/cerrarSesion.php'), ENT_QUOTES, 'UTF-8') ?>">

                            <i class="bi bi-box-arrow-right"></i>
                            Cerrar sesión

                        </a>
                    </li>

                </ul>

            </div>

            <!-- PERFIL Y CERRAR SESIÓN EN CELULARES -->
            <div class="usuario-mobile">

                <a class="nav-link"
                   href="<?= htmlspecialchars(urlApp('admin/perfilAdmin.php'), ENT_QUOTES, 'UTF-8') ?>">

                    <i class="bi bi-person"></i>
                    Mi perfil

                </a>

                <a class="nav-link"
                   href="<?= htmlspecialchars(urlApp('php/cerrarSesion.php'), ENT_QUOTES, 'UTF-8') ?>">

                    <i class="bi bi-box-arrow-right"></i>
                    Cerrar sesión

                </a>

            </div>

        </div>

    </div>
</nav>