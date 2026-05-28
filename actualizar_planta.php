<?php

/**
 * Archivo: actualizar_planta.php
 * Descripción: Procesa el formulario de edición de planta y actualiza los datos en la base de datos.
 * Componente: Gestión de Plantas
 */

include 'conexion.php';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $id              = intval($_POST['id']);
    $nombre          = $_POST['nombre'];
    $variedad        = $_POST['variedad'];
    $id_bancal       = intval($_POST['id_bancal']);
    $id_epoca        = !empty($_POST['id_epoca']) ? intval($_POST['id_epoca']) : NULL;
    $id_tipo_siembra = !empty($_POST['id_tipo_siembra']) ? intval($_POST['id_tipo_siembra']) : NULL;

    // Asegúrate que los nombres de columna coincidan con tu tabla 'plantas'
    $sql = "UPDATE plantas SET nombre=?, variedad=?, id_bancal=?, id_epoca=?, id_tipo_siembra=? WHERE id=?";
    
    $stmt = $conexion->prepare($sql);
    
    // QA CHECK: "ssiiii" (2 textos, 4 números enteros)
    $stmt->bind_param("ssiiii", $nombre, $variedad, $id_bancal, $id_epoca, $id_tipo_siembra, $id);

    if ($stmt->execute()) {
        header("Location: index.php?status=updated");
    } else {
        echo "Error al actualizar: " . $stmt->error;
    }
    
    $stmt->close();
    $conexion->close();
}
?>