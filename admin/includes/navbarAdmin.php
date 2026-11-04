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

        <a class="navbar-brand" href="/admin/admin.php">
            <img src="/imagenes/logo.png" alt="Logo Nuvia">
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

                <!-- INICIO -->
                <a class="nav-link" href="/index.php">
                    Inicio
                </a>

                <!-- PANEL ADMIN -->
                <a class="nav-link" href="/admin/admin.php">
                    Panel
                </a>

                <!-- AEROLÍNEAS -->
                <a class="nav-link" href="/admin/aerolineas/gestionAerolineas.php">
                    Aerolíneas
                </a>

                <!-- VUELOS -->
                <a class="nav-link" href="/admin/vuelos/gestionVuelos.php">
                    Vuelos
                </a>

                <!-- PROMOCIONES -->
                <a class="nav-link" href="/admin/promociones/gestionPromociones.php">
                    Promociones
                </a>

                <!-- NOVEDADES -->
                <a class="nav-link" href="/admin/novedades/gestionNovedades.php">
                    Novedades
                </a>

                <!-- REPORTES -->
                <a class="nav-link" href="/admin/reportes.php">
                    Reportes
                </a>

                <!-- USUARIOS (Enlace único y correcto) -->
                <a class="nav-link" href="/admin/reportes/usuarios.php">
                    Usuarios
                </a>

                <!-- CEOs -->
                <a class="nav-link" href="/admin/ceos/gestionCeos.php">
                    CEOs
                </a>

                <!-- PERFIL -->
                <div class="dropdown">

                    <div class="opciones-cuenta dropdown">

                        <button class="info-usuario dropdown-toggle" type="button" data-bs-toggle="dropdown"
                            aria-expanded="false">
                            <i class="bi bi-person-circle"></i>
                            Hola, <?php echo htmlspecialchars($primerNombre); ?>!
                        </button>
                        
                        <ul class="menu-usuarios dropdown-menu dropdown-menu-end">

                            <li>
                                <a class="dropdown-item" href="/admin/perfilAdmin.php">
                                    <i class="bi bi-person"></i>
                                    Mi perfil
                                </a>
                            </li>

                            <li><hr class="dropdown-divider"></li>

                            <li>
                                <a class="dropdown-item" href="/php/cerrarSesion.php">
                                    <i class="bi bi-box-arrow-right"></i>
                                    Cerrar sesión
                                </a>
                            </li>

                        </ul>

                        <div class="usuario-mobile">
                            <a class="nav-link" href="/admin/perfilAdmin.php">
                                <i class="bi bi-person"></i>
                                Mi perfil
                            </a>

                            <a class="nav-link" href="/php/cerrarSesion.php">
                                <i class="bi bi-box-arrow-right"></i>
                                Cerrar sesión
                            </a>
                        </div>

                    </div>

                </div>

            </div>

        </div>

    </div>

</nav>