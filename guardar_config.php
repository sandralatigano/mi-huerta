<?php
/**
 * Archivo: guardar_config.php
 * Descripción: Procesa y almacena los cambios realizados en el panel de configuración del sistema.
 * Componente: Configuración
 */ 
include 'conexion.php';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $tabla_tipo = $_POST['tabla'];
    $nombre = $_POST['nombre'];
    $from = $_POST['from'] ?? 'index'; 

    if ($tabla_tipo == 'catalogo') {
        $id_epoca = !empty($_POST['id_epoca']) ? intval($_POST['id_epoca']) : NULL;
        $id_tipo_siembra = !empty($_POST['id_tipo_siembra']) ? intval($_POST['id_tipo_siembra']) : NULL;
        $id_tipo_cultivo = !empty($_POST['id_tipo_cultivo']) ? intval($_POST['id_tipo_cultivo']) : NULL;
        $id_grupo_rotacion = !empty($_POST['id_grupo_rotacion']) ? intval($_POST['id_grupo_rotacion']) : NULL;
        $rieme = $_POST['riego'] ?? '';
        $distancia = !empty($_POST['distancia']) ? intval($_POST['distancia']) : 0;
        $cosecha = $_POST['cosecha'] ?? '';
        
        // --- NUEVA LÓGICA: Capturar días numéricos ---
        // Si no viene en el POST (porque no agregamos el input al HTML aún), 
        // podemos intentar una conversión simple o dejarlo en NULL
        $dias_cosecha_num = !empty($_POST['dias_cosecha_num']) ? intval($_POST['dias_cosecha_num']) : NULL;

        $sql = "INSERT INTO catalogo_plantas (nombre_comun, id_epoca, id_tipo_siembra, id_tipo_cultivo, id_grupo_rotacion, riego, distancia_sugerida, tiempo_cosecha, dias_cosecha_num) 
                VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?)";
        $stmt = $conexion->prepare($sql);
        // Ajustamos el bind_param (agregamos una "i" al final para el nuevo entero)
        $stmt->bind_param("siiiisisi", $nombre, $id_epoca, $id_tipo_siembra, $id_tipo_cultivo, $id_grupo_rotacion, $rieme, $distancia, $cosecha, $dias_cosecha_num);
        
    } elseif ($tabla_tipo == 'epoca') {
        $sql = "INSERT INTO epocas (nombre_epoca) VALUES (?)";
        $stmt = $conexion->prepare($sql);
        $stmt->bind_param("s", $nombre);

    } elseif ($tabla_tipo == 'tipo_siembra') {
        $sql = "INSERT INTO tipos_siembra (nombre_tipo) VALUES (?)";
        $stmt = $conexion->prepare($sql);
        $stmt->bind_param("s", $nombre);

    } elseif ($tabla_tipo == 'bancal') {
        $sql = "INSERT INTO bancales (nombre_bancal) VALUES (?)";
        $stmt = $conexion->prepare($sql);
        $stmt->bind_param("s", $nombre);

    } elseif ($tabla_tipo == 'tipo_cultivo') {
        $sql = "INSERT INTO tipos_cultivo (nombre_tipo) VALUES (?)";
        $stmt = $conexion->prepare($sql);
        $stmt->bind_param("s", $nombre);

    } elseif ($tabla_tipo == 'grupo_rotacion') { 
        $sql = "INSERT INTO grupos_rotacion (nombre_grupo) VALUES (?)"; 
        $stmt = $conexion->prepare($sql);
        $stmt->bind_param("s", $nombre);
    }

    if (isset($stmt) && $stmt->execute()) {
        header("Location: configuracion.php?status=success&from=" . urlencode($from));
        exit();
    } else {
        echo "Error: " . (isset($stmt) ? $stmt->error : $conexion->error);
    }

    if (isset($stmt)) $stmt->close();
    $conexion->close();
}
?>