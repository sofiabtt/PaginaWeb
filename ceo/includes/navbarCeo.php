<?php

if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

if (isset($_SESSION["tipoUsuario"])) {
    $primerNombre = explode(" ", $_SESSION["nombreUsuario"])[0];
} else {
    $primerNombre = null;
}
?>

<nav class="navbar navbar-expand-xl">
    <div class="container-fluid">

        <a class="navbar-brand" href="<?= htmlspecialchars(urlApp('ceo/ceo.php'), ENT_QUOTES, 'UTF-8') ?>">
            <img src="<?= htmlspecialchars(urlApp('imagenes/logo.png'), ENT_QUOTES, 'UTF-8') ?>" alt="Logo Nuvia">

            <h1>Nuvia</h1>
        </a>

        <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNavAltMarkup" aria-controls="navbarNavAltMarkup" aria-expanded="false" aria-label="Toggle navigation">
            <span class="navbar-toggler-icon"></span>
        </button>

        <div class="collapse navbar-collapse" id="navbarNavAltMarkup">

            <div class="navbar-nav-general">

                <div class="usuario-mobile">
                    <span class="saludo">
                        <i class="bi bi-person-circle"></i>
                        Hola, <?php echo htmlspecialchars($primerNombre); ?>!
                    </span>
                </div>

                <a class="nav-link" href="<?= htmlspecialchars(urlApp('index.php'), ENT_QUOTES, 'UTF-8') ?>">
                    Inicio
                </a>

                <a class="nav-link" href="<?= htmlspecialchars(urlApp('ceo/ceo.php'), ENT_QUOTES, 'UTF-8') ?>">
                    Panel
                </a>

                <a class="nav-link" href="<?= htmlspecialchars(urlApp('ceo/vuelos/gestionVuelos.php'), ENT_QUOTES, 'UTF-8') ?>">
                    Vuelos
                </a>

                <a class="nav-link" href="<?= htmlspecialchars(urlApp('ceo/promociones/gestionPromocion.php'), ENT_QUOTES, 'UTF-8') ?>">
                    Promociones
                </a>

                <a class="nav-link" href="<?= htmlspecialchars(urlApp('ceo/reportes/reportes.php'), ENT_QUOTES, 'UTF-8') ?>">
                    Reportes
                </a>


                <div class="dropdown">

                    <div class="opciones-cuenta dropdown">

                        <button class="info-usuario dropdown-toggle" type="button" data-bs-toggle="dropdown"
                            aria-expanded="false">
                            <i class="bi bi-person-circle"></i>
                            Hola, <?php echo htmlspecialchars($primerNombre); ?>!
                        </button>
                        
                        <ul class="menu-usuarios dropdown-menu dropdown-menu-end">

                            <li>
                                <a class="dropdown-item" href="<?= htmlspecialchars(urlApp('ceo/perfilCeo.php'), ENT_QUOTES, 'UTF-8') ?>">
                                    <i class="bi bi-person"></i>
                                    Mi perfil
                                </a>
                            </li>

                            <li><hr class="dropdown-divider"></li>

                            <li>
                                <a class="dropdown-item" href="<?= htmlspecialchars(urlApp('php/cerrarSesion.php'), ENT_QUOTES, 'UTF-8') ?>">
                                    <i class="bi bi-box-arrow-right"></i>
                                    Cerrar sesión
                                </a>
                            </li>

                        </ul>
                    </div>

                    <div class="usuario-mobile">
                        <a class="nav-link" href="<?= htmlspecialchars(urlApp('ceo/perfilCeo.php'), ENT_QUOTES, 'UTF-8') ?>">
                            <i class="bi bi-person"></i>
                            Mi perfil
                        </a>

                        <a class="nav-link" href="<?= htmlspecialchars(urlApp('php/cerrarSesion.php'), ENT_QUOTES, 'UTF-8') ?>">
                            <i class="bi bi-box-arrow-right"></i>
                            Cerrar sesión
                        </a>
                    </div>

                </div>

            </div>

        </div>

    </div>

</nav>

