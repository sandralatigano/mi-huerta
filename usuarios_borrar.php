<?php

/**
 * Archivo: usuarios_borrar.php
 * Descripción: Procesa la eliminación de un usuario del sistema.
 * Componente: Gestión de Usuarios
 */

include 'auth.php'; // Esto inicia la sesión y verifica quién sos
include 'conexion.php';

// 1. Capturamos los datos que vienen de la tabla
$id_a_borrar = isset($_GET['id']) ? intval($_GET['id']) : null;
$motivo = isset($_GET['motivo']) ? $_GET['motivo'] : 'Sin observaciones';

// 2. IMPORTANTE: Usamos el nombre exacto de tu sesión (usuario_id)
$realizado_por = $_SESSION['usuario_id'] ?? null; 

if ($id_a_borrar && $realizado_por) {
    
    // 3. Buscamos los datos del usuario antes de borrarlo
    $consulta = $conexion->prepare("SELECT nombre_usuario, nombre_real, fecha_alta FROM usuarios WHERE id = ?");
    $consulta->bind_param("i", $id_a_borrar);
    $consulta->execute();
    $u = $consulta->get_result()->fetch_assoc();

    if ($u) {
        // Desactivamos llaves un segundo para que no haya errores de restricción
        $conexion->query("SET FOREIGN_KEY_CHECKS = 0");

        // 4. Insertamos en el historial (usando tus nombres de columna)
        $sql_historial = "INSERT INTO historial_usuarios 
            (nombre_usuario, nombre_real, observaciones, realizado_por, fecha_alta_original, fecha_baja) 
            VALUES (?, ?, ?, ?, ?, NOW())";
        
        $stmt_hist = $conexion->prepare($sql_historial);
        // "sss i s" -> usuario, nombre, observaciones, ID del que borra (int), fecha alta
        $stmt_hist->bind_param("sssis", 
            $u['nombre_usuario'], 
            $u['nombre_real'], 
            $motivo, 
            $realizado_por, 
            $u['fecha_alta']
        );

        if ($stmt_hist->execute()) {
            // 5. Ahora sí, borramos al usuario de la tabla principal
            $stmt_borrar = $conexion->prepare("DELETE FROM usuarios WHERE id = ?");
            $stmt_borrar->bind_param("i", $id_a_borrar);
            
            if ($stmt_borrar->execute()) {
                $conexion->query("SET FOREIGN_KEY_CHECKS = 1");
                
                // Redirigimos con "status=deleted" para que tu usuarios_gestion lo reconozca
                header("Location: usuarios_gestion.php?status=deleted");
                exit();
            }
        }
    }
}

// Si falla, volvemos con error
header("Location: usuarios_gestion.php?error=no_se_pudo_borrar");
exit();
?>