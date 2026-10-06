<!DOCTYPE html>
<html lang="es">

<head>

    <meta charset="UTF-8">

    <meta
        name="viewport"
        content="width=device-width, initial-scale=1.0"
    >

    <title>Nuvia - Verificá tu cuenta</title>

    <link rel="stylesheet" href="../css/bootstrap.min.css">
    <link rel="stylesheet" href="../css/bootstrap-icons.css">
    <link rel="stylesheet" href="../css/estilos.css">
    <link rel="stylesheet" href="../css/navbar.css">

    <link
        rel="icon"
        type="image/png"
        href="../imagenes/logo.png"
    >

</head>

<body>

    <?php include("../includes/navbar.php"); ?>


    <main class="d-flex justify-content-center align-items-center">

        <section class="rectangulo-formulario formulario-exito">

            <div class="verificacion-contenido">

                <div class="icono-verificacion">
                    <i class="bi bi-envelope-check"></i>
                </div>

                <h1>
                    Revisá tu correo
                </h1>

                <p class="verificacion-texto">
                    Te enviamos un enlace para verificar tu cuenta.
                </p>

                <p class="verificacion-aclaracion">
                    El enlace estará disponible durante 24 horas.
                </p>

                <a
                    href="../home.php"
                    class="btn btn-primary"
                >
                    Volver al inicio
                </a>

            </div>

        </section>

    </main>


    <script src="../js/bootstrap.bundle.min.js"></script>

</body>

</html>