<?php
/**
 * Archivo: validar_login.php
 * Descripción: Procesa las credenciales del usuario para autenticación segura.
 * Componente: Autenticación
 */ 
session_start();
include 'conexion.php';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $user = trim($_POST['usuario']);
    $pass = $_POST['password'];

    try {
        // 1. Buscamos al usuario con sentencia preparada (Blindaje SQLi)
        $stmt = $conexion->prepare("SELECT id, nombre_usuario, password, nombre_real, id_rol FROM usuarios WHERE nombre_usuario = ?");
        $stmt->bind_param("s", $user);
        $stmt->execute();
        $resultado = $stmt->get_result();

        if ($row = $resultado->fetch_assoc()) {
            // 2. Verificamos la contraseña encriptada
            if (password_verify($pass, $row['password'])) {
                
                // 3. Seguridad de Sesión: Nueva llave segura
                session_regenerate_id(true); 
                
                $_SESSION['usuario_id'] = $row['id'];
                $_SESSION['nombre_usuario'] = $row['nombre_usuario'];
                $_SESSION['nombre_real'] = $row['nombre_real']; 
                $_SESSION['id_rol'] = $row['id_rol']; 
                
                // Inicializamos el reloj para el control de inactividad de auth.php
                $_SESSION['ultimo_acceso'] = time(); 
                
                header("Location: index.php");
                exit();
            }
        }
        
        // 4. Si falló el usuario O la contraseña, mandamos el mismo error
        // Esto evita dar pistas sobre qué dato fue el que falló
        header("Location: login.php?error=credenciales");
        exit();

    } catch (Exception $e) {
        // Error de base de datos silencioso
        header("Location: login.php?error=sistema");
        exit();
    } finally {
        if (isset($stmt)) $stmt->close();
        if (isset($conexion)) $conexion->close();
    }
} else {
    header("Location: login.php");
    exit();
}
?>