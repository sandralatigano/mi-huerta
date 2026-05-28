<?php

/**
 * Archivo: borrar_planta.php
 * Descripción: Elimina una planta y su historial de seguimiento de la base de datos.
 * Componente: Gestión de Plantas
 */

// 1. FUNDAMENTAL: Solo usuarios logueados
include 'auth.php'; 
include 'conexion.php';

// 2. OPCIONAL: Solo permitir borrar si es administrador (supongamos que id_rol 1 es Admin)
/*
if ($_SESSION['id_rol'] != 1) {
    header("Location: index.php?error=no_permiso");
    exit();
}
*/

if (isset($_GET['id'])) {
    $id = intval($_GET['id']);

    $conexion->begin_transaction();

    try {
        // Borramos el seguimiento
        $stmt_seg = $conexion->prepare("DELETE FROM seguimiento WHERE id_planta = ?");
        $stmt_seg->bind_param("i", $id);
        $stmt_seg->execute();

        // Borramos la planta
        $stmt_plan = $conexion->prepare("DELETE FROM plantas WHERE id = ?");
        $stmt_plan->bind_param("i", $id);
        $stmt_plan->execute();

        $conexion->commit();
        
        header("Location: index.php?status=deleted");
        exit(); // Siempre poner exit después de un header Location

    } catch (Exception $e) {
        $conexion->rollback();
        // Redirigimos con error pero sin dar detalles técnicos
        header("Location: index.php?error=db_error");
        exit();
    }
    
    // El cierre de conexiones es bueno, pero el exit() arriba a veces lo salta.
    // PHP lo hace solo al terminar el script, así que está bien.
} else {
    header("Location: index.php");
    exit();
}
?>