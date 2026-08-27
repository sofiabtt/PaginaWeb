<nav class="navbar navbar-expand-lg">

    <a
        class="navbar-brand"
        href="/PaginaWeb/admin/admin.php"
    >

        <img
            src="/PaginaWeb/imagenes/logo.png"
            alt="Logo AeroFly"
            width="60"
            height="60"
        >

    </a>


    <div class="menu-admin-navbar">


        <!-- INICIO -->

        <a
            class="nav-link"
            href="/PaginaWeb/admin/admin.php"
        >
            Inicio
        </a>



        <!-- AEROLÍNEAS -->

        <a
            class="nav-link"
            href="/PaginaWeb/admin/aerolineas/gestionAerolineas.php"
        >
            Aerolíneas
        </a>



        <!-- PROMOCIONES -->

        <a
            class="nav-link"
            href="/PaginaWeb/admin/promociones/gestionPromociones.php"
        >
            Promociones
        </a>



        <!-- NOVEDADES -->

        <a
            class="nav-link"
            href="/PaginaWeb/admin/novedades/gestionNovedades.php"
        >
            Novedades
        </a>



        <!-- REPORTES -->

        <a
            class="nav-link"
            href="/PaginaWeb/admin/reportes.php"
        >
            Reportes
        </a>



        <!-- CEOs -->

        <a
            class="nav-link"
            href="/PaginaWeb/admin/ceos/gestionCeos.php"
        >
            CEOs
        </a>



        <!-- PERFIL -->

        <div class="dropdown">

            <button
                class="usuario-admin dropdown-toggle"
                type="button"
                data-bs-toggle="dropdown"
                aria-expanded="false"
            >

                <i class="bi bi-person-circle"></i>

                Administrador

            </button>


            <ul
                class="dropdown-menu dropdown-menu-end menu-admin"
            >

                <li>

                    <a
                        class="dropdown-item"
                        href="/PaginaWeb/admin/perfilAdmin.php"
                    >

                        <i class="bi bi-person"></i>

                        Ver perfil

                    </a>

                </li>


                <li>

                    <hr class="dropdown-divider">

                </li>


                <li>

                    <a
                        class="dropdown-item"
                        href="/PaginaWeb/home.php"
                    >

                        <i class="bi bi-box-arrow-right"></i>

                        Cerrar sesión

                    </a>

                </li>

            </ul>

        </div>


    </div>

</nav>