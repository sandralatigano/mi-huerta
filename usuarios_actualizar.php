<?php

/**
 * Archivo: usuarios_actualizar.php
 * Descripción: Procesa la actualización de un usuario existente.
 * Componente: Gestión de Usuarios
 */

include 'auth.php';
include 'conexion.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Recibimos los datos del formulario
    $id = intval($_POST['id']);
    $user = $_POST['nombre_usuario'];
    $nombre = $_POST['nombre_real'];
    $rol = intval($_POST['id_rol']);
    $pass = $_POST['password'];

    // Verificamos si se ingresó una nueva contraseña
    if (!empty($pass)) {
        // Escenario A: El usuario quiere cambiar la contraseña
        $pass_encriptada = password_hash($pass, PASSWORD_DEFAULT);
        
        $sql = "UPDATE usuarios SET nombre_usuario=?, password=?, nombre_real=?, id_rol=? WHERE id=?";
        $stmt = $conexion->prepare($sql);
        $stmt->bind_param("sssii", $user, $pass_encriptada, $nombre, $rol, $id);
    } else {
        // Escenario B: El usuario NO quiere tocar la contraseña actual
        $sql = "UPDATE usuarios SET nombre_usuario=?, nombre_real=?, id_rol=? WHERE id=?";
        $stmt = $conexion->prepare($sql);
        $stmt->bind_param("ssii", $user, $nombre, $rol, $id);
    }

    // Ejecutamos la actualización
    if ($stmt->execute()) {
        // Redirigimos con un mensaje de éxito
        header("Location: usuarios_gestion.php?status=updated");
    } else {
        // En caso de error (ej: nombre de usuario ya existe)
        echo "<div style='font-family: sans-serif; padding: 20px; color: red;'>";
        echo "<h4>Error al actualizar:</h4>" . $conexion->error;
        echo "<br><br><a href='usuarios_gestion.php'>Volver a gestión</a>";
        echo "</div>";
    }

    $stmt->close();
    $conexion->close();
} else {
    // Si alguien intenta entrar a este archivo directamente sin pasar por el formulario
    header("Location: usuarios_gestion.php");
    exit();
}
?>