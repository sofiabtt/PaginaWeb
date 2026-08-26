<?php
session_start();
?>

<!DOCTYPE html>
<html lang="es">
    <head>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <!--Hace que la página se adapte al ancho de celulares, tablets y computadoras.-->
        <title>Aerolinea</title>
        

        <link rel="stylesheet" href="../css/bootstrap.min.css">

        <link rel="stylesheet" href="../css/estilos.css">

        <link rel="icon" type="image/png" href="../imagenes/logo.png">
        

    </head>
    <body>

        <header class="barra-superior navbar navbar-expand-lg">

            <a class="navbar-brand">
                <img
                src="../imagenes/logo.png"
                alt="logo"
                width="60"
                height="60">
            </a>

            <div class="ms-auto d-none d-lg-flex align-items-center">

                <a class="nav-link">
                    Novedades
                </a>

                <a class="nav-link">
                    Destinos
                </a>

                <a class="nav-link">
                    Ofertas
                </a>


            </div>

        </header>
        
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

                        <a href="registro.html" class="btn btn-outline-primary">
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

    </body>

</html>