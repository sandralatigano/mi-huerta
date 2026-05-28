<?php

/**
 * Archivo: informes.php
 * Descripción: Genera un informe general de estado de la huerta con estadísticas y alertas.
 * Componente: Informes y Reportes
 */

include 'auth.php'; 
include 'conexion.php';
include 'encabezado_pagina.php'; 

// --- CONSULTAS ---
$res_bancales = $conexion->query("SELECT b.nombre_bancal, COUNT(p.id) as total FROM bancales b LEFT JOIN plantas p ON b.id = p.id_bancal GROUP BY b.id");
$hoy_str = date('Y-m-d');
$res_sedientas = $conexion->query("SELECT p.nombre, p.variedad, b.nombre_bancal, (SELECT MAX(fecha) FROM seguimiento WHERE id_planta = p.id AND accion = 'Riego') as ultimo_riego FROM plantas p JOIN bancales b ON p.id_bancal = b.id HAVING ultimo_riego IS NULL OR DATEDIFF('$hoy_str', ultimo_riego) >= 2");
$res_progreso = $conexion->query("SELECT p.nombre, p.variedad, p.fecha_siembra, c.dias_cosecha_num FROM plantas p JOIN catalogo_plantas c ON p.nombre = c.nombre_comun WHERE p.fecha_siembra IS NOT NULL AND c.dias_cosecha_num > 0");
?>

<link rel="stylesheet" href="impresion.css">

<div class="container mt-4 mb-5">
    
    <div class="d-flex justify-content-end mb-3 d-print-none">
        <a href="index.php" class="btn btn-outline-secondary btn-sm me-2">Volver</a>
        <button onclick="window.print()" class="btn btn-danger btn-sm">Generar PDF / Imprimir</button>
    </div>

    <div class="mb-4 border-bottom pb-2">
        <h2 class="text-success fw-bold m-0">Informe de Estado</h2>
        <p class="text-muted small">Generado el: <?= date('d/m/Y H:i') ?></p>
    </div>

    <div class="row g-3 mb-4">
        <div class="col-4">
            <div class="card bg-success text-white p-3 border-0">
                <h6 class="small mb-1">Bancales</h6>
                <h3><?= $res_bancales->num_rows ?></h3>
            </div>
        </div>
        <div class="col-4">
            <div class="card bg-warning text-dark p-3 border-0">
                <h6 class="small mb-1">Riego</h6>
                <h3><?= $res_sedientas->num_rows ?></h3>
            </div>
        </div>
        <div class="col-4">
            <div class="card bg-info text-white p-3 border-0">
                <h6 class="small mb-1">Especies</h6>
                <?php $total_c = $conexion->query("SELECT count(*) as t FROM catalogo_plantas")->fetch_assoc(); ?>
                <h3><?= $total_c['t'] ?></h3>
            </div>
        </div>
    </div>

    <div class="row">
        <div class="col-7">
            <div class="card mb-4">
                <div class="card-header bg-white fw-bold">Estimación de Cosecha</div>
                <div class="card-body">
                    <?php while($p = $res_progreso->fetch_assoc()): 
                        $fs = new DateTime($p['fecha_siembra']);
                        $pasados = (new DateTime())->diff($fs)->days;
                        $porc = min(100, round(($pasados / $p['dias_cosecha_num']) * 100));
                    ?>
                    <div class="mb-3 small">
                        <div class="d-flex justify-content-between">
                            <span><?= $p['nombre'] ?></span>
                            <span>Faltan <?= max(0, $p['dias_cosecha_num'] - $pasados) ?> días</span>
                        </div>
                        <div class="progress" style="height: 10px;">
                            <div class="progress-bar bg-success" style="width: <?= $porc ?>%"></div>
                        </div>
                    </div>
                    <?php endwhile; ?>
                </div>
            </div>
        </div>

        <div class="col-5">
            <div class="card mb-4">
                <div class="card-header bg-white fw-bold text-danger small">Alertas de Riego</div>
                <table class="table table-sm mb-0 extra-small">
                    <tbody>
                        <?php while($s = $res_sedientas->fetch_assoc()): ?>
                        <tr><td><?= $s['nombre'] ?></td><td><?= $s['nombre_bancal'] ?></td></tr>
                        <?php endwhile; ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    <div class="mt-5 d-none d-print-block">
        <h6 class="fw-bold text-success border-bottom pb-2">Observaciones y Notas de Campo:</h6>
        <div style="border: 1px solid #dee2e6; height: 100px; background-color: #fcfcfc;"></div>
        <div class="d-flex justify-content-end mt-4">
            <div class="text-center" style="width: 200px; border-top: 1px solid #000;">
                <p class="small mt-1">Firma del Responsable</p>
            </div>
        </div>
    </div>
</div>

<div class="d-print-none">
    <?php include 'pie_pagina.php'; ?>
</div>