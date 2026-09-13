<?php

session_start();

ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);


// =========================
// CONEXIÓN
// =========================

include "conexionBD.php";


if ($_SERVER["REQUEST_METHOD"] == "POST") {

    $gmail = trim($_POST["gmail"]);


    // =========================
    // VALIDAR MAIL
    // =========================

    if (empty($gmail)) {

        header(
            "Location: ../registro.html"
        );

        exit();

    }


    // =========================
    // BUSCAR SI YA EXISTE
    // =========================

    $consulta = $conexion->prepare("
        SELECT codUsuario
        FROM Usuarios
        WHERE emailUsuario = ?
    ");

    $consulta->bind_param(
        "s",
        $gmail
    );

    $consulta->execute();

    $resultado =
        $consulta->get_result();


    // =========================
    // SI YA EXISTE
    // =========================

    if ($resultado->num_rows > 0) {

        $consulta->close();
        $conexion->close();

        header(
            "Location: ../registro.html"
            . "?error=usuario_existente"
            . "&gmail=" . urlencode($gmail)
        );

        exit();

    }


    // =========================
    // GUARDAR MAIL EN SESIÓN
    // =========================

    $_SESSION["gmailRegistro"] = $gmail;


    $consulta->close();
    $conexion->close();


    // =========================
    // IR A DATOS PERSONALES
    // =========================

    header(
        "Location: ../registroDatosPersonales.html"
    );

    exit();

}