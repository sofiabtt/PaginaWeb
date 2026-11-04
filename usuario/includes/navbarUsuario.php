<nav class="navbar navbar-expand-lg">
    
    <a
        class="navbar-brand"
        href="/index.php"
    >
        <img
            src="/imagenes/logo.png"
            alt="Logo de Nuvia"
            width="60"
            height="60"
        >

        <span class="fw-bold fs-4">
            Nuvia
        </span>
    </a>

    <div class="menu-admin-navbar">
        <a class="nav-link" href="/usuario/usuario.php">Buscar vuelos</a>
        <a class="nav-link" href="/usuario/reservas/gestionReservas.php">Mis reservas</a>
        <a class="nav-link" href="/usuario/historialCompras.php">Historial</a>
        <a class="nav-link" href="/usuario/novedades.php">Novedades</a>

        <div class="dropdown">
            <button class="usuario-admin dropdown-toggle" type="button" data-bs-toggle="dropdown">
                <i class="bi bi-person-circle"></i>
                <?php echo htmlspecialchars($_SESSION["nombreUsuario"] ?? "Usuario"); ?>
            </button>
            <ul class="dropdown-menu dropdown-menu-end menu-admin">
                <li>
                    <a class="dropdown-item" href="/usuario/perfil.php">
                        <i class="bi bi-person"></i> Mi perfil
                    </a>
                </li>
                <li><hr class="dropdown-divider"></li>
                <li>
                    <a class="dropdown-item" href="/php/cerrarSesion.php">
                        <i class="bi bi-box-arrow-right"></i> Cerrar sesión
                    </a>
                </li>
            </ul>
        </div>
    </div>
</nav>