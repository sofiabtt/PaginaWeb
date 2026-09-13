<?php

session_start();


// VERIFICAR QUE SEA USUARIO

if (
    !isset($_SESSION["tipoUsuario"]) ||
    $_SESSION["tipoUsuario"] != "usuario"
) {

    header("Location: ../inicioSesion.php");
    exit();

}

?>


<!DOCTYPE html>

<html lang="es">


<head>

    <meta charset="UTF-8">

    <meta
        name="viewport"
        content="width=device-width, initial-scale=1.0"
    >

    <title>Nuvia - Usuario</title>


    <!-- Favicon -->

    <link
        rel="icon"
        href="../imagenes/logo.png"
        type="image/png"
    >


    <!-- Bootstrap -->

    <link
        rel="stylesheet"
        href="../css/bootstrap.min.css"
    >


    <!-- CSS HOME -->

    <link
        rel="stylesheet"
        href="../css/estiloshome.css?v=2"
    >
    <link
    rel="stylesheet"
    href="../css/estilos-admin.css"
    >

    <!-- FOOTER -->

    <link
        rel="stylesheet"
        href="../css/footer.css"
    >


    <!-- ICONOS -->

    <link
        rel="stylesheet"
        href="../css/bootstrap-icons.css"
    >


</head>


<body>


<section class="hero">


    <!-- =========================
         NAVBAR
    ========================== -->

    <nav class="navbar navbar-expand-lg">


        <a
            class="navbar-brand"
            href="usuario.php"
        >

            <img
                src="../imagenes/logo.png"
                alt="logo"
                width="60"
                height="60"
            >

            <span class="fw-bold fs-4">

                Nuvia

            </span>

        </a>



        <!-- BOTÓN CELULAR -->

        <button
            class="navbar-toggler d-lg-none btn-menu"
        >

            ☰

        </button>



        <!-- MENÚ -->

        <div
            class="menu-principal ms-auto d-none d-lg-flex align-items-center"
        >


            <a
                href="usuario.php"
                class="nav-menu"
            >

                Inicio

            </a>


            <a
                href="reservas/gestionReservas.php"
                class="nav-menu"
            >

                Mis reservas

            </a>


            <a
                href="../destinos.html"
                class="nav-menu"
            >

                Destinos

            </a>


            <a
                href="../novedades.html"
                class="nav-menu"
            >

                Novedades

            </a>


            <a
                href="../ofertas.html"
                class="nav-menu"
            >

                Ofertas

            </a>



            <!-- PERFIL -->

            <div class="dropdown ms-4">

    <button
        class="usuario-admin dropdown-toggle"
        type="button"
        data-bs-toggle="dropdown"
        aria-expanded="false"
    >

        <i class="bi bi-person-circle"></i>

        <?php
        echo htmlspecialchars(
            $_SESSION["nombreUsuario"]
        );
        ?>

    </button>


    <ul class="dropdown-menu dropdown-menu-end menu-admin">

        <li>

            <a
                class="dropdown-item"
                href="perfil.php"
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
                href="../php/cerrarSesion.php"
            >

                <i class="bi bi-box-arrow-right"></i>

                Cerrar sesión

            </a>

        </li>

    </ul>

</div>


    </nav>



    <!-- =========================
         BUSCADOR
    ========================== -->

    <div class="contenedor-buscador">


        <div class="tipo-viaje">


            <p class="titulo-buscador">

                Encontrá el

                <strong>
                    vuelo ideal
                </strong>

                para tu próximo viaje

            </p>


            <!-- IDA Y VUELTA -->

            <input
                type="radio"
                name="viaje"
                checked
                onclick="mostrarVuelta()"
            >


            <label>

                Ida y vuelta

            </label>



            <!-- SOLO IDA -->

            <input
                type="radio"
                name="viaje"
                onclick="ocultarVuelta()"
            >


            <label>

                Solo ida

            </label>


        </div>



        <!-- DATOS DEL BUSCADOR -->

        <div id="buscador-ing-datos">


            <form
                action="vuelos/buscarVuelos.php"
                method="GET"
            >


                <div class="row g-0">



                    <!-- ORIGEN -->

                    <div class="col-md-3">


                        <input
                            type="text"
                            class="form-control"
                            name="origen"
                            placeholder="Desde"
                            required
                        >


                    </div>



                    <!-- DESTINO -->

                    <div class="col-md-3">


                        <input
                            type="text"
                            class="form-control"
                            name="destino"
                            placeholder="Hacia"
                            required
                        >


                    </div>



                    <!-- FECHA IDA -->

                    <div
                        class="col-md-2"
                        id="fecha-ida"
                    >


                        <input
                            type="date"
                            class="form-control"
                            name="fechaIda"
                            required
                        >


                    </div>



                    <!-- FECHA VUELTA -->

                    <div
                        class="col-md-2"
                        id="fecha-vuelta"
                    >


                        <input
                            type="date"
                            class="form-control"
                            name="fechaVuelta"
                        >


                    </div>



                    <!-- BOTÓN BUSCAR -->

                    <div class="col-md-2">


                        <button
                            type="submit"
                            class="buscar"
                        >

                            →

                        </button>


                    </div>


                </div>


            </form>


        </div>


    </div>




<!-- =========================
     JS BUSCADOR
========================== -->

<script>


function ocultarVuelta() {

    document.getElementById(
        "fecha-vuelta"
    ).style.display = "none";


    document.getElementById(
        "fecha-ida"
    ).className = "col-md-4";

}



function mostrarVuelta() {

    document.getElementById(
        "fecha-vuelta"
    ).style.display = "block";


    document.getElementById(
        "fecha-ida"
    ).className = "col-md-2";

}


</script>


<script src="../js/bootstrap.bundle.min.js"></script>


</body>

</html>