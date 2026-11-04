<?php
session_start();
?>

<!DOCTYPE html>
<html lang="es">
    <head>

        <meta charset="UTF-8">

        <meta name="viewport" content="width=device-width, initial-scale=1.0">

        <title>Nuvia - Registro</title>

        <link rel="stylesheet" href="../css/bootstrap.min.css">

        <link rel="stylesheet" href="../css/estilos.css">

        <link rel="stylesheet" href="../css/navbar.css">

        <link rel="stylesheet" href="../css/footer.css">

        <link rel="icon" type="image/png" href="../imagenes/logo.png">
        
    </head>
    <body>

        <?php include "../includes/navbar.php"; ?>
        
        <main class="d-flex justify-content-center align-items-center">

        <section class="rectangulo-formulario">

            <h1 class="text-center mb-4 texto-negro">
                Crear Cuenta
            </h1>

            <form action="../php/verificarCod.php" method="POST">
                

                <div class="mb-4">
                    <label for="codigoVerificacion" class="form-label texto-negro">
                        Ingrese Codigo de verificacion:
                    </label>
                    
                    <input
                    type="text"
                    class="form-control"
                    id="codigoVerificacion"
                    name="codigoVerificacion"
                    required
                    > 

                    <?php

                    if (isset($_SESSION["errorCodigo"])) {

                        echo '<p style="color: red;">'
                            . $_SESSION["errorCodigo"]
                            . '</p>';

                        unset($_SESSION["errorCodigo"]);
                    }

                    ?>

                <div class="d-flex justify-content-between align-items-end gap-3">
                <!--lo de arriba coloca dos sectores en una misma fila.-->

                    <div>
                        <p class="texto-registro mb-1 texto-negro">
                            Desea volver?
                        </p>

                        <a href="../registro.php" class="btn btn-outline-primary">
                            Atrás
                        </a>
                    </div>

                    <button type="submit" class="btn btn-primary">
                        Continuar
                    </button>

                </div>

            </form>

        </section>
        <a href="../index.php" class="boton-atras">
            ← Inicio
        </a>

    </main>

    <?php include "/includes/footer.php"; ?>

    <script src="/js/bootstrap.bundle.min.js"></script>

    </body>

</html>