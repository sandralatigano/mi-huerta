<?php

/**
 * Archivo: usuarios_gestion.php
 * Descripción: Panel de administración para crear, editar y dar de baja a los usuarios del sistema.
 * Componente: Gestión de Usuarios
 */

include 'auth.php'; // Primero verificamos quién es el usuario

include 'conexion.php';

// Obtenemos los datos de la sesión correctamente
$mi_rol = $_SESSION['id_rol'];
$mi_id = $_SESSION['usuario_id'];

// Bloqueo: Si el rol es mayor a 2 (es decir, es Operador), lo expulsamos
if ($mi_rol > 2) {
    header("Location: index.php?error=sin_permiso");
    exit();
}

// Consulta para obtener los usuarios
if ($mi_rol == 1) {
    // Si soy Administrador Principal, veo a TODOS (incluyéndome para gestión)
    $sql = "SELECT u.*, r.nombre_rol 
            FROM usuarios u 
            JOIN roles r ON u.id_rol = r.id 
            ORDER BY u.id_rol ASC";
} else {
    // Si soy Administrador, SOLO veo a los rangos inferiores (Operadores)
    $sql = "SELECT u.*, r.nombre_rol 
            FROM usuarios u 
            JOIN roles r ON u.id_rol = r.id 
            WHERE u.id_rol > $mi_rol 
            ORDER BY u.nombre_real ASC";
}

$resultado = $conexion->query($sql);

include 'encabezado_pagina.php'; 
?>

<div class="container mt-4 mb-5">
    <div class="d-flex justify-content-between align-items-center mb-4 pb-2 border-bottom">
        <h4 class="text-secondary m-0 fw-normal">
            <i class="bi bi-people-fill text-success"></i> Gestión de Usuarios
        </h4>
        <div class="d-flex gap-2">
            <a href="usuarios_historial.php" class="btn btn-outline-info btn-sm shadow-sm">
                <i class="bi bi-clock-history"></i> Ver Historial de Bajas
            </a>
            <a href="usuarios_nuevo.php" class="btn btn-success btn-sm fw-bold px-3 shadow-sm">
                <i class="bi bi-person-plus-fill"></i> Agregar Usuario
            </a>
        </div>
    </div>

    <?php if (isset($_GET['status']) && $_GET['status'] == 'deleted'): ?>
        <div class="alert alert-success alert-dismissible fade show border-0 shadow-sm mb-4" role="alert">
            <i class="bi bi-check-circle-fill me-2 text-success"></i>
            <strong>¡Usuario dado de baja y archivado correctamente!</strong>
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
    <?php endif; ?>

    <div class="d-none d-md-block card shadow-sm border-0 rounded-3 overflow-hidden">
        <div class="card-body p-0">
            <div class="table-responsive">
                <table class="table table-hover align-middle mb-0">
                    <thead class="table-dark small">
                        <tr>
                            <th class="ps-4">ID</th>
                            <th>USUARIO</th>
                            <th>NOMBRE REAL</th>
                            <th>ROL</th>
                            <th>FECHA DE ALTA</th>
                            <th class="text-center pe-4">ACCIONES</th>
                        </tr>
                    </thead>
                    <tbody class="small">
                        <?php
                        if ($resultado->num_rows > 0) {
                            $resultado->data_seek(0); // Reiniciamos puntero para la PC
                            while ($row = $resultado->fetch_assoc()): ?>
                                <tr>
                                    <td class="ps-4 fw-bold text-muted"><?php echo $row['id']; ?></td>
                                    <td>
                                        <span class="badge bg-light text-dark border rounded-pill px-3">
                                            <?php echo htmlspecialchars($row['nombre_usuario']); ?>
                                        </span>
                                    </td>
                                    <td class="fw-bold text-secondary">
                                        <?php echo htmlspecialchars($row['nombre_real']); ?>
                                    </td>
                                    <td>
                                        <span class="badge bg-light text-dark border rounded-pill px-3">
                                            <?php echo htmlspecialchars($row['nombre_rol']); ?>
                                        </span>
                                    </td>
                                    <td class="text-muted">
                                        <i class="bi bi-calendar3 me-1"></i>
                                        <?php echo date('d/m/Y', strtotime($row['fecha_alta'])); ?>
                                    </td>
                                    <td class="text-center pe-4">
                                        <div class="btn-group shadow-sm">
                                            <a href="usuarios_editar.php?id=<?php echo $row['id']; ?>" 
                                            class="btn btn-sm btn-outline-warning" title="Editar">
                                                <i class="bi bi-pencil-square"></i>
                                            </a>
                                            
                                            <?php if ($row['id'] != $_SESSION['usuario_id']): ?>
                                            <button onclick="confirmarBajaUsuario(<?php echo $row['id']; ?>)" 
                                                    class="btn btn-sm btn-outline-danger" title="Dar de baja">
                                                <i class="bi bi-trash"></i>
                                            </button>
                                            <?php else: ?>
                                            <button class="btn btn-sm btn-outline-secondary disabled" title="Tu usuario">
                                                <i class="bi bi-person-check"></i>
                                            </button>
                                            <?php endif; ?>
                                        </div>
                                    </td>
                                </tr>
                            <?php endwhile; 
                        } else { 
                            echo "<tr>";
                            echo "<td colspan='6' class='text-center text-muted py-4'>";
                            echo "<i class='bi bi-people me-2'></i> No hay usuarios registrados en el sistema actualmente.";
                            echo "</td>";
                            echo "</tr>";
                        }
                        ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    <div class="d-block d-md-none">
        <?php
        if ($resultado->num_rows > 0) {
            $resultado->data_seek(0); // Reiniciamos puntero para el celular
            while ($row = $resultado->fetch_assoc()): ?>
                <div class="card shadow-sm border-0 rounded-3 mb-3 bg-white">
                    <div class="card-body p-3">
                        <div class="d-flex justify-content-between align-items-center border-bottom pb-2 mb-2">
                            <span class="fw-bold text-dark" style="font-size: 0.95rem;">
                                <i class="bi bi-person text-muted me-1"></i> <?php echo htmlspecialchars($row['nombre_real']); ?>
                            </span>
                            <span class="badge bg-light text-muted border style="font-size: 0.75rem;">
                                ID: <?php echo $row['id']; ?>
                            </span>
                        </div>
                        
                        <div class="mb-2 small">
                            <span class="text-muted">Usuario:</span>
                            <span class="badge bg-light text-dark border rounded-pill px-2 ms-1">
                                <?php echo htmlspecialchars($row['nombre_usuario']); ?>
                            </span>
                        </div>

                        <div class="mb-2 small">
                            <span class="text-muted">Rol asignado:</span>
                            <span class="badge bg-light text-dark border rounded-pill px-2 ms-1">
                                <?php echo htmlspecialchars($row['nombre_rol']); ?>
                            </span>
                        </div>

                        <div class="mb-3 small text-muted">
                            <i class="bi bi-calendar3 me-1"></i> Alta: <?php echo date('d/m/Y', strtotime($row['fecha_alta'])); ?>
                        </div>

                        <div class="d-flex gap-2 border-top pt-2">
                            <a href="usuarios_editar.php?id=<?php echo $row['id']; ?>" class="btn btn-sm btn-outline-warning flex-grow-1 text-center py-2 fw-bold">
                                <i class="bi bi-pencil-square me-1"></i> Editar
                            </a>
                            
                            <?php if ($row['id'] != $_SESSION['usuario_id']): ?>
                            <button onclick="confirmarBajaUsuario(<?php echo $row['id']; ?>)" class="btn btn-sm btn-outline-danger px-3 py-2">
                                <i class="bi bi-trash"></i>
                            </button>
                            <?php else: ?>
                            <button class="btn btn-sm btn-outline-secondary disabled px-3 py-2">
                                <i class="bi bi-person-check"></i>
                            </button>
                            <?php endif; ?>
                        </div>
                    </div>
                </div>
            <?php endwhile; 
        } else { ?>
            <div class="text-center py-5 text-muted bg-white rounded-3 shadow-sm">
                <i class="bi bi-people d-block mb-2" style="font-size: 1.8rem;"></i>
                No hay usuarios registrados en el sistema actualmente.
            </div>
        <?php } ?>
    </div>

</div>

<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<script>
function confirmarBajaUsuario(id) {
    Swal.fire({
        title: '¿Dar de baja usuario?',
        text: "El usuario será eliminado pero sus datos se guardarán en el historial.",
        icon: 'warning',
        input: 'text',
        inputPlaceholder: 'Motivo de la baja...',
        showCancelButton: true,
        confirmButtonColor: '#d33',
        confirmButtonText: 'Sí, dar de baja',
        cancelButtonText: 'Cancelar'
    }).then((result) => {
        if (result.isConfirmed) {
            const motivo = encodeURIComponent(result.value || 'Sin observaciones');
            window.location.href = `usuarios_borrar.php?id=${id}&motivo=${motivo}`;
        }
    })
}
</script>

<?php include 'pie_pagina.php'; ?>
<script src="scripts.js"></script>