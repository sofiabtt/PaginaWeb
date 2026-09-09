<?php

include "../../php/conexionBD.php";

$error = "";

if ($_SERVER["REQUEST_METHOD"] == "POST") {

    $texto = $_POST["textoNovedad"];
    $fechaPublicacion = $_POST["fechaPublicacionNovedad"];
    $fechaExpiracion = $_POST["fechaExpiracionNovedad"];

    if (empty($texto) || empty($fechaPublicacion) || empty($fechaExpiracion)) {
        die("Todos los campos son obligatorios.");
    }

    if ($fechaExpiracion < $fechaPublicacion) {
        $error = "La fecha de expiración no puede ser anterior a la fecha de publicación.";
    }

    if (strtotime($fechaPublicacion) < strtotime(date("Y-m-d"))) {
        $error = "La fecha de publicación no puede ser anterior a la fecha actual.";
    }

    if (empty($error)) {

        $sql = "INSERT INTO Novedades
                (textoNovedad, fechaPublicacionNovedad, fechaExpiracionNovedad)
                VALUES (?, ?, ?)";

        $stmt = $conexion->prepare($sql);

        $stmt->bind_param(
            "sss",
            $texto,
            $fechaPublicacion,
            $fechaExpiracion
        );

        if ($stmt->execute()) {
            header("Location: gestionNovedades.php");
            exit;
        }
    }
}
?>