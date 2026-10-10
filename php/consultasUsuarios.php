<?php
// Archivo: PaginaWeb/php/consultasUsuarios.php

include_once __DIR__ . "/conexionBD.php";

// ==========================================
// 1. FUNCIONES PARA EL PANEL DE ADMINISTRADOR
// ==========================================

/**
 * Cantidad total de usuarios para el panel de Admin
 */
function cantidadUsuarios($conexion)
{
    $sql = "SELECT COUNT(*) AS total FROM Usuarios";
    $res = mysqli_query($conexion, $sql);
    if (!$res) {
        $sql = "SELECT COUNT(*) AS total FROM usuarios";
        $res = mysqli_query($conexion, $sql);
    }
    if ($res && ($fila = mysqli_fetch_assoc($res))) {
        return (int)$fila['total'];
    }
    return 0;
}

/**
 * Cantidad de usuarios filtrados para la paginación de reportes
 */
function cantidadUsuariosReporte($conexion, $rol = '', $busqueda = '')
{
    $where = ["1=1"];

    if ($rol !== '') {
        if ($rol === 'usuario' || $rol === 'cliente') {
            $where[] = "tipoUsuario IN ('usuario', 'cliente')";
        } else {
            $rolEsc = mysqli_real_escape_string($conexion, $rol);
            $where[] = "tipoUsuario = '$rolEsc'";
        }
    }

    if ($busqueda !== '') {
        $busqEsc = mysqli_real_escape_string($conexion, $busqueda);
        $where[] = "(nombreUsuario LIKE '%$busqEsc%' OR emailUsuario LIKE '%$busqEsc%')";
    }

    $whereSql = implode(" AND ", $where);
    
    $sql = "SELECT COUNT(*) AS total FROM Usuarios WHERE $whereSql";
    $res = mysqli_query($conexion, $sql);
    if (!$res) {
        $sql = "SELECT COUNT(*) AS total FROM usuarios WHERE $whereSql";
        $res = mysqli_query($conexion, $sql);
    }

    if ($res && ($fila = mysqli_fetch_assoc($res))) {
        return (int)$fila['total'];
    }
    return 0;
}

/**
 * Obtener usuarios paginados y filtrados para la tabla de reportes
 */
function obtenerUsuariosReporte($conexion, $porPagina, $inicio, $rol = '', $busqueda = '')
{
    $where = ["1=1"];

    if ($rol !== '') {
        if ($rol === 'usuario' || $rol === 'cliente') {
            $where[] = "tipoUsuario IN ('usuario', 'cliente')";
        } else {
            $rolEsc = mysqli_real_escape_string($conexion, $rol);
            $where[] = "tipoUsuario = '$rolEsc'";
        }
    }

    if ($busqueda !== '') {
        $busqEsc = mysqli_real_escape_string($conexion, $busqueda);
        $where[] = "(nombreUsuario LIKE '%$busqEsc%' OR emailUsuario LIKE '%$busqEsc%')";
    }

    $whereSql = implode(" AND ", $where);

    $sql = "SELECT codUsuario,
                   nombreUsuario,
                   emailUsuario,
                   telefonoUsuario,
                   tipoUsuario,
                   verificado
            FROM Usuarios
            WHERE $whereSql
            ORDER BY codUsuario DESC
            LIMIT $inicio, $porPagina";

    $res = mysqli_query($conexion, $sql);
    if (!$res) {
        $sql = "SELECT codUsuario,
                       nombreUsuario,
                       emailUsuario,
                       telefonoUsuario,
                       tipoUsuario,
                       verificado
                FROM usuarios
                WHERE $whereSql
                ORDER BY codUsuario DESC
                LIMIT $inicio, $porPagina";
        $res = mysqli_query($conexion, $sql);
    }

    return $res;
}

// ==========================================
// 2. FUNCIONES DE LOGIN Y AUTENTICACIÓN
// ==========================================

/**
 * Obtener usuario por email para inicio de sesión (Usada en validarContra.php)
 */
function obtenerUsuarioPorEmail($conexion, $email)
{
    $consulta = $conexion->prepare(
        "SELECT codUsuario,
                nombreUsuario,
                claveUsuario,
                tipoUsuario,
                verificado
         FROM Usuarios
         WHERE emailUsuario = ?"
    );

    if (!$consulta) {
        $consulta = $conexion->prepare(
            "SELECT codUsuario,
                    nombreUsuario,
                    claveUsuario,
                    tipoUsuario,
                    verificado
             FROM usuarios
             WHERE emailUsuario = ?"
        );
    }

    if (!$consulta) {
        return null;
    }

    $consulta->bind_param("s", $email);
    $consulta->execute();
    $resultado = $consulta->get_result();

    return $resultado ? $resultado->fetch_assoc() : null;
}

// ==========================================
// 3. FUNCIONES DE PERFIL Y EDICIÓN DE USUARIO
// ==========================================

function obtenerUsuario($conexion, $codUsuario)
{
    $consulta = $conexion->prepare(
        "SELECT nombreUsuario,
                emailUsuario,
                telefonoUsuario
         FROM Usuarios
         WHERE codUsuario = ?"
    );

    if (!$consulta) {
        $consulta = $conexion->prepare(
            "SELECT nombreUsuario,
                    emailUsuario,
                    telefonoUsuario
             FROM usuarios
             WHERE codUsuario = ?"
        );
    }

    if (!$consulta) return null;

    $consulta->bind_param("i", $codUsuario);
    $consulta->execute();
    $resultado = $consulta->get_result();

    return $resultado ? $resultado->fetch_assoc() : null;
}

function modificarUsuario($conexion, $codUsuario, $nombre, $email, $telefono)
{
    $consulta = "UPDATE Usuarios
                 SET nombreUsuario = ?,
                     emailUsuario = ?,
                     telefonoUsuario = ?
                 WHERE codUsuario = ?";

    $stmt = $conexion->prepare($consulta);
    if (!$stmt) {
        $consulta = "UPDATE usuarios
                     SET nombreUsuario = ?,
                         emailUsuario = ?,
                         telefonoUsuario = ?
                     WHERE codUsuario = ?";
        $stmt = $conexion->prepare($consulta);
    }

    if (!$stmt) return false;

    $stmt->bind_param("sssi", $nombre, $email, $telefono, $codUsuario);
    return $stmt->execute();
}

function modificarContraseñaUsuario($conexion, $codUsuario, $claveNueva)
{
    $hash = password_hash($claveNueva, PASSWORD_DEFAULT);

    $sql = "UPDATE Usuarios SET claveUsuario = ? WHERE codUsuario = ?";
    $consulta = mysqli_prepare($conexion, $sql);
    if (!$consulta) {
        $sql = "UPDATE usuarios SET claveUsuario = ? WHERE codUsuario = ?";
        $consulta = mysqli_prepare($conexion, $sql);
    }

    if (!$consulta) {
        return false;
    }

    mysqli_stmt_bind_param($consulta, "si", $hash, $codUsuario);
    $resultado = mysqli_stmt_execute($consulta);
    mysqli_stmt_close($consulta);

    return $resultado;
}

function obtenerUsuariosPaginados($conexion, $porPagina, $inicio)
{
    $consulta = $conexion->prepare(
        "SELECT codUsuario,
                nombreUsuario,
                emailUsuario,
                telefonoUsuario,
                verificado
         FROM Usuarios
         WHERE tipoUsuario = 'usuario'
         ORDER BY codUsuario DESC
         LIMIT ? OFFSET ?"
    );

    if (!$consulta) {
        $consulta = $conexion->prepare(
            "SELECT codUsuario,
                    nombreUsuario,
                    emailUsuario,
                    telefonoUsuario,
                    verificado
             FROM usuarios
             WHERE tipoUsuario = 'usuario'
             ORDER BY codUsuario DESC
             LIMIT ? OFFSET ?"
        );
    }

    if (!$consulta) return false;

    $consulta->bind_param("ii", $porPagina, $inicio);
    $consulta->execute();

    return $consulta->get_result();
}

function obtenerPerfilUsuario($conexion, $codUsuario)
{
    $consulta = $conexion->prepare(
        "SELECT nombreUsuario,
                emailUsuario,
                telefonoUsuario
         FROM Usuarios
         WHERE codUsuario = ?
           AND tipoUsuario = 'usuario'"
    );

    if (!$consulta) {
        $consulta = $conexion->prepare(
            "SELECT nombreUsuario,
                    emailUsuario,
                    telefonoUsuario
             FROM usuarios
             WHERE codUsuario = ?
               AND tipoUsuario = 'usuario'"
        );
    }

    if (!$consulta) return null;

    $consulta->bind_param("i", $codUsuario);
    $consulta->execute();
    $resultado = $consulta->get_result();

    return $resultado ? $resultado->fetch_assoc() : null;
}

function actualizarPerfilUsuario($conexion, $codUsuario, $nombre, $email, $telefono, $claveNueva)
{
    if ($claveNueva !== "") {
        $hash = password_hash($claveNueva, PASSWORD_DEFAULT);
        $actualizar = $conexion->prepare(
            "UPDATE Usuarios
             SET nombreUsuario = ?,
                 emailUsuario = ?,
                 telefonoUsuario = ?,
                 claveUsuario = ?
             WHERE codUsuario = ?
               AND tipoUsuario = 'usuario'"
        );
        if (!$actualizar) {
            $actualizar = $conexion->prepare(
                "UPDATE usuarios
                 SET nombreUsuario = ?,
                     emailUsuario = ?,
                     telefonoUsuario = ?,
                     claveUsuario = ?
                 WHERE codUsuario = ?
                   AND tipoUsuario = 'usuario'"
            );
        }
        if (!$actualizar) return false;

        $actualizar->bind_param("ssssi", $nombre, $email, $telefono, $hash, $codUsuario);
    } else {
        $actualizar = $conexion->prepare(
            "UPDATE Usuarios
             SET nombreUsuario = ?,
                 emailUsuario = ?,
                 telefonoUsuario = ?
             WHERE codUsuario = ?
               AND tipoUsuario = 'usuario'"
        );
        if (!$actualizar) {
            $actualizar = $conexion->prepare(
                "UPDATE usuarios
                 SET nombreUsuario = ?,
                     emailUsuario = ?,
                     telefonoUsuario = ?
                 WHERE codUsuario = ?
                   AND tipoUsuario = 'usuario'"
            );
        }
        if (!$actualizar) return false;

        $actualizar->bind_param("sssi", $nombre, $email, $telefono, $codUsuario);
    }

    return $actualizar->execute();
}