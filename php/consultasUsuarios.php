<?php
// Archivo: PaginaWeb/php/consultasUsuarios.php
include_once __DIR__ . "/conexionBD.php";

/**
 * Cantidad total de usuarios para el panel de Admin
 */
function cantidadUsuarios($conexion)
{
    $sql = "SELECT COUNT(*) AS total FROM Usuarios";
    $res = mysqli_query($conexion, $sql);
    if ($res && ($fila = mysqli_fetch_assoc($res))) {
        return (int)$fila['total'];
    }
    return 0;
}

/**
 * Cantidad de usuarios filtrados para la paginación
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

    if ($res && ($fila = mysqli_fetch_assoc($res))) {
        return (int)$fila['total'];
    }
    return 0;
}

/**
 * Obtener usuarios paginados y filtrados para la tabla
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

    return mysqli_query($conexion, $sql);
}