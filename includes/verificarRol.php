<?php
function verificarRol($rolPermitido) {

    if (session_status() === PHP_SESSION_NONE) {
        session_start();
    }

    if (
        !isset($_SESSION['codUsuario']) ||
        ($_SESSION['tipoUsuario'] ?? null) !== $rolPermitido
    ) {
        http_response_code(403);
        exit("No tenés permisos para realizar esta operación.");
    }
}