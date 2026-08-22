
<!DOCTYPE html>
<html lang="es">

<head>

    <meta charset="UTF-8">

    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <title>AeroFly Admin - CEOs</title>

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


    <!-- =========================
         NAVBAR
    ========================== -->

    <?php include "includes/navbar-admin.php"; ?>



    <!-- =========================
         CONTENIDO
    ========================== -->

    <main class="contenido-admin">


        <section class="encabezado-contenido">

            <div>

                <h1>
                    Gestión de CEOs
                </h1>

                <p>
                    Administra los CEOs registrados en el sistema.
                </p>

            </div>


            <a href="#" class="btn btn-primary">

                <i class="bi bi-plus-lg"></i>

                Agregar CEO

            </a>

        </section>



        <!-- TABLA -->

        <section class="tabla-contenedor">

            <table class="table align-middle">

                <thead>

                    <tr>

                        <th>
                            Nombre
                        </th>

                        <th>
                            Email
                        </th>

                        <th>
                            Aerolínea
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
                        Los CEOs se cargarán
                        posteriormente mediante PHP.
                    -->

                </tbody>

            </table>

        </section>


    </main>



    <script src="../../js/bootstrap.bundle.min.js"></script>

</body>

</html>

