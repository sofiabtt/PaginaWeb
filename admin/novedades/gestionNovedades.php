```html
<?php
include "../../php/conexionBD.php"; //conexion base

$consulta = "SELECT * FROM Novedades ORDER BY codNovedad";
$resultado = $conexion->query($consulta); //guarda en rdo
?>
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


            <a href="crearNovedad.php" class="btn btn-primary">

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
                            Codigo
                        </th>

                        <th>
                            Novedad
                        </th>

                        <th>
                            Publicacion
                        </th>

                        <th>
                            Expiracion
                        </th>

                        <th>
                            Acciones
                        </th>

                    </tr>

                </thead>


                <tbody>

                    <?php while ($novedad = $resultado->fetch_assoc()) { ?>

    <tr>
        <td>
            <?php echo $novedad["codNovedad"]; ?>
        </td>

        <td>
            <?php echo $novedad["textoNovedad"]; ?>
        </td>

        <td>
            <?php echo $novedad["fechaPublicacionNovedad"]; ?>
        </td>

        <td>
            <?php echo $novedad["fechaExpiracionNovedad"]; ?>
        </td>

        <td>
            Modificar | Eliminar
        </td>
    </tr>

<?php } ?>
                </tbody>

            </table>

        </section>


    </main>



    <script src="../../js/bootstrap.bundle.min.js"></script>

</body>

</html>

