<?php


// CALCULAR EDAD

function calcularEdad($fechaNacimiento)
{
    try {

        $nacimiento = new DateTime($fechaNacimiento);
        $hoy = new DateTime();

        if ($nacimiento > $hoy) {
            return false;
        }

        return $hoy->diff($nacimiento)->y;

    } catch (Exception $e) {

        return false;
    }
}


// RECUPERAR VALOR DE UN ARRAY

function valorArray($array, $indice)
{
    return htmlspecialchars(
        $array[$indice] ?? ""
    );
}


// VALIDAR ADULTOS

function validarAdultos(
    $nombres,
    $apellidos,
    $emails,
    $fechas,
    $cantidad
) {

    for ($i = 0; $i < $cantidad; $i++) {

        $nombre = trim($nombres[$i] ?? "");
        $apellido = trim($apellidos[$i] ?? "");
        $email = trim($emails[$i] ?? "");
        $fecha = $fechas[$i] ?? "";


        // CAMPOS VACÍOS

        if (
            $nombre === "" ||
            $apellido === "" ||
            $email === "" ||
            $fecha === ""
        ) {

            return "Completá todos los datos de los pasajeros.";
        }


        // EMAIL

        if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {

            return "Ingresá un correo electrónico válido.";
        }


        // EDAD

        $edad = calcularEdad($fecha);

        if ($edad === false) {

            return "Ingresá una fecha de nacimiento válida.";
        }


        // DEBE SER ADULTO

        if ($edad < 12) {

            return "Uno de los pasajeros cargados como adulto tiene menos de 12 años.";
        }
    }


    return "";
}


// VALIDAR MENORES

function validarMenores(
    $nombres,
    $apellidos,
    $emails,
    $fechas,
    $cantidad
) {

    for ($i = 0; $i < $cantidad; $i++) {

        $nombre = trim($nombres[$i] ?? "");
        $apellido = trim($apellidos[$i] ?? "");
        $email = trim($emails[$i] ?? "");
        $fecha = $fechas[$i] ?? "";


        // CAMPOS VACÍOS

        if (
            $nombre === "" ||
            $apellido === "" ||
            $email === "" ||
            $fecha === ""
        ) {

            return "Completá todos los datos de los pasajeros.";
        }


        // EMAIL

        if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {

            return "Ingresá un correo electrónico válido.";
        }


        // EDAD

        $edad = calcularEdad($fecha);

        if ($edad === false) {

            return "Ingresá una fecha de nacimiento válida.";
        }


        // DEBE SER MENOR

        if ($edad >= 12) {

            return "Uno de los pasajeros cargados como menor tiene 12 años o más.";
        }
    }


    return "";
}


// GUARDAR RESERVA TEMPORAL

function guardarReservaTemporal(
    $codVuelo,
    $adultos,
    $menores,
    $tipoViaje,
    $origen,
    $destino,
    $fechaIda,
    $fechaVuelta,
    $tramo,
    $reservaIda
) {

    $_SESSION["reservaTemporal"] = [

        "codVuelo" => $codVuelo,

        "adultos" => $adultos,

        "menores" => $menores,

        "nombreAdulto" =>
            $_POST["nombreAdulto"] ?? [],

        "apellidoAdulto" =>
            $_POST["apellidoAdulto"] ?? [],

        "emailAdulto" =>
            $_POST["emailAdulto"] ?? [],

        "fechaAdulto" =>
            $_POST["fechaAdulto"] ?? [],

        "nombreMenor" =>
            $_POST["nombreMenor"] ?? [],

        "apellidoMenor" =>
            $_POST["apellidoMenor"] ?? [],

        "emailMenor" =>
            $_POST["emailMenor"] ?? [],

        "fechaMenor" =>
            $_POST["fechaMenor"] ?? [],

        "tipoViaje" => $tipoViaje,

        "origen" => $origen,

        "destino" => $destino,

        "fechaIda" => $fechaIda,

        "fechaVuelta" => $fechaVuelta,

        "tramo" => $tramo,

        "reservaIda" => $reservaIda

    ];
}