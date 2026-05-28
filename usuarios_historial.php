<?php

/**
 * Archivo: usuarios_historial.php
 * Descripción: Muestra el historial de bajas de usuarios del sistema.
 * Componente: Gestión de Usuarios
 */

include 'auth.php';
include 'conexion.php';

$mi_rol = $_SESSION['id_rol'];
$mi_id = $_SESSION['usuario_id'];

// Bloqueo: Solo Admins (1) y Administradores (2) pueden entrar. Operadores (3) afuera.
if ($mi_rol > 2) {
    header("Location: index.php?error=sin_permiso");
    exit();
}

// DEFINICIÓN DE LA CONSULTA SEGÚN EL ROL
if ($mi_rol == 1) {
    // El Administrador Principal ve TODO el historial de bajas
    $sql = "SELECT h.*, u.nombre_real AS responsable 
            FROM historial_usuarios h 
            LEFT JOIN usuarios u ON h.realizado_por = u.id 
            ORDER BY h.fecha_baja DESC";
} else {
    // El Administrador (Rol 2) solo ve las bajas que él mismo ejecutó (Su gestión)
    $sql = "SELECT h.*, u.nombre_real AS responsable 
            FROM historial_usuarios h 
            LEFT JOIN usuarios u ON h.realizado_por = u.id 
            WHERE h.realizado_por = $mi_id 
            ORDER BY h.fecha_baja DESC";
}

$resultado = $conexion->query($sql);

include 'encabezado_pagina.php';
?>

<div class="container mt-4 mb-5">
    <div class="d-flex justify-content-between align-items-center mb-4 pb-2 border-bottom">
        <h4 class="text-secondary m-0 fw-normal">
            <i class="bi bi-clock-history text-info"></i> Historial de Bajas de Usuarios
        </h4>
        <a href="usuarios_gestion.php" class="btn btn-outline-secondary btn-sm">
            <i class="bi bi-arrow-left"></i> Volver a Gestión
        </a>
    </div>

    <div class="card shadow-sm border-0 rounded-3">
        <div class="card-body p-0">
            <div class="table-responsive">
                <table class="table table-hover align-middle mb-0">
                    <thead class="table-dark small">
                        <tr>
                            <th class="ps-4">USUARIO BORRADO</th>
                            <th>NOMBRE REAL</th>
                            <th>FECHA ALTA ORIG.</th>
                            <th>FECHA BAJA</th>
                            <th>MOTIVO / OBSERVACIONES</th>
                            <th class="pe-4">REALIZADO POR</th>
                        </tr>
                    </thead>
                    <tbody class="small">
                        <?php if ($resultado->num_rows > 0): ?>
                            <?php while ($row = $resultado->fetch_assoc()): ?>
                                <tr>
                                    <td class="ps-4">
                                        <span class="badge bg-light text-dark border"><?php echo htmlspecialchars($row['nombre_usuario']); ?></span>
                                    </td>
                                    <td><?php echo htmlspecialchars($row['nombre_real']); ?></td>
                                    <td class="text-muted"><?php echo date('d/m/Y', strtotime($row['fecha_alta_original'])); ?></td>
                                    <td class="text-danger fw-bold"><?php echo date('d/m/Y H:i', strtotime($row['fecha_baja'])); ?></td>
                                    <td class="text-wrap" style="max-width: 250px;"><?php echo htmlspecialchars($row['observaciones']); ?></td>
                                    <td class="pe-4">
                                        <span class="text-primary">
                                            <i class="bi bi-person-badge"></i> 
                                            <?php echo $row['responsable'] ? htmlspecialchars($row['responsable']) : '<span class="text-muted">Sistema / Eliminado</span>'; ?>
                                        </span>
                                    </td>
                                </tr>
                            <?php endwhile; ?>
                        <?php else: ?>
                            <tr>
                                <td colspan="6" class="text-center py-5 text-muted">
                                    <i class="bi bi-info-circle me-2"></i> No hay registros de bajas en tu gestión.
                                </td>
                            </tr>
                        <?php endif; ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>

<?php include 'pie_pagina.php'; ?>