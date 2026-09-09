<?php 
session_start();
$_SESSION = array();

//elimina las cookies en caso que el navegador las utilice 
if (ini_get("session.use_cookies")) {
    $parametros = session_get_cookie_params();
    setcookie(
        session_name(), 
        "",
        time() - 42000,
        $parametros["path"], 
        $parametros["domain"],
        $parametros["secure"],
        $parametros["httponly"],
    );
}

session_destroy();

header("Location: ../home.php");
exit();
?>