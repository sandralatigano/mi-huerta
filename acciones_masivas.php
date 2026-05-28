<?php

/**
 * Archivo: acciones_masivas.php
 * Descripción: Procesa acciones masivas como riego general para todas las plantas.
 * Componente: Gestión de Plantas
 */

include 'auth.php'; // <--- ¡FUNDAMENTAL! Solo usuarios logueados
include 'conexion.php';

if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['accion']) && $_POST['accion'] == 'riego_general') {
    
    // Opcional: Solo (Admin principal) puede hacer riegos masivos
    /*
    if ($_SESSION['id_rol'] != 1) { 
        header("Location: index.php?error=no_autorizado");
        exit();
    }
    */

    $fecha = date('Y-m-d');
    $accion = "Riego";
    $obs = "Riego general desde el panel";

    try {
        // Usamos una transacción: o se registran todos los riegos o ninguno
        $conexion->begin_transaction();

        $res = $conexion->query("SELECT id FROM plantas");

        if ($res && $res->num_rows > 0) {
            $stmt = $conexion->prepare("INSERT INTO seguimiento (id_planta, fecha, accion, observaciones) VALUES (?, ?, ?, ?)");
            
            while ($row = $res->fetch_assoc()) {
                $id_planta = $row['id'];
                $stmt->bind_param("isss", $id_planta, $fecha, $accion, $obs);
                $stmt->execute();
            }
            $stmt->close();
            
            $conexion->commit(); // Confirmamos todos los riegos
            header("Location: index.php?status=riego_ok");
            exit();
        } else {
            header("Location: index.php?status=error_no_plants");
            exit();
        }
    } catch (Exception $e) {
        $conexion->rollback(); // Si algo falla, no se guarda nada
        // Guardamos el error en un log interno (opcional) y mandamos error genérico
        header("Location: index.php?error=db_error");
        exit();
    }
} else {
    header("Location: index.php");
    exit();
}