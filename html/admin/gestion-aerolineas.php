<!DOCTYPE html>
<html lang="es">

<head>

    <meta charset="UTF-8">

    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <title>AeroFly Admin - Aerolíneas</title>

    <!-- Bootstrap -->
    <link rel="stylesheet" href="../../css/bootstrap.min.css">

    <!-- Bootstrap Icons -->
    <link rel="stylesheet" href="../../css/bootstrap-icons.css">

    <!-- CSS del administrador -->
    <link rel="stylesheet" href="../../css/estilos-admin.css">

    <!-- Favicon -->
    <link rel="icon" type="image/png" href="../../imagenes/logo.png">

</head>


<body>
    <!--NAVBAR-->

    <?php include "includes/navbar-admin.php"; ?>


    <main class="contenido-admin">


        <!-- ENCABEZADO-->

        <section class="encabezado-contenido">


            <div>

                <h1>
                    Gestión de Aerolíneas
                </h1>


                <p>
                    Administra las aerolíneas registradas en el sistema.
                </p>

            </div>



            <!-- CREAR AEROLÍNEA -->

            <a href="#" class="btn btn-primary">

                <i class="bi bi-plus-lg"></i>

                Crear aerolínea

            </a>


        </section>



        <!--TABLA DE AEROLÍNEAS-->

        <section class="tabla-contenedor">


            <table class="table align-middle">


                <!-- ENCABEZADO DE LA TABLA -->

                <thead>

                    <tr>

                        <th scope="col">
                            Código
                        </th>


                        <th scope="col">
                            Aerolínea
                        </th>


                        <th scope="col">
                            País
                        </th>


                        <th scope="col">
                            Acciones
                        </th>

                    </tr>

                </thead>



                <!-- CUERPO -->

                <tbody>


                    <!--
                        Los registros de las aerolíneas
                        se cargarán posteriormente
                        mediante PHP y la base de datos.
                    -->


                </tbody>


            </table>


        </section>


    </main>

    <script src="../../js/bootstrap.bundle.min.js"></script>


</body>

</html>