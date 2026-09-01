<?php

include "../php/conexionBD.php";


// ADMINISTRADOR
$codAdmin = 1;


// SI SE ENVIO EL FORMULARIO
if ($_SERVER["REQUEST_METHOD"] == "POST") {

    $nombre = $_POST["nombreUsuario"];
    $email = $_POST["emailUsuario"];
    $telefono = $_POST["telefonoUsuario"];


    // ACTUALIZAR DATOS DEL ADMIN
    $consulta = "UPDATE Usuarios
                 SET nombreUsuario = ?,
                     emailUsuario = ?,
                     telefonoUsuario = ?
                 WHERE codUsuario = ?";


    $stmt = $conexion->prepare($consulta);

    $stmt->bind_param(
        "sssi",
        $nombre,
        $email,
        $telefono,
        $codAdmin
    );


    if ($stmt->execute()) {

        // REDIRIGIR DESPUES DE GUARDAR
        header("Location: perfil-admin.php?actualizado=1");

        exit;

    } else {

        $mensaje = "Ocurrió un error al actualizar los datos.";

    }


    $stmt->close();

}


// MOSTRAR MENSAJE SOLO SI SE ACABA DE ACTUALIZAR
if (isset($_GET["actualizado"])) {

    $mensaje = "Los datos se actualizaron correctamente.";

}


// BUSCAR LOS DATOS DEL ADMIN
$consulta = "SELECT nombreUsuario, emailUsuario, telefonoUsuario
             FROM Usuarios
             WHERE codUsuario = ?";


$stmt = $conexion->prepare($consulta);

$stmt->bind_param(
    "i",
    $codAdmin
);

$stmt->execute();

$resultado = $stmt->get_result();

$admin = $resultado->fetch_assoc();

$stmt->close();

?>

<!DOCTYPE html>

<html lang="es">

<head>

    <meta charset="UTF-8">

    <meta
        name="viewport"
        content="width=device-width, initial-scale=1.0"
    >

    <title>
        Admin - Mi perfil
    </title>


    <!-- Bootstrap -->

    <link
        rel="stylesheet"
        href="../css/bootstrap.min.css"
    >


    <!-- Bootstrap Icons -->

    <link
        rel="stylesheet"
        href="../css/bootstrap-icons.css"
    >


    <!-- CSS del administrador -->

    <link
        rel="stylesheet"
        href="../css/estilos-admin.css"
    >


    <!-- Favicon -->

    <link
        rel="icon"
        type="image/png"
        href="../imagenes/logo.png"
    >

</head>


<body>


    <!-- NAVBAR -->

    <?php include "includes/navbarAdmin.php"; ?>


    <main class="contenido-admin">


        <!-- ENCABEZADO -->

        <section class="encabezado-contenido">

            <div>

                <h1>
                    Mi perfil
                </h1>

                <p>
                    Gestioná tus datos personales.
                </p>

            </div>

        </section>



        <!-- MENSAJE -->

        <?php if (isset($mensaje)) { ?>

            <div
                class="alert alert-success"
                role="alert"
            >

                <?php echo $mensaje; ?>

            </div>

        <?php } ?>



        <!-- PERFIL -->

        <section class="container-fluid px-0">


            <div
                class="card border-0 shadow-sm mx-auto"
                style="max-width: 850px;"
            >


                <div class="card-body text-center p-4 p-md-5">


                    <!-- ICONO ADMIN -->

                    <div
                        class="rounded-circle d-flex align-items-center justify-content-center mx-auto mb-3"
                        style="
                            width: 75px;
                            height: 75px;
                            background-color: #f3e9df;
                            color: #684028;
                            font-size: 34px;
                        "
                    >

                        <i class="bi bi-person-fill"></i>

                    </div>


                    <h2 class="h4 mb-2">

                        Perfil de administrador

                    </h2>


                    <p class="text-muted mb-4">

                        Gestioná la información de tu cuenta.

                    </p>



                    <!-- FORMULARIO -->

                    <form
                        method="POST"
                        action="perfil-admin.php"
                        class="text-start"
                    >


                        <div class="row g-4">


                            <!-- NOMBRE -->

                            <div class="col-md-6">

                                <label
                                    for="nombreUsuario"
                                    class="form-label fw-semibold"
                                >

                                    Nombre

                                </label>


                                <div class="input-group">

                                    <span class="input-group-text">

                                        <i class="bi bi-person"></i>

                                    </span>


                                    <input
                                        type="text"
                                        class="form-control"
                                        id="nombreUsuario"
                                        name="nombreUsuario"
                                        value="<?php echo htmlspecialchars($admin["nombreUsuario"]); ?>"
                                        required
                                    >

                                </div>

                            </div>



                            <!-- EMAIL -->

                            <div class="col-md-6">

                                <label
                                    for="emailUsuario"
                                    class="form-label fw-semibold"
                                >

                                    Email

                                </label>


                                <div class="input-group">

                                    <span class="input-group-text">

                                        <i class="bi bi-envelope"></i>

                                    </span>


                                    <input
                                        type="email"
                                        class="form-control"
                                        id="emailUsuario"
                                        name="emailUsuario"
                                        value="<?php echo htmlspecialchars($admin["emailUsuario"]); ?>"
                                        required
                                    >

                                </div>

                            </div>



                            <!-- TELEFONO -->

                            <div class="col-md-6">

                                <label
                                    for="telefonoUsuario"
                                    class="form-label fw-semibold"
                                >

                                    Teléfono

                                </label>


                                <div class="input-group">

                                    <span class="input-group-text">

                                        <i class="bi bi-telephone"></i>

                                    </span>


                                    <input
                                        type="tel"
                                        class="form-control"
                                        id="telefonoUsuario"
                                        name="telefonoUsuario"
                                        value="<?php echo htmlspecialchars($admin["telefonoUsuario"]); ?>"
                                        required
                                    >

                                </div>

                            </div>


                        </div>



                        <!-- CONTRASEÑA -->

                        <div
                            class="alert mt-4 mb-4 d-flex align-items-center gap-3"
                        >

                            <i class="bi bi-lock-fill fs-4"></i>


                            <div>

                                <strong>
                                    Contraseña
                                </strong>


                                <div class="small text-muted">

                                    Por seguridad, la contraseña no puede
                                    modificarse en este perfil.

                                </div>

                            </div>

                        </div>



                        <!-- BOTON -->

                        <div class="text-end">

                            <button
                                type="submit"
                                class="btn btn-primary"
                            >

                                <i class="bi bi-check-lg"></i>

                                Guardar cambios

                            </button>

                        </div>


                    </form>


                </div>

            </div>


        </section>


    </main>


</body>

</html>