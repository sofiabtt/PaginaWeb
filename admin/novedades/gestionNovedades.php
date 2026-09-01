```html
<!DOCTYPE html>
<html lang="es">

<head>

    <meta charset="UTF-8">

    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <title>AeroFly Admin - Novedades</title>

    <link rel="stylesheet" href="../../css/bootstrap.min.css">

    <link rel="stylesheet" href="../../css/bootstrap-icons.css">

    <link rel="stylesheet" href="../../css/estilos-admin.css">

    <link rel="icon" type="image/png" href="../../imagenes/logo.png">

</head>


<body>


    <!-- NAVBAR -->

    <?php include "../includes/navbarAdmin.php"; ?>


    <!-- CONTENIDO -->

    <main class="contenido-admin">


        <section class="encabezado-contenido">

            <div>

                <h1>
                    Gestión de Novedades
                </h1>

                <p>
                    Administra las novedades publicadas.
                </p>

            </div>


            <a href="#" class="btn btn-primary">

                <i class="bi bi-plus-lg"></i>

                Crear novedad

            </a>

        </section>



        <!-- TABLA -->

        <section class="tabla-contenedor">

            <table class="table align-middle">

                <thead>

                    <tr>

                        <th>
                            Título
                        </th>

                        <th>
                            Fecha
                        </th>

                        <th>
                            Estado
                        </th>

                        <th>
                            Acciones
                        </th>

                    </tr>

                </thead>


                <tbody>

                    <!--
                        Las novedades se cargarán
                        posteriormente mediante PHP.
                    -->

                </tbody>

            </table>

        </section>


    </main>



    <script src="../../js/bootstrap.bundle.min.js"></script>

</body>

</html>

