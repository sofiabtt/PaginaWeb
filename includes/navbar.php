<?php

if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

?>

<nav class="navbar navbar-expand-lg bg-white shadow-sm">

    <div class="container-fluid px-4">

        <a
            class="navbar-brand d-flex align-items-center"
            href="home.php"
        >

            <img
                src="/PaginaWeb/imagenes/logo.png"
                alt="Logo de Nuvia"
                width="60"
                height="60"
            >

            <span class="fw-bold fs-4 ms-2">
                Nuvia
            </span>

        </a>


        <button
            class="navbar-toggler"
            type="button"
            data-bs-toggle="collapse"
            data-bs-target="#navbarNuvia"
            aria-controls="navbarNuvia"
            aria-expanded="false"
            aria-label="Abrir menú de navegación"
        >

            <span class="navbar-toggler-icon"></span>

        </button>


        <div
            class="collapse navbar-collapse"
            id="navbarNuvia"
        >

            <ul class="navbar-nav ms-auto align-items-lg-center menu-principal">

                <li class="nav-item">

                    <a
                        class="nav-menu"
                        href="home.php"
                    >
                        Inicio
                    </a>

                </li>


                <li class="nav-item">

                    <a
                        class="nav-menu"
                        href="#"
                    >
                        Contacto
                    </a>

                </li>


                <li class="nav-item">

                    <a
                        class="nav-menu"
                        href="#"
                    >
                        Destinos
                    </a>

                </li>


                <li class="nav-item">

                    <a
                        class="nav-menu"
                        href="#"
                    >
                        Novedades
                    </a>

                </li>


                <li class="nav-item">

                    <a
                        class="nav-menu"
                        href="#"
                    >
                        Ofertas
                    </a>

                </li>


                <?php if (
                    isset($_SESSION["gmailIngreso"]) &&
                    isset($_SESSION["tipoUsuario"]) &&
                    $_SESSION["tipoUsuario"] == "usuario"
                ): ?>


                    <li class="nav-item">

                        <a
                            class="nav-menu"
                            href="#"
                        >
                            Mis reservas
                        </a>

                    </li>


                    <li class="nav-item">

                        <a
                            class="nav-menu"
                            href="#"
                        >

                            <?php

                            echo htmlspecialchars(
                                $_SESSION["nombreUsuario"] ?? "Mi perfil"
                            );

                            ?>

                        </a>

                    </li>


                    <li class="nav-item">

                        <a
                            class="nav-menu"
                            href="php/cerrarSesion.php"
                        >
                            Cerrar sesión
                        </a>

                    </li>


                <?php else: ?>


                    <li class="nav-item">

                        <a
                            class="btn-iniciosesion"
                            href="inicioSesion.php"
                        >
                            Iniciar sesión
                        </a>

                    </li>


                    <li class="nav-item">

                        <a
                            class="btn-registro"
                            href="registro.html"
                        >
                            Registrate
                        </a>

                    </li>


                <?php endif; ?>

            </ul>

        </div>

    </div>

</nav>