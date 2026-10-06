<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">

    <meta
        name="viewport"
        content="width=device-width, initial-scale=1.0"
    >

    <title>Nuvia - Registro</title>

    <link rel="stylesheet" href="css/bootstrap.min.css">
    <link rel="stylesheet" href="css/bootstrap-icons.css">
    <link rel="stylesheet" href="css/estilos.css">
    <link rel="stylesheet" href="css/navbar.css">
    <link rel="icon" type="image/png" href="imagenes/logo.png">
</head>

<body>

    <?php include("includes/navbar.php"); ?>

    <main class="d-flex justify-content-center align-items-center">

        <section class="rectangulo-formulario">

            <h1 class="text-center mb-4 texto-negro">
                Crear Cuenta
            </h1>


            <form
                action="usuarioNOregistrado/registroUsuario.php"
                method="POST"
                novalidate
            >

                <div class="mb-4">


                    <!-- =========================
                         NOMBRE Y APELLIDO
                    ========================== -->

                    <div class="d-flex align-items-center gap-2">

                        <label
                            for="nombreApellido"
                            class="form-label texto-negro mb-0"
                        >
                            Ingrese Nombre y Apellido:
                        </label>

                        <span
                            id="error-nombre"
                            class="alerta-en-rojo"
                        ></span>

                    </div>

                    <input
                        type="text"
                        class="form-control"
                        id="nombreApellido"
                        name="nombreApellido"
                        required
                    >



                    <!-- =========================
                         TELÉFONO
                    ========================== -->

                    <div class="d-flex align-items-center gap-2 mt-3">

                        <label
                            for="telefono"
                            class="form-label texto-negro mb-0"
                        >
                            Ingrese Teléfono:
                        </label>

                        <span
                            id="error-telefono"
                            class="alerta-en-rojo"
                        ></span>

                    </div>

                    <input
                        type="tel"
                        class="form-control"
                        id="telefono"
                        name="telefono"
                        required
                    >



                    <!-- =========================
                         CONTRASEÑA
                    ========================== -->

                    <div class="d-flex align-items-center gap-2 mt-3">

                        <label
                            for="contrasena"
                            class="form-label texto-negro mb-0"
                        >
                            Contraseña:
                        </label>

                        <span
                            id="error-contrasena"
                            class="alerta-en-rojo"
                        ></span>

                    </div>


                    <div class="grupo-contrasena">

                        <input
                            type="password"
                            class="form-control"
                            id="contrasena"
                            name="contrasena"
                            minlength="6"
                            required
                        >

                        <button
                            type="button"
                            class="boton-ojo"
                            onclick="mostrarContrasena()"
                            id="boton-ojo"
                            aria-label="Mostrar u ocultar contraseña"
                        >
                            <i class="bi bi-eye"></i>
                        </button>

                    </div>


                    <small class="text-muted">
                        Mínimo 6 caracteres, una letra mayúscula y un número.
                    </small>

                </div>



                <!-- =========================
                     BOTONES
                ========================== -->

                <div class="d-flex justify-content-between align-items-end gap-3">

                    <div>

                        <p class="texto-registro mb-1 texto-negro">
                            ¿Desea volver?
                        </p>

                        <a
                            href="registro.php"
                            class="btn btn-outline-primary"
                        >
                            Atrás
                        </a>

                    </div>


                    <button
                        type="submit"
                        class="btn btn-primary"
                    >
                        Continuar
                    </button>

                </div>

            </form>

        </section>



        <!-- BOTÓN INICIO -->

        <a
            href="home.php"
            class="boton-atras"
        >
            ← Inicio
        </a>

    </main>



    <script>

        const parametros =
            new URLSearchParams(window.location.search);


        // =========================
        // RECUPERAR NOMBRE
        // =========================

        const nombre =
            parametros.get("nombre");

        if (nombre !== null) {

            document.getElementById("nombreApellido").value =
                nombre;

        }


        // =========================
        // RECUPERAR TELÉFONO
        // =========================

        const telefono =
            parametros.get("telefono");

        if (telefono !== null) {

            document.getElementById("telefono").value =
                telefono;

        }


        // =========================
        // ERROR NOMBRE
        // =========================

        if (parametros.get("errorNombre") === "1") {

            document.getElementById("error-nombre").textContent =
                "Ingrese un nombre válido *";

        }


        // =========================
        // ERROR TELÉFONO
        // =========================

        if (parametros.get("errorTelefono") === "1") {

            document.getElementById("error-telefono").textContent =
                "Ingrese un teléfono válido *";

        }


        // =========================
        // ERROR CONTRASEÑA
        // =========================

        if (parametros.get("errorContrasena") === "1") {

            document.getElementById("error-contrasena").textContent =
                "Contraseña inválida *";

        }


        // =========================
        // MOSTRAR / OCULTAR CONTRASEÑA
        // =========================

        function mostrarContrasena() {

            const input =
                document.getElementById("contrasena");

            const icono =
                document.querySelector("#boton-ojo i");


            if (input.type === "password") {

                input.type = "text";

                icono.className =
                    "bi bi-eye-slash";

            } else {

                input.type = "password";

                icono.className =
                    "bi bi-eye";

            }

        }

    </script>


    <script src="js/bootstrap.bundle.min.js"></script>

</body>

</html>