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
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">
</head>

<body>
    <?php include("includes/navbar.php"); ?>
    
    <main class="d-flex justify-content-center align-items-center">
        <section class="rectangulo-formulario">
            <h1 class="text-center mb-4 texto-negro">
                Crear Cuenta
            </h1>

            <form action="usuarioNOregistrado/registroUsuario.php" method="POST">
                <div class="mb-4">
                    <label
                        for="nombreApellido"
                        class="form-label texto-negro"
                    >
                        Ingrese Nombre y Apellido:
                    </label>

                    <input
                        type="text"
                        class="form-control"
                        id="nombreApellido"
                        name="nombreApellido"
                        required
                    >

                    <label
                        for="telefono"
                        class="form-label texto-negro"
                    >
                        Ingrese Teléfono:
                    </label>

                    <input
                        type="tel"
                        class="form-control"
                        id="telefono"
                        name="telefono"
                        required
                    >

                    <label
                        for="contrasena"
                        class="form-label texto-negro"
                    >
                        Ingrese Contraseña:
                    </label>

                    <div class="barra-contra">
                        <input
                            type="password"
                            class="form-control"
                            id="contrasena"
                            name="contrasena"
                            minlength="8"
                            required
                        >

                        <button
                            type="button"
                            class="boton-ojo"
                            onclick="mostrarContrasena()"
                            id="boton-ojo"
                        >
                            <i class="bi bi-eye"></i>
                        </button>
                    </div>
                </div>

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

                    <button type="submit" class="btn btn-primary">
                        Continuar
                    </button>
                </div>
            </form>
        </section>

        <a href="home.php" class="boton-atras">
            ← Inicio
        </a>
    </main>

    <script>
        const parametros = new URLSearchParams(window.location.search);

        if (parametros.get("error") === "datos_invalidos") {
            const formulario = document.querySelector("form");

            formulario.insertAdjacentHTML(
                "afterbegin",
                '<div class="alert alert-danger">' +
                'Completá todos los datos. La contraseña debe tener al menos 8 caracteres.' +
                '</div>'
            );
        }

        function mostrarContrasena() {
            const input = document.getElementById("contrasena");
            const icono = document.querySelector("#boton-ojo i");

            if (input.type === "password") {
                input.type = "text";
                icono.className = "bi bi-eye-slash";
            } else {
                input.type = "password";
                icono.className = "bi bi-eye";
            }
        }
    </script>

    <script src="js/bootstrap.bundle.min.js"></script>
</body>
</html>