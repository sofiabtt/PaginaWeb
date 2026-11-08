
<?php require_once __DIR__ . "/php/conexionBD.php"; ?>

<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <title>Acerca de nosotros | Nuvia</title>

    <link rel="icon" type="image/png" href="<?= htmlspecialchars(urlApp('imagenes/logo.png'), ENT_QUOTES, 'UTF-8') ?>">

    <link rel="stylesheet" href="<?= htmlspecialchars(urlApp('css/bootstrap.min.css'), ENT_QUOTES, 'UTF-8') ?>">
    <link rel="stylesheet" href="<?= htmlspecialchars(urlApp('css/bootstrap-icons.css'), ENT_QUOTES, 'UTF-8') ?>">
    <link rel="stylesheet" href="<?= htmlspecialchars(urlApp('css/estilos.css'), ENT_QUOTES, 'UTF-8') ?>?v=20261108">
    
    <link rel="stylesheet" href="<?= htmlspecialchars(urlApp('css/footer.css'), ENT_QUOTES, 'UTF-8') ?>">
    <link rel="stylesheet" href="<?= htmlspecialchars(urlApp('css/fuentes.css'), ENT_QUOTES, 'UTF-8') ?>">
</head>

<body class="nuvia-nosotros nuvia-navbar">

    <?php include __DIR__ . "/includes/navbar.php"; ?>

    <main class="contenedor-nosotros">

        <!-- Presentación -->
        <section class="presentacion-nosotros">
            <span class="subtitulo-nosotros">CONOCÉ NUVIA</span>

            <h1>Acerca de nosotros</h1>

            <p>
                Somos Nuvia, una plataforma pensada para hacer que
                la búsqueda y reserva de vuelos sea una experiencia
                sencilla, organizada y accesible.
            </p>
        </section>

        <!-- Quiénes somos -->
        <section class="seccion-nosotros">
            <div class="contenido-seccion">
                <div class="texto-seccion">
                    <h2>¿Quiénes somos?</h2>

                    <p>
                        Nuvia es una plataforma web orientada a la
                        búsqueda y reserva de vuelos. Nuestro objetivo
                        es ofrecer un espacio donde los usuarios puedan
                        consultar distintas opciones de viaje y acceder
                        fácilmente a la información que necesitan.
                    </p>

                    <p>
                        A través de una interfaz clara y funcionalidades
                        organizadas, buscamos simplificar la experiencia
                        de quienes desean planificar sus próximos viajes.
                    </p>
                </div>

                <div class="imagen-seccion">
                    <img
                        src="<?= htmlspecialchars(urlApp('imagenes/logo.png'), ENT_QUOTES, 'UTF-8') ?>"
                        alt="Logotipo de Nuvia">
                </div>
            </div>
        </section>

        <!-- Objetivo -->
        <section class="seccion-objetivo">
            <h2>Nuestro objetivo</h2>

            <p>
                Facilitar el acceso a la información sobre vuelos y
                brindar herramientas que permitan a los usuarios
                gestionar sus consultas y reservas desde una misma
                plataforma.
            </p>
        </section>

        <!-- Servicios --> 
<section class="seccion-servicios">

    <div class="encabezado-servicios">
        <h2>¿Qué ofrecemos?</h2>

        <p>
            Contamos con distintas funcionalidades diseñadas
            para acompañar a los usuarios durante su experiencia
            en Nuvia.
        </p>
    </div>

    <div class="carrusel-servicios">

        <button class="flecha-servicio flecha-anterior-servicio" type="button" aria-label="Ver servicio anterior">
            &#10094;
        </button>

        <div class="servicios-grid" id="contenedorServicios">

            <article class="tarjeta-servicio">
                <div class="icono-servicio">
                    <i class="bi bi-search"></i>
                </div>
                <h3>Búsqueda de vuelos</h3>
                <p>
                    Consultá vuelos indicando el origen, destino,
                    fechas de viaje y cantidad de pasajeros.
                </p>
            </article>

            <article class="tarjeta-servicio">
                <div class="icono-servicio">
                    <i class="bi bi-ticket-perforated"></i>
                </div>
                <h3>Reservas</h3>
                <p>
                    Accedé a las funcionalidades de reserva y
                    consultá la información correspondiente
                    a tus operaciones.
                </p>
            </article>

            <article class="tarjeta-servicio">
                <div class="icono-servicio">
                    <i class="bi bi-tag"></i>
                </div>
                <h3>Promociones</h3>
                <p>
                    Explorá las promociones publicadas y conocé
                    las ofertas disponibles en la plataforma.
                </p>
            </article>

            <article class="tarjeta-servicio">
                <div class="icono-servicio">
                    <i class="bi bi-megaphone"></i>
                </div>
                <h3>Novedades</h3>
                <p>
                    Mantenete informado mediante las publicaciones
                    y novedades compartidas en Nuvia.
                </p>
            </article>

            <article class="tarjeta-servicio">
                <div class="icono-servicio">
                    <i class="bi bi-person-circle"></i>
                </div>
                <h3>Cuenta personal</h3>
                <p>
                    Registrate para acceder a las funcionalidades
                    destinadas a usuarios y gestionar tu perfil.
                </p>
            </article>

            <article class="tarjeta-servicio">
                <div class="icono-servicio">
                    <i class="bi bi-chat-dots"></i>
                </div>
                <h3>Contacto y consultas</h3>
                <p>
                    Utilizá nuestro formulario de contacto para
                    realizar consultas de distintos tipos.
                </p>
            </article>

        </div>

        <button class="flecha-servicio flecha-siguiente-servicio" type="button" aria-label="Ver servicio siguiente">
            &#10095;
        </button>

    </div>

    <div class="indicadores-servicios" aria-label="Seleccionar servicio">

        <button class="indicador-servicio activo" type="button" aria-label="Servicio 1" aria-current="true"></button>

        <button class="indicador-servicio" type="button" aria-label="Servicio 2"></button>

        <button class="indicador-servicio" type="button" aria-label="Servicio 3"></button>

        <button class="indicador-servicio" type="button" aria-label="Servicio 4"></button>

        <button class="indicador-servicio" type="button" aria-label="Servicio 5"></button>

        <button class="indicador-servicio" type="button" aria-label="Servicio 6"></button>

    </div>

</section>

        <!-- Cómo funciona -->
        <section class="seccion-funcionamiento">

            <h2>¿Cómo funciona?</h2>

            <p class="descripcion-funcionamiento">
                Utilizar Nuvia es sencillo. Podés comenzar a explorar
                las opciones de viaje siguiendo estos pasos:
            </p>

            <div class="pasos-grid">

                <article class="paso">
                    <span class="numero-paso">01</span>
                    <h3>Buscá tu vuelo</h3>
                    <p>
                        Ingresá los datos de tu viaje en el buscador
                        disponible en la página principal.
                    </p>
                </article>

                <article class="paso">
                    <span class="numero-paso">02</span>
                    <h3>Consultá las opciones</h3>
                    <p>
                        Explorá los resultados obtenidos y utilizá
                        los filtros para encontrar alternativas.
                    </p>
                </article>

                <article class="paso">
                    <span class="numero-paso">03</span>
                    <h3>Gestioná tu reserva</h3>
                    <p>
                        Ingresá con tu cuenta para acceder a las
                        funcionalidades de reserva disponibles.
                    </p>
                </article>

            </div>
        </section>

        <!-- Cierre -->
        <section class="cierre-nosotros">

            <h2>Tu próximo viaje comienza acá</h2>

            <p>
                Explorá Nuvia y descubrí las herramientas que tenemos
                para ayudarte a planificar tu próximo destino.
            </p>

            <a href="<?= htmlspecialchars(urlApp('index.php'), ENT_QUOTES, 'UTF-8') ?>"
                class="boton-explorar">
                Explorar Nuvia
            </a>

        </section>

    </main>

    <?php include __DIR__ . "/includes/footer.php"; ?>

    <script src="<?= htmlspecialchars(urlApp('js/bootstrap.bundle.min.js'), ENT_QUOTES, 'UTF-8') ?>"></script>
    
    <script>
        document.addEventListener("DOMContentLoaded", function () {

            const contenedor = document.getElementById("contenedorServicios");

            const tarjetas = document.querySelectorAll(".tarjeta-servicio");

            const indicadores = document.querySelectorAll(".indicador-servicio");

            const anterior = document.querySelector(".flecha-anterior-servicio");

            const siguiente = document.querySelector(".flecha-siguiente-servicio");

            if (!contenedor || !tarjetas.length) return;

            let indiceActual = 0;

            function actualizarIndicador() {

                indicadores.forEach((indicador, indice) => {

                    indicador.classList.toggle("activo", indice === indiceActual);

                    if (indice === indiceActual) {
                        indicador.setAttribute("aria-current", "true");
                    } else {
                        indicador.removeAttribute("aria-current");
                    }

                });

            }

            function irATarjeta(indice) {

                indiceActual = Math.max(0, Math.min(indice, tarjetas.length - 1));

                contenedor.scrollTo({
                    left: tarjetas[indiceActual].offsetLeft,
                    behavior: "smooth"
                });

                actualizarIndicador();
            }

            siguiente.addEventListener("click", function () {
                irATarjeta(indiceActual + 1);
            });

            anterior.addEventListener("click", function () {
                irATarjeta(indiceActual - 1);
            });

            indicadores.forEach((indicador, indice) => {

                indicador.addEventListener("click", function () {
                    irATarjeta(indice);
                });

            });

            contenedor.addEventListener("scroll", function () {

                const anchoTarjeta = contenedor.clientWidth;

                if (anchoTarjeta === 0) return;

                indiceActual = Math.round(contenedor.scrollLeft / anchoTarjeta);

                actualizarIndicador();

            });

        });
    </script>

</body>
</html>
