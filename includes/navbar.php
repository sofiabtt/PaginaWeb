<?php

// Cargar la conexión y la función urlApp()
require_once __DIR__ . '/../php/conexionBD.php';

if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

$estaLogueado = isset($_SESSION["codUsuario"]);

if ($estaLogueado) {

    $tipoUsuario = $_SESSION["tipoUsuario"];
    $primerNombre = explode(" ", $_SESSION["nombreUsuario"])[0];

    // Ruta del perfil según el tipo de usuario
    if ($tipoUsuario === "administrador") {
        $rutaPerfil = "admin/perfilAdmin.php";
    } elseif ($tipoUsuario === "ceo") {
        $rutaPerfil = "ceo/perfilCeo.php";
    } else {
        $rutaPerfil = "usuario/perfil.php";
    }

} else {

    $tipoUsuario = null;
    $primerNombre = null;
    $rutaPerfil = null;

}

?>

<nav class="navbar navbar-expand-xl">
    <div class="container-fluid">

        <!-- LOGO NUVIA -->
        <a class="navbar-brand" href="<?= htmlspecialchars(urlApp('index.php'), ENT_QUOTES, 'UTF-8') ?>">

            <img
                src="<?= htmlspecialchars(urlApp('imagenes/logo.png'), ENT_QUOTES, 'UTF-8') ?>"
                alt="Logo Nuvia"
            >

            <h1>Nuvia</h1>
        </a>

        <!-- ACCIONES DEL NAVBAR RESPONSIVE -->
        <div class="acciones-navbar-mobile d-xl-none">

            <?php if ($estaLogueado) { ?>

                <!-- USUARIO LOGUEADO -->
                <a
                    class="perfil-navbar-mobile"
                    href="<?= htmlspecialchars(urlApp($rutaPerfil), ENT_QUOTES, 'UTF-8') ?>"
                    aria-label="Ir a mi perfil"
                >
                    <i class="bi bi-person-circle"></i>

                    <span>
                        <?= htmlspecialchars($primerNombre, ENT_QUOTES, 'UTF-8') ?>
                    </span>
                </a>

            <?php } ?>

            <!-- MENÚ HAMBURGUESA -->
            <button
                class="navbar-toggler"
                type="button"
                data-bs-toggle="collapse"
                data-bs-target="#navbarNavAltMarkup"
                aria-controls="navbarNavAltMarkup"
                aria-expanded="false"
                aria-label="Abrir menú de navegación"
            >
                <span class="navbar-toggler-icon"></span>
            </button>

        </div>

        <div class="collapse navbar-collapse" id="navbarNavAltMarkup">

            <!-- NAVEGACIÓN GENERAL -->
            <div class="navbar-nav-general">

                <a class="nav-link" href="<?= htmlspecialchars(urlApp('index.php'), ENT_QUOTES, 'UTF-8') ?>">
                    Inicio
                </a>

                <?php if ($estaLogueado && $tipoUsuario === "usuario") { ?>

                    <a class="nav-link" href="<?= htmlspecialchars(urlApp('usuario/novedades.php'), ENT_QUOTES, 'UTF-8') ?>">
                        Novedades
                    </a>

                    <a class="nav-link" href="<?= htmlspecialchars(urlApp('usuario/historialCompras.php'), ENT_QUOTES, 'UTF-8') ?>">
                        Historial de compras
                    </a>

                    <a class="nav-link" href="<?= htmlspecialchars(urlApp('usuario/reservas/gestionReservas.php'), ENT_QUOTES, 'UTF-8') ?>">
                        Mis reservas
                    </a>

                    <a class="nav-link" href="<?= htmlspecialchars(urlApp('index.php#buscador'), ENT_QUOTES, 'UTF-8') ?>">
                        <i class="bi bi-search"></i>
                        Buscar vuelos
                    </a>

                <?php } elseif ($estaLogueado && $tipoUsuario === "administrador") { ?>

                    <a class="nav-link" href="<?= htmlspecialchars(urlApp('admin/admin.php'), ENT_QUOTES, 'UTF-8') ?>">
                        <i class="bi bi-calendar3"></i>
                        Ir al panel de gestión
                    </a>

                <?php } elseif ($estaLogueado && $tipoUsuario === "ceo") { ?>

                    <a class="nav-link" href="<?= htmlspecialchars(urlApp('ceo/ceo.php'), ENT_QUOTES, 'UTF-8') ?>">
                        <i class="bi bi-calendar3"></i>
                        Ir al panel de gestión
                    </a>

                <?php } ?>

            </div>

            <?php if ($estaLogueado) { ?>

                <!-- OPCIONES DE CUENTA -->
                <div class="opciones-cuenta dropdown">

                    <button
                        class="info-usuario dropdown-toggle"
                        type="button"
                        data-bs-toggle="dropdown"
                        aria-expanded="false"
                    >
                        <i class="bi bi-person-circle"></i>
                        Hola, <?= htmlspecialchars($primerNombre) ?>!
                    </button>

                    <ul class="menu-usuarios dropdown-menu dropdown-menu-end">

                        <?php if ($tipoUsuario === "administrador") { ?>

                            <li>
                                <a class="dropdown-item" href="<?= htmlspecialchars(urlApp('admin/perfilAdmin.php'), ENT_QUOTES, 'UTF-8') ?>">
                                    <i class="bi bi-person"></i>
                                    Mi perfil
                                </a>
                            </li>

                        <?php } elseif ($tipoUsuario === "ceo") { ?>

                            <li>
                                <a class="dropdown-item" href="<?= htmlspecialchars(urlApp('ceo/perfilCeo.php'), ENT_QUOTES, 'UTF-8') ?>">
                                    <i class="bi bi-person"></i>
                                    Mi perfil
                                </a>
                            </li>

                        <?php } elseif ($tipoUsuario === "usuario") { ?>

                            <li>
                                <a class="dropdown-item" href="<?= htmlspecialchars(urlApp('usuario/perfil.php'), ENT_QUOTES, 'UTF-8') ?>">
                                    <i class="bi bi-person"></i>
                                    Mi perfil
                                </a>
                            </li>

                        <?php } ?>

                        <li>
                            <hr class="dropdown-divider">
                        </li>

                        <li>
                            <a class="dropdown-item" href="<?= htmlspecialchars(urlApp('php/cerrarSesion.php'), ENT_QUOTES, 'UTF-8') ?>">
                                <i class="bi bi-box-arrow-right"></i>
                                Cerrar sesión
                            </a>
                        </li>

                    </ul>

                </div>

                <!-- OPCIONES PARA CELULAR -->
                <div class="usuario-mobile">

                    <?php if ($tipoUsuario === "usuario") { ?>

                        <a class="nav-link" href="<?= htmlspecialchars(urlApp('usuario/perfil.php'), ENT_QUOTES, 'UTF-8') ?>">
                            <i class="bi bi-person"></i>
                            Mi perfil
                        </a>

                    <?php } elseif ($tipoUsuario === "administrador") { ?>

                        <a class="nav-link" href="<?= htmlspecialchars(urlApp('admin/perfilAdmin.php'), ENT_QUOTES, 'UTF-8') ?>">
                            <i class="bi bi-person"></i>
                            Mi perfil
                        </a>

                    <?php } elseif ($tipoUsuario === "ceo") { ?>

                        <a class="nav-link" href="<?= htmlspecialchars(urlApp('ceo/perfilCeo.php'), ENT_QUOTES, 'UTF-8') ?>">
                            <i class="bi bi-person"></i>
                            Mi perfil
                        </a>

                    <?php } ?>

                    <a class="nav-link" href="<?= htmlspecialchars(urlApp('php/cerrarSesion.php'), ENT_QUOTES, 'UTF-8') ?>">
                        <i class="bi bi-box-arrow-right"></i>
                        Cerrar sesión
                    </a>

                </div>

            <?php } else { ?>

                <!-- USUARIO NO REGISTRADO -->
                <div class="opciones-acceso">

                    <a class="nav-link btn-iniciosesion" href="<?= htmlspecialchars(urlApp('inicioSesion.php'), ENT_QUOTES, 'UTF-8') ?>">
                        <i class="bi bi-person-check"></i>
                        Iniciar sesión
                    </a>

                    <a class="nav-link btn-registro" href="<?= htmlspecialchars(urlApp('registro.php'), ENT_QUOTES, 'UTF-8') ?>">
                        <i class="bi bi-person-plus"></i>
                        Registrate
                    </a>

                </div>

            <?php } ?>

        </div>
    </div>
</nav>