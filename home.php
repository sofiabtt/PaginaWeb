<?php session_start(); ?>
<!DOCTYPE html>
<html lang="es">

<head>

    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <title>Nuvia</title>
    <link rel="icon" href="imagenes/logo.png" type="image/png">

    <!-- Bootstrap -->
    <link rel="stylesheet" href="css/bootstrap.min.css">
    <link rel="stylesheet" href="css/bootstrap-icons.css">
    <link rel="stylesheet" href="css/estiloshome.css?v=2">
    <link rel="stylesheet" href="css/footer.css">
    <link rel="stylesheet" href="css/navbar.css">

</head>


<body>

<?php include("includes/navbar.php"); ?>

<section class="hero">
    <!-- BUSCADOR -->


    
    <div class="contenedor-buscador">
        <div class="tipo-viaje">
            
            <p class="titulo-buscador">
                Encontrá el <strong>vuelo ideal</strong> para tu próximo viaje
            </p>
            <input 
            type="radio"
            name="viaje"
            checked
            onclick="mostrarVuelta()">

            
            <label>

                Ida y vuelta

            </label> 


            <input 
            type="radio"
            name="viaje"
            onclick="ocultarVuelta()">



            <label>

                Solo ida

            </label>



        </div>
        <div id ="buscador-ing-datos">
            <div class="row g-0">

                <div class="col-md-3">


                    <input 
                    class="form-control"
                    placeholder="Desde">


                </div>

                <div class="col-md-3">


                    <input 
                    class="form-control"
                    placeholder="Hacia">


                </div>


                <div class="col-md-2" id="fecha-ida">


                    <input 
                    type="date"
                    class="form-control">


                </div>


                <div class="col-md-2"
                id="fecha-vuelta">


                    <input 
                    type="date"
                    class="form-control">


                </div>


                <div class="col-md-2">


                    <button class="buscar">


                        →

                    </button>


                </div>


            </div>

        </div>
        <br>

    </div>

    


    <section class="destinos-destacados">
        <h4 class="titulo-destino">
            Viaja por el mundo
        </h4>

        <div class="contenedor-tarjetas">
            <div class="tarjeta-destino">
                <img src="imagenes/brasil.jpg" alt="Destino 1">
                <h4>Brasil</h4>
            </div>

            <div class="tarjeta-destino">
                <img src="imagenes/bsas.jpg" alt="Destino 2">
                <h4>Buenos Aires</h4>
            </div>

            <div class="tarjeta-destino">
                <img src="imagenes/madrid.jpg" alt="Destino 3">
                <h4>Madrid</h4>
            </div>

            <div class="tarjeta-destino">
                <img src="imagenes/roma.jpg" alt="Destino 4">
                <h4>Roma</h4>
            </div>
        </div>

    </section>

    
    


</section>

<?php include("includes/footer.php"); ?>


<script>


    function ocultarVuelta(){

    document.getElementById("fecha-vuelta").style.display="none";

    document.getElementById("fecha-ida").className="col-md-4";

}



function mostrarVuelta(){

    document.getElementById("fecha-vuelta").style.display="block";

    document.getElementById("fecha-ida").className="col-md-2";

}


</script>

<script src="js/bootstrap.bundle.min.js"></script>

</body>

</html>
