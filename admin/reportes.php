
<!DOCTYPE html>
<html lang="es">

<head>

    <meta charset="UTF-8">

    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <title>AeroFly Admin - Reportes</title>

    <link rel="stylesheet" href="../css/bootstrap.min.css">

    <link rel="stylesheet" href="../css/bootstrap-icons.css">

    <link rel="stylesheet" href="../css/estilos-admin.css">

    <link rel="icon" type="image/png" href="../imagenes/logo.png">

</head>


<body>


    <!-- NAVBAR -->

    <?php include "includes/navbarAdmin.php"; ?>


    <!-- CONTENIDO -->

    <main class="contenido-admin">


        <section class="encabezado-contenido">

            <div>

                <h1>
                    Reportes
                </h1>

                <p>
                    Consulta los reportes y estadísticas del sistema.
                </p>

            </div>

        </section>



        <!-- TABLA DE REPORTES -->

        <section class="tabla-contenedor">

            <table class="table align-middle">

                <thead>

                    <tr>

                        <th>
                            Reporte
                        </th>

                        <th>
                            Descripción
                        </th>

                        <th>
                            Acción
                        </th>

                    </tr>

                </thead>


                <tbody>


                    <tr>

                        <td>
                            Ventas
                        </td>

                        <td>
                            Reservas con estado confirmada.
                        </td>

                        <td>

                            <a href="#" class="btn btn-sm btn-primary">

                                <i class="bi bi-file-earmark-bar-graph"></i>

                                Ver reporte

                            </a>

                        </td>

                    </tr>


                    <tr>

                        <td>
                            Vuelos
                        </td>

                        <td>
                            Información de vuelos realizados.
                        </td>

                        <td>

                            <a href="#" class="btn btn-sm btn-primary">

                                <i class="bi bi-file-earmark-bar-graph"></i>

                                Ver reporte

                            </a>

                        </td>

                    </tr>


                    <tr>

                        <td>
                            Usuarios
                        </td>

                        <td>
                            Información de usuarios registrados.
                        </td>

                        <td>

                            <a href="#" class="btn btn-sm btn-primary">

                                <i class="bi bi-file-earmark-bar-graph"></i>

                                Ver reporte

                            </a>

                        </td>

                    </tr>


                </tbody>

            </table>

        </section>


    </main>



    <script src="../js/bootstrap.bundle.min.js"></script>

</body>

</html>

