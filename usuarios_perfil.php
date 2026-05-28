<?php

/**
 * Archivo: usuarios_perfil.php
 * Descripción: Muestra y permite la edición del perfil del usuario logueado.
 * Componente: Gestión de Usuarios
 */

include 'auth.php'; 
include 'conexion.php';

// 1. Obtenemos los datos frescos del usuario logueado
$id_sesion = $_SESSION['usuario_id'];
$stmt = $conexion->prepare("SELECT u.*, r.nombre_rol 
                            FROM usuarios u 
                            INNER JOIN roles r ON u.id_rol = r.id 
                            WHERE u.id = ?");
$stmt->bind_param("i", $id_sesion);
$stmt->execute();
$usuario = $stmt->get_result()->fetch_assoc();

include 'encabezado_pagina.php'; 
?>

<div class="container mt-4 mb-5">
    <div class="d-flex justify-content-between align-items-center mb-4 pb-2 border-bottom">
        <h4 class="text-secondary m-0 fw-normal">
            <i class="bi bi-person-badge text-success"></i> Gestión de Mi Perfil
        </h4>
        <a href="index.php" class="btn btn-outline-secondary btn-sm shadow-sm">
            <i class="bi bi-house-door"></i> Volver al Panel
        </a>
    </div>

    <div class="row justify-content-center">
        <div class="col-md-6 col-lg-5">
            <div class="card shadow border-0 rounded-4 overflow-hidden">
                <div class="py-4 text-center" style="background-color: #1b4332;">
                    <div class="rounded-circle bg-white text-success d-inline-flex align-items-center justify-content-center mb-2 shadow" 
                         style="width: 90px; height: 90px; font-size: 2.5rem; border: 4px solid rgba(255,255,255,0.2);">
                        <?= strtoupper(substr($usuario['nombre_real'], 0, 1)) ?>
                    </div>
                    <h5 class="text-white mb-0 fw-bold"><?= htmlspecialchars($usuario['nombre_real']) ?></h5>
                   <span class="badge rounded-pill bg-success text-white border border-white-50 mt-2 px-3 small">
                        <i class="bi bi-shield-lock me-1"></i> <?= htmlspecialchars($usuario['nombre_rol']) ?>
                    </span>
                </div>

                <div class="card-body p-4">
                    
                    <?php if (isset($_GET['status']) && $_GET['status'] == 'perfil_updated'): ?>
                        <div class="alert alert-success border-0 shadow-sm mb-4 small">
                            <i class="bi bi-check-circle-fill me-2"></i> ¡Perfil actualizado con éxito!
                        </div>
                    <?php endif; ?>

                    <?php if (isset($_GET['error'])): ?>
                        <div class="alert alert-danger border-0 shadow-sm mb-4 small">
                            <i class="bi bi-exclamation-triangle-fill me-2"></i>
                            <?php 
                                if($_GET['error'] == 'password_mismatch') echo "Las nuevas contraseñas no coinciden.";
                                if($_GET['error'] == 'password_short') echo "La clave nueva es muy corta (mín. 6 carac.).";
                                if($_GET['error'] == 'wrong_current') echo "La contraseña actual es incorrecta.";
                                if($_GET['error'] == 'db_error') echo "Error técnico: No se pudo actualizar en la base de datos.";
                            ?>
                        </div>
                    <?php endif; ?>
                    <form action="usuarios_actualizar_perfil.php" method="POST">
                        <div class="row g-3">
                            <div class="col-12">
                                <label class="form-label small fw-bold text-secondary text-uppercase" style="font-size: 0.7rem;">Nombre de Usuario</label>
                                <div class="input-group">
                                    <span class="input-group-text border-0 bg-light"><i class="bi bi-person text-muted"></i></span>
                                    <input type="text" class="form-control border-0 bg-light" value="<?= htmlspecialchars($usuario['nombre_usuario']) ?>" readonly disabled>
                                </div>
                                <div class="form-text extra-small" style="font-size: 0.7rem;">El nombre de usuario no puede ser modificado.</div>
                            </div>

                            <div class="col-12 mt-3">
                                <label class="form-label small fw-bold text-secondary text-uppercase" style="font-size: 0.7rem;">Nombre Real para el Sistema</label>
                                <input type="text" name="nombre_real" class="form-control shadow-sm border-light-subtle" 
                                       value="<?= htmlspecialchars($usuario['nombre_real']) ?>" required>
                            </div>

                            <div class="col-12">
                                <hr class="my-3 opacity-25">
                                <h6 class="text-success fw-bold small mb-3">
                                    <i class="bi bi-key-fill me-1"></i> Seguridad y Acceso
                                </h6>
                            </div>

                            <div class="col-12">
                                <label class="form-label small fw-bold text-dark">Tu Contraseña Actual</label>
                                <input type="password" name="password_current" class="form-control border-success-subtle shadow-sm" 
                                       placeholder="Confirmar para guardar cambios" required>
                                <div class="form-text text-danger extra-small" style="font-size: 0.7rem;">Es obligatoria para validar que eres tú.</div>
                            </div>

                            <div class="col-12 mt-2">
                                <div class="p-3 rounded-3 border bg-light-subtle shadow-inner">
                                    <div class="mb-3">
                                        <label class="form-label extra-small fw-bold text-secondary">NUEVA CONTRASEÑA (OPCIONAL)</label>
                                        <input type="password" name="password_new" class="form-control form-control-sm border-0 shadow-sm" 
                                               placeholder="Dejar vacío si no quieres cambiarla">
                                    </div>
                                    <div class="mb-0">
                                        <label class="form-label extra-small fw-bold text-secondary">CONFIRMAR NUEVA CLAVE</label>
                                        <input type="password" name="password_confirm" class="form-control form-control-sm border-0 shadow-sm">
                                    </div>
                                </div>
                            </div>

                            <div class="col-12 mt-4">
                                <button type="submit" class="btn btn-success w-100 py-2 fw-bold shadow">
                                    <i class="bi bi-check2-circle me-2"></i> Guardar Actualizaciones
                                </button>
                            </div>
                        </div>
                    </form>
                </div>
            </div>

            <div class="text-center mt-4">
                <p class="text-muted small mb-0">
                    Soporte técnico de Salta: Si pierdes tu acceso, <br>
                    comunícate con la administración de **Mi Huerta**.
                </p>
            </div>
        </div>
    </div>
</div>

<?php include 'pie_pagina.php'; ?>
<script src="js/scripts.js"></script>