<?php

include "conexionBD.php";


// REGISTRAR ACTIVIDAD

function registrarActividad($conexion, $usuarioActividad, $accionActividad)
{
    $consulta = "INSERT INTO Actividad
                 (usuarioActividad, accionActividad)
                 VALUES (?, ?)";

    $stmt = $conexion->prepare($consulta);

    $stmt->bind_param(
        "ss",
        $usuarioActividad,
        $accionActividad
    );

    return $stmt->execute();
}


// OBTENER ACTIVIDADES RECIENTES

function obtenerActividadesRecientes($conexion)
{
    $consulta = "SELECT *
                 FROM Actividad
                 ORDER BY fechaActividad DESC
                 LIMIT 10";

    return $conexion->query($consulta);
}