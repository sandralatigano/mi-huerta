<?php

/**
 * Archivo: guardar_planta.php
 * Descripción: Procesa el formulario de nueva planta y la guarda en la base de datos.
 * Componente: Gestión de Plantas
 */

include 'auth.php'; // 1. SEGURIDAD: Solo Sandra o usuarios logueados pueden guardar
include 'conexion.php';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    
    // 2. CAPTURA Y LIMPIEZA (Trim elimina espacios accidentales)
    $nombre   = trim($_POST['nombre']);        
    $variedad = trim($_POST['variedad']);

    // 3. VALIDACIÓN DE TIPOS (Aseguramos que sean números o NULL)
    // Nota: Usamos los nombres de los campos que pusimos en el formulario anterior
    $id_epoca        = (!empty($_POST['id_epoca'])) ? (int)$_POST['id_epoca'] : null;
    $id_tipo_siembra = (!empty($_POST['id_tipo_siembra'])) ? (int)$_POST['id_tipo_siembra'] : null;
    $distancia       = (!empty($_POST['distancia'])) ? (int)$_POST['distancia'] : 0;
    $id_bancal       = (!empty($_POST['id_bancal'])) ? (int)$_POST['id_bancal'] : null;

    // Validación mínima: Si no hay nombre, no guardamos
    if (empty($nombre)) {
        header("Location: nueva_planta.php?error=nombre_vacio");
        exit();
    }

    try {
        // 4. EL ESCUDO: Sentencia Preparada
        $sql = "INSERT INTO plantas (nombre, variedad, id_epoca, id_tipo_siembra, distancia, id_bancal, fecha_siembra) 
                VALUES (?, ?, ?, ?, ?, ?, ?)";
        
        $stmt = $conexion->prepare($sql);
        
        // Fecha de hoy para el registro
        $fecha_hoy = date('Y-m-d');

        // "ssiiiis" -> nombre(s), variedad(s), epoca(i), tipo(i), distancia(i), bancal(i), fecha(s)
        $stmt->bind_param("ssiiiis", 
            $nombre, 
            $variedad, 
            $id_epoca, 
            $id_tipo_siembra, 
            $distancia, 
            $id_bancal,
            $fecha_hoy
        );

        if ($stmt->execute()) {
            // 5. ÉXITO: Volvemos con bandera verde
            header("Location: index.php?status=success");
            exit();
        } else {
            // Error de ejecución (ej: problema de integridad)
            header("Location: nueva_planta.php?error=db");
            exit();
        }

    } catch (Exception $e) {
        // Error de sistema: No mostramos detalles técnicos al usuario
        header("Location: nueva_planta.php?error=sistema");
        exit();
    } finally {
        // Cerramos recursos si se alcanzaron a crear
        if (isset($stmt)) $stmt->close();
        if (isset($conexion)) $conexion->close();
    }

} else {
    // Si alguien intenta entrar por URL sin enviar el formulario
    header("Location: index.php");
    exit();
}
?>