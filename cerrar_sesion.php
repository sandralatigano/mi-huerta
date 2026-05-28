<?php
/**
 * Archivo: cerrar_sesion.php
 * Descripción: Cierra la sesión del usuario y redirige al login.
 * Componente: Autenticación
 */ 
session_start();

// 1. Limpiamos todas las variables de sesión
$_SESSION = array();

// 2. Si se desea destruir la sesión completamente, también hay que borrar la cookie de sesión.
if (ini_get("session.use_cookies")) {
    $params = session_get_cookie_params();
    setcookie(session_name(), '', time() - 42000,
        $params["path"], $params["domain"],
        $params["secure"], $params["httponly"]
    );
}

// 3. Finalmente, destruimos la sesión en el servidor
session_destroy();

// 4. Redirigimos al login
header("Location: login.php");
exit();
?>