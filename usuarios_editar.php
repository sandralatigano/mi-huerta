<?php

/**
 * Archivo: usuarios_editar.php
 * Descripción: Formulario para editar los datos de un usuario existente.
 * Componente: Gestión de Usuarios
 */

include 'auth.php';

// Bloqueo: Si el rol es mayor a 2 (es decir, es Operador), lo expulsamos
if ($mi_rol > 2) {
    header("Location: index.php?error=sin_permiso");
    exit();
}

include 'conexion.php';

//$id = $_GET['id'];
$id = (int)$_GET['id']; // seguridad extra

// Agregamos JOIN para traer el nombre del rol y mostrarlo si no es admin principal
$stmt = $conexion->prepare("SELECT u.*, r.nombre_rol 
                            FROM usuarios u 
                            JOIN roles r ON u.id_rol = r.id 
                            WHERE u.id = ?");
$stmt->bind_param("i", $id);
$stmt->execute();
$usuario = $stmt->get_result()->fetch_assoc();

include 'encabezado_pagina.php';
?>

<div class="container mt-4 mb-5">
    <div class="d-flex justify-content-between align-items-center mb-4 pb-2 border-bottom">
        <h4 class="text-secondary m-0 fw-normal">
            <i class="bi bi-pencil-square text-warning"></i> Editar Usuario: <span class="fw-bold"><?= htmlspecialchars($usuario['nombre_usuario']) ?></span>
        </h4>
        <a href="usuarios_gestion.php" class="btn btn-outline-secondary btn-sm shadow-sm">
            <i class="bi bi-arrow-left"></i> Volver
        </a>
    </div>

    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card shadow-sm border-0 rounded-3">
                <div class="card-body p-4">
                    <form action="usuarios_actualizar.php" method="POST">
                        <input type="hidden" name="id" value="<?= htmlspecialchars($usuario['id']) ?>">
                        <div class="row g-3">
                            <div class="col-md-6">
                                <label class="form-label small fw-bold text-secondary">Nombre de Usuario</label>
                                <input type="text" name="nombre_usuario" class="form-control bg-light border-0" value="<?= htmlspecialchars($usuario['nombre_usuario']) ?>" required>
                            </div>
                            
                            <div class="col-md-6">
                                <label class="form-label small fw-bold text-secondary">Rol del Sistema</label>
                                <?php if ($_SESSION['id_rol'] == 1): ?>
                                    <select name="id_rol" class="form-select bg-light border-0">
                                        <option value="1" <?= $usuario['id_rol'] == 1 ? 'selected' : '' ?>>Administrador Principal</option>
                                        <option value="2" <?= $usuario['id_rol'] == 2 ? 'selected' : '' ?>>Administrador</option>
                                        <option value="3" <?= $usuario['id_rol'] == 3 ? 'selected' : '' ?>>Operador</option>
                                    </select>
                                <?php else: ?>
                                    <div class="form-control bg-light border-0 text-muted">
                                        <i class="bi bi-shield-lock me-2"></i> <?= htmlspecialchars($usuario['nombre_rol']) ?>
                                        <input type="hidden" name="id_rol" value="<?= htmlspecialchars($usuario['id_rol']) ?>">
                                    </div>
                                    <p class="extra-small text-muted mt-1 mb-0"><i class="bi bi-info-circle"></i> Solo lectura para administradores.</p>
                                <?php endif; ?>
                            </div>

                            <div class="col-md-12">
                                <label class="form-label small fw-bold text-secondary">Nombre Real</label>
                                <input type="text" name="nombre_real" class="form-control bg-light border-0" value="<?= htmlspecialchars($usuario['nombre_real']) ?>" required>
                            </div>

                            <div class="col-md-12">
                                <div class="alert alert-info border-0 mt-2 mb-1 py-2" style="font-size: 0.8rem;">
                                    <i class="bi bi-info-circle-fill me-2"></i> Si no deseas cambiar la contraseña, deja el siguiente campo vacío.
                                </div>
                            </div>

                            <div class="col-md-12">
                                <label class="form-label small fw-bold text-secondary">Nueva Contraseña (Opcional)</label>
                                <input type="password" name="password" class="form-control bg-light border-0" placeholder="Escribe solo si quieres cambiarla">
                            </div>

                            <div class="col-12 text-end mt-4">
                                <hr>
                                <button type="submit" class="btn btn-warning fw-bold px-5 shadow-sm text-dark">
                                    <i class="bi bi-check-circle"></i> Actualizar Datos
                                </button>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>

<?php include 'pie_pagina.php'; ?>