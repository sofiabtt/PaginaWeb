<?php require_once __DIR__ . "/php/conexionBD.php"; ?>

<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <title>Preguntas frecuentes | Nuvia</title>

    <link rel="icon" type="image/png" href="<?= htmlspecialchars(urlApp('imagenes/logo.png'), ENT_QUOTES, 'UTF-8') ?>">
    <link rel="stylesheet" href="<?= htmlspecialchars(urlApp('css/bootstrap.min.css'), ENT_QUOTES, 'UTF-8') ?>">
    <link rel="stylesheet" href="<?= htmlspecialchars(urlApp('css/estilos.css'), ENT_QUOTES, 'UTF-8') ?>?v=20261108">
</head>

<body class="nuvia-preguntas nuvia-navbar">

    <?php include __DIR__ . "/includes/navbar.php"; ?>

    <main class="contenedor-preguntas">

        <section class="encabezado-preguntas">
            <h1>Preguntas frecuentes</h1>
            <p>
                Encontrá respuestas a las dudas más habituales sobre Nuvia
                y conocé cómo utilizar nuestra plataforma.
            </p>
        </section>

        <section class="seccion-preguntas">

            <h2>Sobre Nuvia</h2>

            <div class="accordion" id="preguntasNuvia">

                <div class="accordion-item">
                    <h3 class="accordion-header">
                        <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#pregunta1" aria-expanded="false" aria-controls="pregunta1">
                            ¿Qué es Nuvia?
                        </button>
                    </h3>
                    <div id="pregunta1" class="accordion-collapse collapse" data-bs-parent="#preguntasNuvia">
                        <div class="accordion-body">
                            Nuvia es una plataforma web que permite consultar
                            vuelos, explorar destinos y realizar reservas
                            de manera sencilla.
                        </div>
                    </div>
                </div>

                <div class="accordion-item">
                    <h3 class="accordion-header">
                        <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#pregunta2" aria-expanded="false" aria-controls="pregunta2">
                            ¿Es necesario registrarse para utilizar Nuvia?
                        </button>
                    </h3>
                    <div id="pregunta2" class="accordion-collapse collapse" data-bs-parent="#preguntasNuvia">
                        <div class="accordion-body">
                            No es necesario contar con una cuenta para visitar
                            el sitio y consultar sus secciones públicas.
                            Para acceder a las funcionalidades destinadas a
                            usuarios registrados, es necesario crear una cuenta
                            e iniciar sesión.
                        </div>
                    </div>
                </div>

            </div>

            <h2>Vuelos y promociones</h2>

            <div class="accordion" id="preguntasVuelos">

                <div class="accordion-item">
                    <h3 class="accordion-header">
                        <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#pregunta3" aria-expanded="false" aria-controls="pregunta3">
                            ¿Cómo puedo buscar un vuelo?
                        </button>
                    </h3>
                    <div id="pregunta3" class="accordion-collapse collapse" data-bs-parent="#preguntasVuelos">
                        <div class="accordion-body">
                            Desde la página principal podés utilizar el buscador
                            de vuelos. Allí deberás indicar el origen, el destino,
                            la fecha del viaje de ida y vuelta, según corresponda. 
                            Una vez realizada la búsqueda, accederás a una página de 
                            resultados donde podrás consultar los vuelos encontrados 
                            y aplicar filtros según tus preferencias.
                        </div>
                    </div>
                </div>

                <div class="accordion-item">
                    <h3 class="accordion-header">
                        <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#pregunta4" aria-expanded="false" aria-controls="pregunta4">
                            ¿Dónde puedo consultar las promociones?
                        </button>
                    </h3>
                    <div id="pregunta4" class="accordion-collapse collapse" data-bs-parent="#preguntasVuelos">
                        <div class="accordion-body">
                            Podés ingresar a la sección de Promociones para
                            conocer las ofertas publicadas y consultar la
                            información correspondiente a cada una.
                        </div>
                    </div>
                </div>

                <div class="accordion-item">
                    <h3 class="accordion-header">
                        <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#pregunta5" aria-expanded="false" aria-controls="pregunta5">
                            ¿Cómo puedo conocer las novedades de Nuvia?
                        </button>
                    </h3>
                    <div id="pregunta5" class="accordion-collapse collapse" data-bs-parent="#preguntasVuelos">
                        <div class="accordion-body">
                            En la sección de Novedades podés consultar las
                            publicaciones e información relevante que se
                            comparte en la plataforma.
                        </div>
                    </div>
                </div>

            </div>

            <h2>Mi cuenta</h2>

            <div class="accordion" id="preguntasCuenta">

                <div class="accordion-item">
                    <h3 class="accordion-header">
                        <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#pregunta6" aria-expanded="false" aria-controls="pregunta6">
                            ¿Cómo puedo crear una cuenta?
                        </button>
                    </h3>
                    <div id="pregunta6" class="accordion-collapse collapse" data-bs-parent="#preguntasCuenta">
                        <div class="accordion-body">
                            Podés registrarte desde la opción de Registrarse,
                            completando el formulario con los datos solicitados.
                            Luego deberás realizar la verificación de tu
                            dirección de correo electrónico.
                        </div>
                    </div>
                </div>

                <div class="accordion-item">
                    <h3 class="accordion-header">
                        <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#pregunta7" aria-expanded="false" aria-controls="pregunta7">
                            ¿Qué hago si olvidé mi contraseña?
                        </button>
                    </h3>
                    <div id="pregunta7" class="accordion-collapse collapse" data-bs-parent="#preguntasCuenta">
                        <div class="accordion-body">
                            Si no recordás tu contraseña, podés consultar las
                            opciones disponibles en la sección de inicio de
                            sesión para recuperar el acceso a tu cuenta.
                        </div>
                    </div>
                </div>

                <div class="accordion-item">
                    <h3 class="accordion-header">
                        <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#pregunta8" aria-expanded="false" aria-controls="pregunta8">
                            ¿Cómo puedo modificar mis datos personales?
                        </button>
                    </h3>
                    <div id="pregunta8" class="accordion-collapse collapse" data-bs-parent="#preguntasCuenta">
                        <div class="accordion-body">
                            Si tenés una cuenta registrada, podés ingresar
                            a tu perfil para consultar y modificar los datos
                            personales que permite gestionar el sistema.
                        </div>
                    </div>
                </div>

            </div>

            <h2>Reservas</h2>

            <div class="accordion" id="preguntasReservas">

                <div class="accordion-item">
                    <h3 class="accordion-header">
                        <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#pregunta9" aria-expanded="false" aria-controls="pregunta9">
                            ¿Dónde puedo consultar mis reservas?
                        </button>
                    </h3>
                    <div id="pregunta9" class="accordion-collapse collapse" data-bs-parent="#preguntasReservas">
                        <div class="accordion-body">
                            Los usuarios registrados pueden acceder a la sección
                            correspondiente a sus reservas desde su cuenta,
                            donde se presenta la información disponible
                            sobre sus operaciones.
                        </div>
                    </div>
                </div>

            </div>

        </section>

        <div class="volver-inicio">
            <a href="/index.php">Volver al inicio</a>
        </div>

    </main>

    <?php include __DIR__ . "/includes/footer.php"; ?>

    <script src="<?= htmlspecialchars(urlApp('js/bootstrap.bundle.min.js'), ENT_QUOTES, 'UTF-8') ?>"></script>

</body>
</html>