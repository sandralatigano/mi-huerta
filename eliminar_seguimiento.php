<?php

/**
 * Archivo: eliminar_seguimiento.php
 * Descripción: Elimina un registro de seguimiento de una planta específica.
 * Componente: Seguimiento (Bitácora)
 */

include 'conexion.php';

// 1. Verificar que los IDs existan en la URL
if (isset($_GET['id']) && isset($_GET['planta_id'])) {
    $id = $_GET['id'];
    $id_planta = $_GET['planta_id'];

    // 2. Ejecutar la sentencia de eliminación
    // Es importante que el nombre de la tabla y la columna coincidan con tu DB
    $sql = "DELETE FROM seguimiento WHERE id = $id";

    if ($conexion->query($sql)) {
        // 3. Si se elimina, redirigir de nuevo al historial de la planta
        header("Location: seguimiento.php?id=" . $id_planta . "&msg=eliminado");
    } else {
        echo "Error al eliminar: " . $conexion->error;
    }
} else {
    echo "Faltan parámetros para eliminar.";
}
?>