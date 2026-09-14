
<?php

    session_start();

    ini_set('display_errors', 1);
    ini_set('display_startup_errors', 1);
    error_reporting(E_ALL);


    // CONEXIÓN

    include "../../php/conexionBD.php";


    // VERIFICAR QUE TENGA UNA AEROLÍNEA ASIGNADA

    if (!isset($_SESSION["codUsuario"])) {

        echo "No se pudo identificar al CEO.";
        exit();

    }

    $codUsuario = $_SESSION["codUsuario"];


    $consultaAerolinea = $conexion->prepare("
        SELECT codAerolinea
        FROM Aerolineas
        WHERE codUsuario = ?
        AND activoAerolinea = 1
    ");

    $consultaAerolinea->bind_param(
        "i",
        $codUsuario
    );

    $consultaAerolinea->execute();

    $resultadoAerolinea = $consultaAerolinea->get_result();


    if ($resultadoAerolinea->num_rows != 1) {

        echo "El CEO no tiene una aerolínea asignada.";
        exit();

    }

    $aerolinea = $resultadoAerolinea->fetch_assoc();

    $codAerolinea = $aerolinea["codAerolinea"];

    $consultaAerolinea->close();


    // INICIALIZAR ERROR

    $error = "";


    // PROCESAR FORMULARIO

    if ($_SERVER["REQUEST_METHOD"] == "POST") {

        $origen = trim($_POST["origen"]);
        $destino = trim($_POST["destino"]);
        $fecha = $_POST["fecha"];
        $hora = $_POST["hora"];
        $precio = $_POST["precio"];
        $asientos = $_POST["asientos"];


        // VALIDAR CAMPOS

        if (
            empty($origen) ||
            empty($destino) ||
            empty($fecha) ||
            empty($hora) ||
            empty($precio) ||
            empty($asientos)
        ) {

            $error = "Debe completar todos los campos.";

        } elseif ($origen == $destino) {

            $error = "El origen y el destino no pueden ser iguales.";

        } elseif (strtotime($fecha) < strtotime(date("Y-m-d"))) {

            $error = "La fecha de salida no puede ser anterior a la fecha actual.";

        } elseif ($precio <= 0) {

            $error = "El precio debe ser mayor a 0.";

        } elseif ($asientos <= 0) {

            $error = "La cantidad de asientos debe ser mayor a 0.";

        }


        // INSERTAR SOLAMENTE SI NO HAY ERROR

        if (empty($error)) {

            // INSERTAR VUELO

            $consulta = $conexion->prepare(
                "INSERT INTO Vuelos
                (
                    codAerolinea,
                    origenVuelo,
                    destinoVuelo,
                    fechaSalidaVuelo,
                    horaSalidaVuelo,
                    precioVuelo,
                    asientosDisponibles
                )
                VALUES (?, ?, ?, ?, ?, ?, ?)"
            );


            $consulta->bind_param(
                "issssdi",
                $codAerolinea,
                $origen,
                $destino,
                $fecha,
                $hora,
                $precio,
                $asientos
            );


            if ($consulta->execute()) {

                $consulta->close();
                $conexion->close();

                header("Location: gestionVuelos.php?mensaje=creado");
                exit();

            } else {

                $error = "No se pudo crear el vuelo.";

            }


            $consulta->close();

        }

    }


    // OBTENER AEROPUERTOS

    $consultaAeropuertos = $conexion->query("
        SELECT
            a.codigoIATA,
            a.nombreAeropuerto,
            c.nombreCiudad,
            p.nombrePais

        FROM Aeropuertos a

        INNER JOIN Ciudades c
            ON a.codCiudad = c.codCiudad

        INNER JOIN Paises p
            ON c.codPais = p.codPais

        ORDER BY
            p.nombrePais,
            c.nombreCiudad
    ");


    if (!$consultaAeropuertos) {

        die(
            "Error al obtener los aeropuertos: "
            . $conexion->error
        );

    }

?>


<!DOCTYPE html>
<html lang="es">

<head>

    <meta charset="UTF-8">

    <meta
        name="viewport"
        content="width=device-width, initial-scale=1.0"
    >

    <title>Nuvia - CEO</title>


    <link
        rel="icon"
        href="../../imagenes/logo.png"
        type="image/png"
    >


    <!-- Bootstrap -->

    <link
        rel="stylesheet"
        href="../../css/bootstrap.min.css"
    >


    <!-- Bootstrap Icons -->

    <link
        rel="stylesheet"
        href="../../css/bootstrap-icons.css"
    >


    <!-- Estilos del administrador -->

    <link
        rel="stylesheet"
        href="../../css/estilos-admin.css"
    >

</head>


<body>


    <!-- NAVBAR -->

    <?php include "../includes/navbarCeo.php"; ?>


    <main class="contenido-admin">


        <!-- ENCABEZADO -->

        <section class="bienvenida-admin">

            <h1>
                Crear vuelo
            </h1>

            <p>
                Completa los datos del nuevo vuelo de tu aerolínea.
            </p>

        </section>


        <!-- FORMULARIO -->

        <section class="perfil-card">


            <?php if (!empty($error)) { ?>

                <div class="alert alert-danger" role="alert">

                    <?php
                    echo htmlspecialchars($error);
                    ?>

                </div>

            <?php } ?>


            <form method="POST">


                <!-- ORIGEN Y DESTINO -->

                <div class="perfil-campos">


                    <!-- ORIGEN -->

                    <div>

                        <label
                            for="origen"
                            class="form-label"
                        >
                            Origen
                        </label>

                        <div class="campo-icono">

                            <i class="bi bi-geo-alt"></i>

                            <select
                                class="form-select"
                                id="origen"
                                name="origen"
                                style="padding-left: 50px;"
                                required
                            >

                                <option value="">
                                    Seleccione el aeropuerto de origen
                                </option>


                                <?php

                                while (
                                    $aeropuerto =
                                    $consultaAeropuertos->fetch_assoc()
                                ) {

                                ?>

                                    <option
                                        value="<?php
                                            echo htmlspecialchars(
                                                $aeropuerto["nombreCiudad"]
                                            );
                                        ?>"
                                        <?php

                                        if (
                                            isset($_POST["origen"]) &&
                                            $_POST["origen"] ==
                                            $aeropuerto["nombreCiudad"]
                                        ) {

                                            echo "selected";

                                        }

                                        ?>
                                    >

                                        <?php

                                        echo htmlspecialchars(
                                            $aeropuerto["nombreCiudad"]
                                            . " ("
                                            . $aeropuerto["codigoIATA"]
                                            . ") - "
                                            . $aeropuerto["nombrePais"]
                                        );

                                        ?>

                                    </option>

                                <?php

                                }

                                ?>

                            </select>

                        </div>

                    </div>



                    <!-- DESTINO -->

                    <div>

                        <label
                            for="destino"
                            class="form-label"
                        >
                            Destino
                        </label>

                        <div class="campo-icono">

                            <i class="bi bi-geo-alt-fill"></i>

                            <select
                                class="form-select"
                                id="destino"
                                name="destino"
                                style="padding-left: 50px;"
                                required
                            >

                                <option value="">
                                    Seleccione el aeropuerto de destino
                                </option>


                                <?php

                                $consultaAeropuertosDestino =
                                    $conexion->query("
                                        SELECT
                                            a.codigoIATA,
                                            a.nombreAeropuerto,
                                            c.nombreCiudad,
                                            p.nombrePais

                                        FROM Aeropuertos a

                                        INNER JOIN Ciudades c
                                            ON a.codCiudad = c.codCiudad

                                        INNER JOIN Paises p
                                            ON c.codPais = p.codPais

                                        ORDER BY
                                            p.nombrePais,
                                            c.nombreCiudad
                                    ");


                                if (!$consultaAeropuertosDestino) {

                                    die(
                                        "Error al obtener los aeropuertos: "
                                        . $conexion->error
                                    );

                                }


                                while (
                                    $aeropuerto =
                                    $consultaAeropuertosDestino->fetch_assoc()
                                ) {

                                ?>

                                    <option
                                        value="<?php
                                            echo htmlspecialchars(
                                                $aeropuerto["nombreCiudad"]
                                            );
                                        ?>"
                                        <?php

                                        if (
                                            isset($_POST["destino"]) &&
                                            $_POST["destino"] ==
                                            $aeropuerto["nombreCiudad"]
                                        ) {

                                            echo "selected";

                                        }

                                        ?>
                                    >

                                        <?php

                                        echo htmlspecialchars(
                                            $aeropuerto["nombreCiudad"]
                                            . " ("
                                            . $aeropuerto["codigoIATA"]
                                            . ") - "
                                            . $aeropuerto["nombrePais"]
                                        );

                                        ?>

                                    </option>

                                <?php

                                }

                                ?>

                            </select>

                        </div>

                    </div>


                </div>


                <!-- FECHA Y HORA -->

                <div
                    class="perfil-campos"
                    style="margin-top: 25px;"
                >


                    <!-- FECHA -->

                    <div>

                        <label
                            for="fecha"
                            class="form-label"
                        >
                            Fecha de salida
                        </label>

                        <div class="campo-icono">

                            <i class="bi bi-calendar"></i>

                            <input
                                type="date"
                                class="form-control"
                                id="fecha"
                                name="fecha"
                                value="<?php
                                    echo isset($_POST["fecha"])
                                        ? htmlspecialchars($_POST["fecha"])
                                        : "";
                                ?>"
                                required
                            >

                        </div>

                    </div>



                    <!-- HORA -->

                    <div>

                        <label
                            for="hora"
                            class="form-label"
                        >
                            Hora de salida
                        </label>

                        <div class="campo-icono">

                            <i class="bi bi-clock"></i>

                            <input
                                type="time"
                                class="form-control"
                                id="hora"
                                name="hora"
                                value="<?php
                                    echo isset($_POST["hora"])
                                        ? htmlspecialchars($_POST["hora"])
                                        : "";
                                ?>"
                                required
                            >

                        </div>

                    </div>


                </div>


                <!-- PRECIO Y ASIENTOS -->

                <div
                    class="perfil-campos"
                    style="margin-top: 25px;"
                >


                    <!-- PRECIO -->

                    <div>

                        <label
                            for="precio"
                            class="form-label"
                        >
                            Precio
                        </label>

                        <div class="campo-icono">

                            <i class="bi bi-currency-dollar"></i>

                            <input
                                type="number"
                                class="form-control"
                                id="precio"
                                name="precio"
                                min="1"
                                step="1"
                                placeholder="Ej. 50000"
                                value="<?php
                                    echo isset($_POST["precio"])
                                        ? htmlspecialchars($_POST["precio"])
                                        : "";
                                ?>"
                                required
                            >

                        </div>

                    </div>



                    <!-- ASIENTOS -->

                    <div>

                        <label
                            for="asientos"
                            class="form-label"
                        >
                            Asientos disponibles
                        </label>

                        <div class="campo-icono">

                            <i class="bi bi-person"></i>

                            <input
                                type="number"
                                class="form-control"
                                id="asientos"
                                name="asientos"
                                min="1"
                                step="1"
                                placeholder="Ej. 150"
                                value="<?php
                                    echo isset($_POST["asientos"])
                                        ? htmlspecialchars($_POST["asientos"])
                                        : "";
                                ?>"
                                required
                            >

                        </div>

                    </div>


                </div>


                <!-- BOTONES -->

                <div class="perfil-acciones">


                    <a
                        href="gestionVuelos.php"
                        class="btn btn-outline-secondary me-2"
                    >
                        Cancelar
                    </a>


                    <button
                        type="submit"
                        class="btn btn-primary"
                    >

                        <i class="bi bi-plus-lg"></i>

                        Crear vuelo

                    </button>


                </div>


            </form>


        </section>


    </main>


    <script
        src="../../js/bootstrap.bundle.min.js">
    </script>


</body>

</html>
