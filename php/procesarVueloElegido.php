<?php

// 1. CALCULAR EDAD (Validación estricta y segura en PHP)
function calcularEdad($fechaNacimiento)
{
    if (empty($fechaNacimiento)) {
        return false;
    }

    try {
        // Validar formato real AAAA-MM-DD (evita fechas inexistentes como 31 de febrero)
        $nacimiento = DateTime::createFromFormat('Y-m-d', $fechaNacimiento);
        $hoy = new DateTime();

        if (!$nacimiento || $nacimiento->format('Y-m-d') !== $fechaNacimiento) {
            return false;
        }

        // Si la fecha es posterior a hoy (fecha futura), es inválida
        if ($nacimiento > $hoy) {
            return false;
        }

        $edad = $hoy->diff($nacimiento)->y;

        // No puede tener más de 115 años (fecha incoherente)
        if ($edad > 115) {
            return false;
        }

        return $edad;

    } catch (Exception $e) {
        return false;
    }
}


// RECUPERAR VALOR DE UN ARRAY
function valorArray($array, $indice)
{
    return htmlspecialchars($array[$indice] ?? "");
}


// 2. VALIDAR ADULTOS
function validarAdultos(
    $nombres,
    $apellidos,
    $emails,
    $fechas,
    $cantidad
) {
    for ($i = 0; $i < $cantidad; $i++) {
        $nombre   = trim($nombres[$i] ?? "");
        $apellido = trim($apellidos[$i] ?? "");
        $email    = trim($emails[$i] ?? "");
        $fecha    = trim($fechas[$i] ?? "");

        // Campos vacíos
        if ($nombre === "" || $apellido === "" || $email === "" || $fecha === "") {
            return "Completá todos los datos de los pasajeros adultos.";
        }

        // Email válido
        if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
            return "Ingresá un correo electrónico válido.";
        }

        // Validación de fecha y edad
        $edad = calcularEdad($fecha);

        if ($edad === false) {
            return "La fecha de nacimiento de uno de los adultos no es válida o es una fecha futura.";
        }

        // El pasajero titular (el primero) debe ser mayor de 18 años
        if ($i === 0 && $edad < 18) {
            return "El pasajero principal de la reserva debe ser mayor de 18 años.";
        }

        // Los adultos acompañantes deben tener al menos 12 años
        if ($edad < 12) {
            return "Uno de los pasajeros cargados como adulto tiene menos de 12 años.";
        }
    }

    return "";
}


// 3. VALIDAR MENORES
function validarMenores(
    $nombres,
    $apellidos,
    $emails,
    $fechas,
    $cantidad
) {
    for ($i = 0; $i < $cantidad; $i++) {
        $nombre   = trim($nombres[$i] ?? "");
        $apellido = trim($apellidos[$i] ?? "");
        $email    = trim($emails[$i] ?? "");
        $fecha    = trim($fechas[$i] ?? "");

        // Campos vacíos
        if ($nombre === "" || $apellido === "" || $email === "" || $fecha === "") {
            return "Completá todos los datos de los pasajeros menores.";
        }

        // Email
        if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
            return "Ingresá un correo electrónico válido.";
        }

        // Validación de fecha y edad
        $edad = calcularEdad($fecha);

        if ($edad === false) {
            return "La fecha de nacimiento de uno de los menores no es válida o es una fecha futura.";
        }

        // Menor de 12 años
        if ($edad >= 12) {
            return "Uno de los pasajeros cargados como menor tiene 12 años o más (debe cargarse como adulto).";
        }
    }

    return "";
}

// (La función guardarReservaTemporal la dejás tal cual como estaba abajo)

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