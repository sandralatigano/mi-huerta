<?php

/**
 * Archivo: guardar_seguimiento.php
 * Descripción: Almacena un nuevo evento de cuidado o mantenimiento en la tabla 'seguimiento'.
 * Componente: Seguimiento (Bitácora)
 */

include 'conexion.php';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Capturamos los datos del formulario de seguimiento
    $id_planta = $_POST['id_planta'];
    $fecha = $_POST['fecha'];
    $accion = $_POST['accion'];
    $observaciones = $_POST['observaciones'];

    // Usamos sentencias preparadas por seguridad
    $sql = "INSERT INTO seguimiento (id_planta, fecha, accion, observaciones) VALUES (?, ?, ?, ?)";
    
    $stmt = $conexion->prepare($sql);
    
    // "isss" significa: integer, string, string, string
    $stmt->bind_param("isss", $id_planta, $fecha, $accion, $observaciones);

    if ($stmt->execute()) {
        // Si se guarda bien, volvemos a la página de seguimiento de esa planta
        header("Location: seguimiento.php?id=" . $id_planta);
    } else {
        echo "Error: " . $conexion->error;
    }

    $stmt->close();
    $conexion->close();
}
?>