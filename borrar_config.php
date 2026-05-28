<?php
/**
 * Archivo: borrar_config.php
 * Descripción: Elimina registros específicos de configuración o restablece valores predeterminados.
 * Componente: Configuración
 */ 
include 'auth.php';
include 'conexion.php';

if (isset($_GET['id']) && isset($_GET['tabla'])) {
    $id = $_GET['id'];
    $tabla_alias = $_GET['tabla'];
    $from = $_GET['from'];

    // Mapeamos el alias al nombre real de la tabla en la DB
    $tablas_reales = [
        'catalogo' => 'catalogo_plantas',
        'epoca' => 'epocas',
        'tipo_siembra' => 'tipos_siembra',
        'bancal' => 'bancales',
        'tipo_cultivo' => 'tipos_cultivo',
        'grupo_rotacion' => 'grupos_rotacion'
    ];

    $tabla_real = $tablas_reales[$tabla_alias];

    $sql = "DELETE FROM $tabla_real WHERE id = ?";
    $stmt = $conexion->prepare($sql);
    $stmt->bind_param("i", $id);

    /*if ($stmt->execute()) {
        // Volvemos con status=ok para que scripts.js dispare el SweetAlert de éxito
        header("Location: configuracion.php?status=ok&from=" . $from);
    } else {
        echo "Error al eliminar: " . $conexion->error;
    }*/
    
    if ($stmt->execute()) {
        // Mandamos status=deleted para que el sistema sepa que debe mostrar el mensaje de borrado
        header("Location: configuracion.php?status=deleted&from=" . $from);
        exit();
    }
}
?>
