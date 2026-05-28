<?php

/**
 * Archivo: usuarios_nuevo.php
 * Descripción: Formulario para crear un nuevo usuario del sistema.
 * Componente: Gestión de Usuarios
 */

include 'auth.php';

// Bloqueo: Si el rol es mayor a 2 (es decir, es Operador), lo expulsamos
if ($mi_rol > 2) {
    header("Location: index.php?error=sin_permiso");
    exit();
}

include 'conexion.php';

// Traemos los roles de la base de datos
$res_roles = $conexion->query("SELECT * FROM roles ORDER BY id ASC");
include 'encabezado_pagina.php';
?>

<div class="container mt-4 mb-5">
    <div class="d-flex justify-content-between align-items-center mb-4 pb-2 border-bottom">
        <h4 class="text-secondary m-0 fw-normal">
            <i class="bi bi-person-plus-fill text-success"></i> Nuevo Usuario del Sistema
        </h4>
        <a href="usuarios_gestion.php" class="btn btn-outline-secondary btn-sm shadow-sm">
            <i class="bi bi-arrow-left"></i> Volver
        </a>
    </div>

    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card shadow-sm border-0 rounded-3">
                <div class="card-body p-4">
                    <form action="usuarios_guardar.php" method="POST">
                        <div class="row g-3">
                            <div class="col-md-6">
                                <label class="form-label small fw-bold text-secondary">Nombre de Usuario (Login)</label>
                                <input type="text" name="nombre_usuario" class="form-control bg-light border-0" required>
                            </div>
                            
                            <div class="col-md-6">
                                <label class="form-label small fw-bold text-secondary">Rol asignado</label>
                                <select name="id_rol" class="form-select bg-light border-0" required>
                                    <option value="" selected disabled>Seleccione un rol...</option>
                                    <?php while($r = $res_roles->fetch_assoc()): ?>
                                        <?php 
                                        // SI ES ADMIN PRINCIPAL (SANDRA): Ve todos los roles
                                        if ($_SESSION['id_rol'] == 1): ?>
                                            <option value="<?= $r['id'] ?>"><?= $r['nombre_rol'] ?></option>
                                        <?php 
                                        // SI ES ADMINISTRADOR (CARMEN): Solo ve el rol de Operador (ID 3)
                                        elseif ($_SESSION['id_rol'] == 2 && $r['id'] == 3): ?>
                                            <option value="<?= $r['id'] ?>"><?= $r['nombre_rol'] ?></option>
                                        <?php endif; ?>
                                    <?php endwhile; ?>
                                </select>
                                <?php if ($_SESSION['id_rol'] == 2): ?>
                                    <p class="extra-small text-muted mt-1 mb-0">
                                        <i class="bi bi-info-circle"></i> Solo puedes crear Operadores.
                                    </p>
                                <?php endif; ?>
                            </div>

                            <div class="col-md-12">
                                <label class="form-label small fw-bold text-secondary">Nombre Real / Descripción</label>
                                <input type="text" name="nombre_real" class="form-control bg-light border-0" required placeholder="Ej: Juan Pérez - Operador de Riego">
                            </div>

                            <div class="col-md-12">
                                <label class="form-label small fw-bold text-secondary">Contraseña</label>
                                <input type="password" name="password" class="form-control bg-light border-0" required>
                            </div>

                            <div class="col-12 text-end mt-4">
                                <hr>
                                <button type="submit" class="btn btn-success fw-bold px-5 shadow-sm">
                                    <i class="bi bi-save"></i> Crear Usuario
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