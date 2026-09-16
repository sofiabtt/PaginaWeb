<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);

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
    
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/flatpickr/dist/flatpickr.min.css">

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
                name="tipoViaje"
                id="ida-vuelta"
                value= "idaVuelta"
                checked
                >

            <label for="ida-vuelta">

                Ida y vuelta

            </label>



            <!-- SOLO IDA -->

            <input
                type="radio"
                name="tipoViaje"
                id="solo-ida"
                value="soloIda">

            <label for="solo-ida">

                Solo ida

            </label>


        </div>



        <!-- FORMULARIO DE BÚSQUEDA -->

        <form
            action="resultadosVuelos.php"
            method="GET">


            <!-- TIPO DE VIAJE -->
            <div id="buscador-ing-datos">

                <div class="row g-0">

                    <div class="col-md-3">

                        <input 
                            type="text"
                            class="form-control"
                            name="origen"
                            placeholder="Desde"
                            list="aeropuertos"
                            autocomplete="off"
                            required>

                    </div>


                    <div class="col-md-3">

                        <input 
                            type="text"
                            class="form-control"
                            name="destino"
                            placeholder="Hacia"
                            list="aeropuertos"
                            autocomplete="off"
                            required>

                    </div>


                    <div class="col-md-4">

                        <input
                            type="text"
                            id="fechaViaje"
                            class="form-control"
                            placeholder="Seleccioná fecha"
                            readonly>

                        <input
                            type="hidden"
                            id="fechaIda"
                            name="fechaIda">

                        <input
                            type="hidden"
                            id="fechaVuelta"
                            name="fechaVuelta">

                    </div>


                    <div class="col-md-2">

                        <button class="buscar">
                            →
                        </button>

                    </div>

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




<script src="https://cdn.jsdelivr.net/npm/flatpickr"></script>

<script>

const radioIdaVuelta = document.getElementById("ida-vuelta");
const radioSoloIda = document.getElementById("solo-ida");

const fechaIda = document.getElementById("fechaIda");
const fechaVuelta = document.getElementById("fechaVuelta");


let primeraFecha = null;


/* CALENDARIO */

const calendario = flatpickr("#fechaViaje", {

    mode: "range",

    dateFormat: "d/m/Y",

    rangeSeparator:" - ",

    minDate: "today",

    locale: {
        firstDayOfWeek: 1,
        weekdays: {
            shorthand: ["Dom", "Lun", "Mar", "Mié", "Jue", "Vie", "Sáb"],
            longhand: [
                "Domingo",
                "Lunes",
                "Martes",
                "Miércoles",
                "Jueves",
                "Viernes",
                "Sábado"
            ]
        },

        months: {
            shorthand: [
                "Ene",
                "Feb",
                "Mar",
                "Abr",
                "May",
                "Jun",
                "Jul",
                "Ago",
                "Sep",
                "Oct",
                "Nov",
                "Dic"
            ],

            longhand: [
                "Enero",
                "Febrero",
                "Marzo",
                "Abril",
                "Mayo",
                "Junio",
                "Julio",
                "Agosto",
                "Septiembre",
                "Octubre",
                "Noviembre",
                "Diciembre"
            ]
        }
    },


    onChange: function(selectedDates, dateStr, instance) {

        /* SOLO IDA */

        if (radioSoloIda.checked) {

            if (selectedDates.length >= 1) {

                fechaIda.value =
                    instance.formatDate(
                        selectedDates[0],
                        "Y-m-d"
                    );

                fechaVuelta.value = "";

                instance.close();

            }

            return;
        }


        /* IDA Y VUELTA */

        if (selectedDates.length === 1) {

            primeraFecha = selectedDates[0];

            fechaIda.value =
                instance.formatDate(
                    primeraFecha,
                    "Y-m-d"
                );

            fechaVuelta.value = "";

        }


        if (selectedDates.length === 2) {

            const ida = selectedDates[0];
            const vuelta = selectedDates[1];


            fechaIda.value =
                instance.formatDate(
                    ida,
                    "Y-m-d"
                );

            fechaVuelta.value =
                instance.formatDate(
                    vuelta,
                    "Y-m-d"
                );


            instance.close();

        }

    }

});


/* CAMBIAR A SOLO IDA */

radioSoloIda.addEventListener("change", function(){
 
    primeraFecha = null;

    calendario.clear();

    calendario.set("mode", "single");

    document.getElementById("fechaViaje").placeholder =
        "Seleccioná fecha de ida";

    fechaIda.value = "";
    fechaVuelta.value = "";

});


/* CAMBIAR A IDA Y VUELTA */

radioIdaVuelta.addEventListener("change", function(){


    primeraFecha = null;

    calendario.clear();

    calendario.set("mode", "range");

    document.getElementById("fechaViaje").placeholder =
        "Seleccioná ida y vuelta";

    fechaIda.value = "";
    fechaVuelta.value = "";

});

</script>


<script src="js/bootstrap.bundle.min.js"></script>


</body>

</html>