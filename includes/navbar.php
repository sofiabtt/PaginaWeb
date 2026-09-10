<?php
if (isset($_SESSION["tipoUsuario"])) {
    $estaLogueado = true;
    $tipoUsuario = $_SESSION["tipoUsuario"];
    $primerNombre = explode(" ", $_SESSION["nombreUsuario"])[0];
} else {
    $estaLogueado = false;
    $tipoUsuario = null;
    $primerNombre = null;
}
?>

<nav class="navbar navbar-expand-lg">
    <div class="container-fluid">
        <a class="navbar-brand" href="home.php">
            <img src="imagenes/logo.png" alt="Logo Nuvia">
        </a>

        <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNavAltMarkup"
            aria-controls="navbarNavAltMarkup" aria-expanded="false" aria-label="Toggle navigation">
            <span class="navbar-toggler-icon"></span>
        </button>

        <div class="collapse navbar-collapse" id="navbarNavAltMarkup">

            <?php if ($estaLogueado) { ?>
                <div class="usuario-mobile">
                    <span class="saludo">
                        <i class="bi bi-person-circle"></i>
                        Hola, <?php echo htmlspecialchars($primerNombre); ?>!
                    </span>
                </div>
            <?php } ?>

            <div class="navbar-nav-general">
                <a class="nav-link" href="home.php">Inicio</a>
                <a class="nav-link" href="#">Contacto</a>
                <a class="nav-link" href="#">Destinos</a>
                <a class="nav-link" href="#">Novedades</a>
                <a class="nav-link" href="#">Ofertas</a>

                <?php if ($estaLogueado && $tipoUsuario === "usuario") { ?>
                    <a class="nav-link" href="#">Buscar vuelos</a>
                    <a class="nav-link" href="#">Mis reservas</a>
                <?php } elseif ($estaLogueado && $tipoUsuario === "administrador") { ?>
                    <a class="nav-link" href="admin/admin.php">
                        <i class="bi bi-calendar3"></i>
                        Ir al panel de gestión
                    </a>
                <?php } elseif ($estaLogueado && $tipoUsuario === "ceo") { ?>
                    <a class="nav-link" href="ceo/ceo.php">
                        <i class="bi bi-calendar3"></i>
                        Ir al panel de gestión
                    </a>
                <?php } ?>
            </div>

            <div class="navbar-nav align-items-lg-center">

                <?php if ($estaLogueado) { ?>

                    <div class="opciones-cuenta dropdown">
                        <button class="info-usuario dropdown-toggle" type="button" data-bs-toggle="dropdown"
                            aria-expanded="false">
                            <i class="bi bi-person-circle"></i>
                            Hola, <?php echo htmlspecialchars($primerNombre); ?>!
                        </button>
                        <ul class="menu-usuarios dropdown-menu dropdown-menu-end">
                            <li>
                                <a class="dropdown-item" href="#">
                                    <i class="bi bi-person"></i>
                                    Mi perfil
                                </a>
                            </li>
                            <li>
                                <hr class="dropdown-divider">
                            </li>
                            <li>
                                <a class="dropdown-item" href="php/cerrarSesion.php">
                                    <i class="bi bi-box-arrow-right"></i>
                                    Cerrar sesión
                                </a>
                            </li>
                        </ul>
                    </div>

                    <div class="usuario-mobile">
                        <a class="nav-link" href="#">
                            <i class="bi bi-person"></i>
                            Mi perfil
                        </a>
                        <a class="nav-link" href="php/cerrarSesion.php">
                            <i class="bi bi-box-arrow-right"></i>
                            Cerrar sesión
                        </a>
                    </div>

                <?php } else { ?>

                    <div class="opciones-cuenta dropdown">
                        <button class="info-usuario dropdown-toggle" type="button" data-bs-toggle="dropdown"
                            aria-expanded="false">
                            <i class="bi bi-person-circle"></i>
                        </button>
                        <ul class="menu-usuarios dropdown-menu dropdown-menu-end">
                            <li>
                                <a class="dropdown-item" href="inicioSesion.php">
                                    <i class="bi bi-person"></i>
                                    Iniciar sesión
                                </a>
                            </li>
                        </ul>
                    </div>

                    <div class="usuario-mobile">
                        <a class="nav-link" href="inicioSesion.php">
                            <i class="bi bi-person"></i>
                            Iniciar sesión
                        </a>
                    </div>

                <?php } ?>
            </div>
        </div>
    </div>
</nav>