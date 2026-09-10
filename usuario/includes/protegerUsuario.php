<?php

if (session_status() !== PHP_SESSION_ACTIVE) {
    session_start();
}

if (
    !isset($_SESSION["codUsuario"], $_SESSION["tipoUsuario"])
    || $_SESSION["tipoUsuario"] !== "usuario"
) {
    header("Location: /PaginaWeb/inicioSesion.php");
    exit();
}

