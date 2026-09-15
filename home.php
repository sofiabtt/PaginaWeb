<?php

session_start();

include("php/conexionBD.php");


// OBTENER AEROPUERTOS PARA EL BUSCADOR

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
    ORDER BY c.nombreCiudad ASC, a.codigoIATA ASC
");

?>

<!DOCTYPE html>

<html lang="es">

<head>

    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <title>Nuvia</title>

    <link rel="icon" href="imagenes/logo.png" type="image/png">

    <link rel="stylesheet" href="css/bootstrap.min.css">

    <link rel="stylesheet" href="css/estiloshome.css?v=2">
    <link rel="stylesheet" href="css/footer.css">

</head>


<body>


<section class="hero">


    <!-- NAVBAR -->

    <?php include("includes/navbar.php"); ?>



    <!-- BUSCADOR -->


    <div class="contenedor-buscador">


        <div class="tipo-viaje">


            <p class="titulo-buscador">

                Encontrá el
                <strong>vuelo ideal</strong>
                para tu próximo viaje

            </p>


            <!-- IDA Y VUELTA -->

            <input
                type="radio"
                name="viaje"
                id="ida-vuelta"
                checked
                onclick="mostrarVuelta()">

            <label for="ida-vuelta">

                Ida y vuelta

            </label>



            <!-- SOLO IDA -->

            <input
                type="radio"
                name="viaje"
                id="solo-ida"
                onclick="ocultarVuelta()">

            <label for="solo-ida">

                Solo ida

            </label>


        </div>



        <!-- FORMULARIO DE BÚSQUEDA -->

        <form
            action="resultadosVuelos.php"
            method="GET">


            <!-- TIPO DE VIAJE -->

            <input
                type="hidden"
                name="tipoViaje"
                id="tipoViaje"
                value="idaVuelta">


            <div id="buscador-ing-datos">

                <div class="row g-0">


                    <!-- DESDE -->

                    <div class="col-md-3">


                        <input
                            type="text"
                            name="origen"
                            class="form-control"
                            list="aeropuertos"
                            placeholder="Desde"
                            autocomplete="off"
                            required>


                    </div>



                    <!-- HACIA -->

                    <div class="col-md-3">


                        <input
                            type="text"
                            name="destino"
                            class="form-control"
                            list="aeropuertos"
                            placeholder="Hacia"
                            autocomplete="off"
                            required>


                    </div>



                    <!-- LISTA DE AEROPUERTOS -->

                    <datalist id="aeropuertos">

                        <?php while (
                            $aeropuerto =
                            $consultaAeropuertos->fetch_assoc()
                        ) { ?>

                            <option value="<?php

                                echo htmlspecialchars(
                                    $aeropuerto["nombreCiudad"]
                                );

                                echo " - ";

                                echo htmlspecialchars(
                                    $aeropuerto["codigoIATA"]
                                );

                                echo " - ";

                                echo htmlspecialchars(
                                    $aeropuerto["nombreAeropuerto"]
                                );

                            ?>">

                            </option>

                        <?php } ?>

                    </datalist>



                    <!-- FECHA DE IDA -->

                    <div
                        class="col-md-2"
                        id="fecha-ida">


                        <input
                            type="date"
                            name="fecha"
                            id="fecha"
                            class="form-control"
                            required>


                    </div>



                    <!-- FECHA DE VUELTA -->

                    <div
                        class="col-md-2"
                        id="fecha-vuelta">


                        <input
                            type="date"
                            name="fechaVuelta"
                            id="fechaVuelta"
                            class="form-control">


                    </div>



                    <!-- BOTÓN BUSCAR -->

                    <div class="col-md-2">


                        <button
                            type="submit"
                            class="buscar">

                            →

                        </button>


                    </div>


                </div>

            </div>


        </form>


        <br>


    </div>



    <!-- DESTINOS DESTACADOS -->


    <section class="destinos-destacados">


        <h4 class="titulo-destino">

            Viaja por el mundo

        </h4>


        <div class="contenedor-tarjetas">


            <div class="tarjeta-destino">


                <img
                    src="imagenes/brasil.jpg"
                    alt="Destino Brasil">


                <h4>
                    Brasil
                </h4>


            </div>



            <div class="tarjeta-destino">


                <img
                    src="imagenes/bsas.jpg"
                    alt="Destino Buenos Aires">


                <h4>
                    Buenos Aires
                </h4>


            </div>



            <div class="tarjeta-destino">


                <img
                    src="imagenes/madrid.jpg"
                    alt="Destino Madrid">


                <h4>
                    Madrid
                </h4>


            </div>



            <div class="tarjeta-destino">


                <img
                    src="imagenes/roma.jpg"
                    alt="Destino Roma">


                <h4>
                    Roma
                </h4>


            </div>


        </div>


    </section>


</section>


<?php include("includes/footer.php"); ?>



<!-- JAVASCRIPT -->

<script>


    function ocultarVuelta(){

        document.getElementById(
            "fecha-vuelta"
        ).style.display = "none";


        document.getElementById(
            "fechaVuelta"
        ).value = "";


        document.getElementById(
            "fechaVuelta"
        ).removeAttribute("required");


        document.getElementById(
            "fecha-ida"
        ).className = "col-md-4";


        document.getElementById(
            "tipoViaje"
        ).value = "ida";

    }



    function mostrarVuelta(){

        document.getElementById(
            "fecha-vuelta"
        ).style.display = "block";


        document.getElementById(
            "fechaVuelta"
        ).setAttribute("required", "");


        document.getElementById(
            "fecha-ida"
        ).className = "col-md-2";


        document.getElementById(
            "tipoViaje"
        ).value = "idaVuelta";

    }


</script>


<script src="js/bootstrap.bundle.min.js"></script>


</body>

</html>