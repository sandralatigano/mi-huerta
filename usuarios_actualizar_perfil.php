<?php

/**
 * Archivo: usuarios_actualizar_perfil.php
 * Descripción: Procesa la actualización del perfil del usuario logueado.
 * Componente: Gestión de Usuarios
 */

include 'auth.php';
include 'conexion.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $id_usuario = $_SESSION['usuario_id']; 
    $nombre_real = $_POST['nombre_real'];
    $password_current = $_POST['password_current']; // Campo obligatorio para validar identidad
    $pass_new = $_POST['password_new']; // Nombre correcto según el formulario
    $pass_confirm = $_POST['password_confirm'];

    // 1. PRIMERO: Verificar que la contraseña actual sea la correcta
    $check = $conexion->prepare("SELECT password FROM usuarios WHERE id = ?");
    $check->bind_param("i", $id_usuario);
    $check->execute();
    $res_check = $check->get_result()->fetch_assoc();

    
    if (!password_verify($password_current, $res_check['password'])) {
        $check->close(); // Cerramos el primer statement
        $conexion->close(); // Cerramos la conexión
        header("Location: usuarios_perfil.php?error=wrong_current");
        exit();
    }

    // 2. SEGUNDO: Lógica para actualizar nombre y/o contraseña
    if (!empty($pass_new)) {
        // Validar coincidencia y longitud
        if ($pass_new !== $pass_confirm) {
            header("Location: usuarios_perfil.php?error=password_mismatch");
            exit();
        }
        if (strlen($pass_new) < 6) {
            header("Location: usuarios_perfil.php?error=password_short");
            exit();
        }

        // Encriptar nueva contraseña y actualizar todo
        $pass_encriptada = password_hash($pass_new, PASSWORD_DEFAULT);
        $sql = "UPDATE usuarios SET nombre_real = ?, password = ? WHERE id = ?";
        $stmt = $conexion->prepare($sql);
        $stmt->bind_param("ssi", $nombre_real, $pass_encriptada, $id_usuario);
    } else {
        // Solo actualizar el nombre real
        $sql = "UPDATE usuarios SET nombre_real = ? WHERE id = ?";
        $stmt = $conexion->prepare($sql);
        $stmt->bind_param("si", $nombre_real, $id_usuario);
    }

    // 3. TERCERO: Ejecutar y redireccionar
    if ($stmt->execute()) {
        $_SESSION['nombre_real'] = $nombre_real; // Actualiza el nombre en la barra de navegación al instante
        header("Location: usuarios_perfil.php?status=perfil_updated");
    } else {
        header("Location: usuarios_perfil.php?error=db_error");
        exit();
    }

    $stmt->close();
    $conexion->close();
} else {
    header("Location: usuarios_perfil.php");
}
?>