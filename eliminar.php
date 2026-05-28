<?php

/**
 * Archivo: eliminar.php
 * Descripción: Elimina una planta y su historial de seguimiento de la base de datos.
 * Componente: Gestión de Plantas
 */

include 'conexion.php';

if (isset($_GET['id'])) {
    $id = (int)$_GET['id'];

    // 1. Borramos historial para que no falle por la FK (id_planta)
    $stmt_seg = $conexion->prepare("DELETE FROM seguimiento WHERE id_planta = ?");
    $stmt_seg->bind_param("i", $id);
    $stmt_seg->execute();

    // 2. Borramos la planta
    $stmt_plan = $conexion->prepare("DELETE FROM plantas WHERE id = ?");
    $stmt_plan->bind_param("i", $id);
    
    if ($stmt_plan->execute()) {
        header("Location: index.php");
    }
}
?>