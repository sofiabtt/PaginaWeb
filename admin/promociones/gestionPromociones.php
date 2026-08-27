
<!DOCTYPE html>
<html lang="es">

<head>

    <meta charset="UTF-8">

    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <title>AeroFly Admin - Promociones</title>

    <link rel="stylesheet" href="../../css/bootstrap.min.css">

    <link rel="stylesheet" href="../../css/bootstrap-icons.css">

    <link rel="stylesheet" href="../../css/estilos-admin.css">

    <link rel="icon" type="image/png" href="../../imagenes/logo.png">

</head>


<body>


    <!-- NAVBAR -->

    <?php include "includes/navbarAdmin.php"; ?>



    <!-- CONTENIDO -->

    <main class="contenido-admin">


        <section class="encabezado-contenido">

            <div>

                <h1>
                    Gestión de Promociones
                </h1>

                <p>
                    Administra y gestiona las promociones.
                </p>

            </div>


            <a href="#" class="btn btn-primary">

                <i class="bi bi-plus-lg"></i>

                Crear promoción

            </a>

        </section>



        <!-- TABLA -->

        <section class="tabla-contenedor">

            <table class="table align-middle">

                <thead>

                    <tr>

                        <th>
                            Promoción
                        </th>

                        <th>
                            Aerolínea
                        </th>

                        <th>
                            Descuento
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
                        Las promociones se cargarán
                        posteriormente mediante PHP.
                    -->

                </tbody>

            </table>

        </section>


    </main>



    <script src="../../js/bootstrap.bundle.min.js"></script>

</body>

</html>

