<?php

/**
 * Archivo: seguimiento.php
 * Descripción: Muestra el historial de seguimiento de una planta específica.
 * Componente: Gestión de Plantas
 */

include 'auth.php'; 
include 'conexion.php';

$id_planta = $_GET['id'];

// Consultar datos de la planta para el título (incluimos variedad para el diseño)
$res = $conexion->query("SELECT nombre, variedad FROM plantas WHERE id = $id_planta");
$planta = $res->fetch_assoc();

// Consultar historial
$historial = $conexion->query("SELECT * FROM seguimiento WHERE id_planta = $id_planta ORDER BY fecha DESC");

include 'encabezado_pagina.php'; 
?>

<div class="container mt-4 mb-5">
    
    <div class="d-flex justify-content-between align-items-center mb-4 pb-2 border-bottom">
        <h4 class="text-secondary m-0 fw-normal">
            <i class="bi bi-journal-check text-success"></i> Seguimiento: 
            <span class="fw-bold text-dark"><?= htmlspecialchars($planta['nombre']) ?></span> 
            <small class="text-muted" style="font-size: 0.9rem;">(<?= htmlspecialchars($planta['variedad']) ?>)</small>
        </h4>
        
        <div class="d-flex gap-2">
            <a href="editar_planta.php?id=<?= $id_planta ?>" class="btn btn-outline-warning btn-sm shadow-sm fw-bold">
                <i class="bi bi-pencil"></i> Editar Planta
            </a>
            <a href="index.php" class="btn btn-outline-secondary btn-sm shadow-sm">
                <i class="bi bi-arrow-left"></i> Volver
            </a>
        </div>
    </div>

    <?php if(isset($_GET['msg']) && $_GET['msg'] == 'borrado'): ?>
        <div class="alert alert-success border-0 shadow-sm mb-4" role="alert">
            <i class="bi bi-check-circle-fill me-2"></i> ¡Registro eliminado correctamente!
        </div>
    <?php endif; ?>

    <div class="row g-4">
        <div class="col-md-4">
            <div class="card border-0 shadow-sm rounded-3">
                <div class="card-body p-4">
                    <h6 class="fw-bold mb-3 text-secondary border-bottom pb-2">
                        <i class="bi bi-plus-circle text-primary"></i> Registrar Tarea
                    </h6>
                    <form action="guardar_seguimiento.php" method="POST">
                        <input type="hidden" name="id_planta" value="<?= $id_planta ?>">
                        
                        <div class="mb-3">
                            <label class="form-label small fw-bold text-secondary">Fecha</label>
                            <input type="date" name="fecha" class="form-control form-control-sm shadow-sm" value="<?= date('Y-m-d') ?>" required>
                        </div>

                        <div class="mb-3">
                            <label class="form-label small fw-bold text-secondary">Acción</label>
                            <select name="accion" class="form-select form-select-sm shadow-sm">
                                <option value="Riego">Riego</option>
                                <option value="Abono">Abono</option>
                                <option value="Poda">Poda</option>
                                <option value="Cosecha">Cosecha</option>
                                <option value="Otro">Otro</option>
                            </select>
                        </div>

                        <div class="mb-3">
                            <label class="form-label small fw-bold text-secondary">Observaciones</label>
                            <textarea name="observaciones" class="form-control form-control-sm shadow-sm" rows="3" placeholder="Ej: Agregué compost..."></textarea>
                        </div>

                        <button type="submit" class="btn btn-primary w-100 fw-bold shadow-sm py-2">
                            <i class="bi bi-save me-1"></i> Guardar Registro
                        </button>
                    </form>
                </div>
            </div>
        </div>

        <div class="col-md-8">
            <div class="card border-0 shadow-sm rounded-3 overflow-hidden">
                <div class="table-responsive">
                    <table class="table table-hover mb-0">
                        <thead style="background-color: #1a3a2a; color: white;">
                            <tr>
                                <th class="ps-4">FECHA</th>
                                <th>ACCIÓN</th>
                                <th>OBSERVACIONES</th>
                                <th class="text-center pe-4">ACCIONES</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php if ($historial->num_rows > 0): ?>
                                <?php while($reg = $historial->fetch_assoc()): ?>
                                <tr class="align-middle">
                                    <td class="ps-4 fw-bold text-secondary" style="font-size: 0.85rem;">
                                        <?= date('d/m/Y', strtotime($reg['fecha'])) ?>
                                    </td>
                                    <td>
                                        <span class="badge bg-light text-success border rounded-pill px-3" style="font-size: 0.75rem;">
                                            <?= $reg['accion'] ?>
                                        </span>
                                    </td>
                                    <td class="text-muted small">
                                        <?= htmlspecialchars($reg['observaciones']) ?>
                                    </td>
                                    <td class="text-center pe-4">
                                        <button onclick="confirmarBorradoSeg(<?= $reg['id'] ?>, <?= $id_planta ?>)" 
                                                class="btn btn-sm btn-outline-danger border-0">
                                            <i class="bi bi-trash3"></i>
                                        </button>
                                    </td>
                                </tr>
                                <?php endwhile; ?>
                            <?php else: ?>
                                <tr>
                                    <td colspan="4" class="text-center py-5 text-muted">
                                        <i class="bi bi-info-circle d-block mb-2" style="font-size: 2rem;"></i>
                                        No hay tareas registradas para esta planta.
                                    </td>
                                </tr>
                            <?php endif; ?>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
function confirmarBorradoSeg(id, planta) {
    if(confirm('¿Estás seguro de eliminar este registro del historial?')) {
        window.location.href = 'borrar_seguimiento.php?id=' + id + '&id_planta=' + planta;
    }
}
</script>

<?php include 'pie_pagina.php'; ?>