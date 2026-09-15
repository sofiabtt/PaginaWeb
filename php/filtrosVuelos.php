<?php


// =====================================================
// DATOS RECIBIDOS DEL BUSCADOR
// =====================================================

$tipoViaje = $_GET["tipoViaje"] ?? "ida";

$origen = $_GET["origen"] ?? "";

$destino = $_GET["destino"] ?? "";

$fechaIda = $_GET["fecha"] ?? "";

$fechaVuelta = $_GET["fechaVuelta"] ?? "";


// =====================================================
// FILTROS
// =====================================================

$aerolinea = $_GET["aerolinea"] ?? "";

$precioMin = $_GET["precioMin"] ?? "";

$precioMax = $_GET["precioMax"] ?? "";

$horario = $_GET["horario"] ?? "";

$orden = $_GET["orden"] ?? "hora";


// =====================================================
// VALIDAR PRECIOS
// =====================================================

if (
    $precioMin !== "" &&
    (!is_numeric($precioMin) || $precioMin < 0)
) {

    $precioMin = "";

}


if (
    $precioMax !== "" &&
    (!is_numeric($precioMax) || $precioMax < 0)
) {

    $precioMax = "";

}


// =====================================================
// VUELOS POR PÁGINA
// =====================================================

$vuelosPorPagina = 5;


// =====================================================
// OBTENER AEROLÍNEAS
// =====================================================

$aerolineas = [];


$consultaAerolineas = "

    SELECT
        codAerolinea,
        nombreAerolinea

    FROM Aerolineas

    WHERE activoAerolinea = 1

    ORDER BY nombreAerolinea ASC

";


$resultadoAerolineas =
    $conexion->query(
        $consultaAerolineas
    );


while (
    $aerolineaBD =
    $resultadoAerolineas->fetch_assoc()
) {

    $aerolineas[] = $aerolineaBD;

}


// =====================================================
// FUNCIÓN PARA OBTENER VUELOS
// =====================================================

function obtenerVuelos(
    $conexion,
    $origenBusqueda,
    $destinoBusqueda,
    $fechaBusqueda,
    $aerolinea,
    $precioMin,
    $precioMax,
    $horario,
    $orden,
    $limite
) {


    $condiciones = "

        V.origenVuelo = ?

        AND V.destinoVuelo = ?

        AND V.fechaSalidaVuelo = ?

        AND V.activoVuelo = 1

        AND A.activoAerolinea = 1

    ";


    $tipos = "sss";


    $parametros = [

        $origenBusqueda,

        $destinoBusqueda,

        $fechaBusqueda

    ];


    // =================================================
    // FILTRO POR AEROLÍNEA
    // =================================================

    if (
        $aerolinea !== "" &&
        ctype_digit($aerolinea)
    ) {

        $condiciones .= "

            AND V.codAerolinea = ?

        ";


        $tipos .= "i";


        $parametros[] =
            (int) $aerolinea;

    }


    // =================================================
    // PRECIO MÍNIMO
    // =================================================

    if ($precioMin !== "") {

        $condiciones .= "

            AND V.precioVuelo >= ?

        ";


        $tipos .= "d";


        $parametros[] =
            (float) $precioMin;

    }


    // =================================================
    // PRECIO MÁXIMO
    // =================================================

    if ($precioMax !== "") {

        $condiciones .= "

            AND V.precioVuelo <= ?

        ";


        $tipos .= "d";


        $parametros[] =
            (float) $precioMax;

    }


    // =================================================
    // FILTRO POR HORARIO
    // =================================================

    if ($horario === "manana") {

        $condiciones .= "

            AND V.horaSalidaVuelo >= '06:00'

            AND V.horaSalidaVuelo < '12:00'

        ";

    }


    if ($horario === "tarde") {

        $condiciones .= "

            AND V.horaSalidaVuelo >= '12:00'

            AND V.horaSalidaVuelo < '18:00'

        ";

    }


    if ($horario === "noche") {

        $condiciones .= "

            AND V.horaSalidaVuelo >= '18:00'

            AND V.horaSalidaVuelo <= '23:59'

        ";

    }


    // =================================================
    // ORDEN
    // =================================================

    if ($orden === "precio_asc") {

        $ordenSQL =
            "V.precioVuelo ASC";

    } elseif ($orden === "precio_desc") {

        $ordenSQL =
            "V.precioVuelo DESC";

    } else {

        $ordenSQL =
            "V.horaSalidaVuelo ASC";

    }


    // =================================================
    // CONTAR VUELOS
    // =================================================

    $consultaCantidad = "

        SELECT
            COUNT(*) AS cantidad

        FROM Vuelos V

        INNER JOIN Aerolineas A

            ON V.codAerolinea =
               A.codAerolinea

        WHERE $condiciones

    ";


    $consultaCantidadPreparada =
        $conexion->prepare(
            $consultaCantidad
        );


    $parametrosCantidad = [];


    $parametrosCantidad[] =
        $tipos;


    foreach (
        $parametros as $indice => $valor
    ) {

        $parametrosCantidad[] =
            &$parametros[$indice];

    }


    call_user_func_array(

        [
            $consultaCantidadPreparada,
            "bind_param"
        ],

        $parametrosCantidad

    );


    $consultaCantidadPreparada->execute();


    $resultadoCantidad =
        $consultaCantidadPreparada->get_result();


    $datosCantidad =
        $resultadoCantidad->fetch_assoc();


    $cantidad =
        (int) $datosCantidad["cantidad"];


    $consultaCantidadPreparada->close();


    // =================================================
    // OBTENER VUELOS
    // =================================================

    $vuelos = [];


    if ($cantidad > 0) {


        $consulta = "

            SELECT

                V.codVuelo,

                V.origenVuelo,

                V.destinoVuelo,

                V.fechaSalidaVuelo,

                V.horaSalidaVuelo,

                V.precioVuelo,

                V.asientosDisponibles,

                A.nombreAerolinea


            FROM Vuelos V


            INNER JOIN Aerolineas A

                ON V.codAerolinea =
                   A.codAerolinea


            WHERE $condiciones


            ORDER BY $ordenSQL


            LIMIT ?

        ";


        $consultaPreparada =
            $conexion->prepare(
                $consulta
            );


        $tiposConsulta =
            $tipos . "i";


        $parametrosConsulta =
            $parametros;


        $parametrosConsulta[] =
            $limite;


        $parametrosBind = [];


        $parametrosBind[] =
            $tiposConsulta;


        foreach (
            $parametrosConsulta
            as $indice => $valor
        ) {

            $parametrosBind[] =
                &$parametrosConsulta[$indice];

        }


        call_user_func_array(

            [
                $consultaPreparada,
                "bind_param"
            ],

            $parametrosBind

        );


        $consultaPreparada->execute();


        $resultado =
            $consultaPreparada->get_result();


        while (
            $vuelo =
            $resultado->fetch_assoc()
        ) {

            $vuelos[] =
                $vuelo;

        }


        $consultaPreparada->close();

    }


    return [

        "cantidad" => $cantidad,

        "vuelos" => $vuelos

    ];

}


// =====================================================
// BUSCAR VUELOS DE IDA
// =====================================================

$resultadoIda = obtenerVuelos(

    $conexion,

    $origen,

    $destino,

    $fechaIda,

    $aerolinea,

    $precioMin,

    $precioMax,

    $horario,

    $orden,

    $vuelosPorPagina

);


$vuelosIda =
    $resultadoIda["vuelos"];


$cantidadIda =
    $resultadoIda["cantidad"];


// =====================================================
// BUSCAR VUELOS DE VUELTA
// =====================================================

$vuelosVuelta = [];

$cantidadVuelta = 0;


if (

    $tipoViaje === "idaVuelta"

    &&

    $fechaVuelta !== ""

) {


    $resultadoVuelta =
        obtenerVuelos(

            $conexion,

            $destino,

            $origen,

            $fechaVuelta,

            $aerolinea,

            $precioMin,

            $precioMax,

            $horario,

            $orden,

            $vuelosPorPagina

        );


    $vuelosVuelta =
        $resultadoVuelta["vuelos"];


    $cantidadVuelta =
        $resultadoVuelta["cantidad"];

}

?>