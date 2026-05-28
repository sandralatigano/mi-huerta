<?php
/**
 * Archivo: auth.php
 * Descripción: Maneja la autenticación y control de sesión del usuario.
 * Componente: Autenticación
 */ 
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

// 1. EL BLOQUEO PRINCIPAL: Si no hay ID, al login
if (!isset($_SESSION['usuario_id'])) {
    header("Location: login.php");
    exit();
}

// 2. CONTROL DE INACTIVIDAD (Blindaje extra)
$tiempo_maximo = 1800; // 30 minutos en segundos
if (isset($_SESSION['ultimo_acceso']) && (time() - $_SESSION['ultimo_acceso'] > $tiempo_maximo)) {
    session_unset();
    session_destroy();
    header("Location: login.php?error=sesion_expirada");
    exit();
}
$_SESSION['ultimo_acceso'] = time(); // Actualizamos el reloj en cada clic

// 3. DATOS DE IDENTIDAD (Seguros con valores por defecto)
$mi_rol = $_SESSION['id_rol'] ?? 3; 
$mi_nombre = $_SESSION['nombre_real'] ?? 'Usuario';

/**
 * TIP DE SEGURIDAD:
 * Siempre que uses $mi_nombre en el HTML (como en la barra de navegación),
 * recordá usar htmlspecialchars($mi_nombre) para evitar XSS.
 */
?>