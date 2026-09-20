<?php
$estaLogueado = isset($_SESSION["codUsuario"]);

if ($estaLogueado) {
    $tipoUsuario = $_SESSION["tipoUsuario"];
    $primerNombre = explode(" ", $_SESSION["nombreUsuario"])[0];
} else {
    $tipoUsuario = null;
    $primerNombre = null;
}
?>

<nav class="navbar navbar-expand-xl">
    <div class="container-fluid">
        <a class="navbar-brand" href="#">
            <img src="/PaginaWeb/imagenes/logo.png" alt="Logo Nuvia">

            <h1>Nuvia</h1>
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
                <a class="nav-link" href="/PaginaWeb/home.php">Inicio</a>
                <a class="nav-link" href="/PaginaWeb/home.php#destinos">Destinos</a>
                <a class="nav-link" href="#">Ofertas</a>

                <?php if ($estaLogueado && $tipoUsuario === "usuario") { ?>

                    <a class="nav-link" href="/PaginaWeb/usuario/novedades.php">
                        Novedades
                    </a>

                    <a class="nav-link" href="/PaginaWeb/usuario/historialCompras.php">
                        Historial de compras
                    </a>

                    <a class="nav-link" href="/PaginaWeb/home.php#buscador">
                        <i class="bi bi-search"></i>
                        Buscar vuelos
                    </a>

                    <a class="nav-link" href="/PaginaWeb/usuario/reservas/gestionReservas.php">
                        <i class="bi bi-ticket-perforated"></i>
                        Mis reservas
                    </a>

                <?php } elseif ($estaLogueado && $tipoUsuario === "administrador") { ?>

                    <a class="nav-link" href="/PaginaWeb/usuario/novedades.php">
                        Novedades
                    </a>

                    <a class="nav-link" href="/PaginaWeb/admin/admin.php">
                        <i class="bi bi-calendar3"></i>
                        Ir al panel de gestión
                    </a>

                <?php } elseif ($estaLogueado && $tipoUsuario === "ceo") { ?>

                    <a class="nav-link" href="/PaginaWeb/usuario/novedades.php">
                        Novedades
                    </a>

                    <a class="nav-link" href="/PaginaWeb/ceo/ceo.php">
                        <i class="bi bi-calendar3"></i>
                        Ir al panel de gestión
                    </a>
                <?php } ?>
            </div>

            <?php if ($estaLogueado) { ?>

                <div class="opciones-cuenta dropdown">
                    <button class="info-usuario dropdown-toggle" type="button" data-bs-toggle="dropdown"
                        aria-expanded="false">
                        <i class="bi bi-person-circle"></i>
                        Hola, <?php echo htmlspecialchars($primerNombre); ?>!
                    </button>
                    <ul class="menu-usuarios dropdown-menu dropdown-menu-end">
                        <li>
                            <a class="dropdown-item" href="/PaginaWeb/usuario/perfil.php">
                                <i class="bi bi-person"></i>
                                Mi perfil
                            </a>
                        </li>

                        <li><hr class="dropdown-divider"></li>

                        <li>
                            <a class="dropdown-item" href="/PaginaWeb/php/cerrarSesion.php">
                                <i class="bi bi-box-arrow-right"></i>
                                Cerrar sesión
                            </a>
                        </li>
                    </ul>
                </div>

                <div class="usuario-mobile">
                    <a class="nav-link" href="/PaginaWeb/usuario/perfil.php">
                        <i class="bi bi-person"></i>
                        Mi perfil
                    </a>

                    <a class="nav-link" href="/PaginaWeb/php/cerrarSesion.php">
                        <i class="bi bi-box-arrow-right"></i>
                        Cerrar sesión
                    </a>
                </div>

            <?php } else { ?>

                <div class="opciones-acceso">
                    <a class="nav-link btn-iniciosesion" href="inicioSesion.php">
                        <i class="bi bi-person-check"></i>
                        Iniciar sesión
                    </a>
    
                    <a class="nav-link btn-registro" href="registro.php">
                        <i class="bi bi-person-plus"></i>
                        Registrate
                    </a>

                </div>

                <?php } ?>
        </div>
    </div>
</nav>