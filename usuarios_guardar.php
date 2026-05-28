<?php

/**
 * Archivo: usuarios_guardar.php
 * Descripción: Procesa la creación de un nuevo usuario del sistema.
 * Componente: Gestión de Usuarios
 */

include 'auth.php';
include 'conexion.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $user = $_POST['nombre_usuario'];
    $pass = $_POST['password'];
    $nombre = $_POST['nombre_real'];
    $rol = $_POST['id_rol'];

    // Encriptamos la contraseña por seguridad
    $pass_encriptada = password_hash($pass, PASSWORD_DEFAULT);

    // Corregimos: 'fecha_alta' es el nombre real en tu DB y usamos NOW() para la fecha actual
    $stmt = $conexion->prepare("INSERT INTO usuarios (nombre_usuario, password, nombre_real, id_rol, fecha_alta) VALUES (?, ?, ?, ?, NOW())");
    
    // El bind_param lleva "sssi" (string, string, string, integer)
    $stmt->bind_param("sssi", $user, $pass_encriptada, $nombre, $rol);

    if ($stmt->execute()) {
        header("Location: usuarios_gestion.php?status=success");
    } else {
        echo "Error al crear el usuario: " . $stmt->error;
    }
    
    $stmt->close();
}
$conexion->close();
?>