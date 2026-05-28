<?php

/**
 * Archivo: editar_planta.php
 * Descripción: Formulario para editar los datos de una planta existente.
 * Componente: Gestión de Plantas
 */

include 'auth.php';       // <--- ESTA es la clave para que aparezca "Sandra"
include 'conexion.php';
include 'encabezado_pagina.php';

$id = intval($_GET['id']);
$res = $conexion->query("SELECT * FROM plantas WHERE id = $id");
$p = $res->fetch_assoc();

// Consultas para los selectores
$cat_plantas = $conexion->query("SELECT nombre_comun FROM catalogo_plantas ORDER BY nombre_comun");
$cat_bancales = $conexion->query("SELECT id, nombre_bancal FROM bancales ORDER BY nombre_bancal");
$cat_epocas = $conexion->query("SELECT id, nombre_epoca FROM epocas ORDER BY nombre_epoca");
$cat_tipos = $conexion->query("SELECT id, nombre_tipo FROM tipos_siembra ORDER BY nombre_tipo");
?>
<div class="container mt-4">
    <div class="d-flex justify-content-between align-items-center mb-4 pb-2 border-bottom">
        <h4 class="text-secondary m-0 fw-normal">
            <i class="bi bi-pencil-square text-success"></i> Editar Planta
        </h4>
        <a href="index.php" class="btn btn-outline-secondary btn-sm">
            <i class="bi bi-x-circle"></i> Cancelar
        </a>
    </div>

    <div class="card shadow-sm border-0 rounded-3">
        <div class="card-body p-4">
            
            <h5 class="text-success fw-bold border-bottom pb-2 mb-4">Datos de la Planta</h5>
            
            <form action="actualizar_planta.php" method="POST">
                <input type="hidden" name="id" value="<?= $id ?>">

                <div class="row g-3">
                    <div class="col-md-6">
                        <label class="form-label small fw-bold">Nombre (Catálogo)</label>
                        <select name="nombre" class="form-select shadow-sm">
                            <?php while($c = $cat_plantas->fetch_assoc()): ?>
                                <option value="<?= $c['nombre_comun'] ?>" <?= ($c['nombre_comun'] == $p['nombre']) ? 'selected' : '' ?>>
                                    <?= $c['nombre_comun'] ?>
                                </option>
                            <?php endwhile; ?>
                        </select>
                    </div>

                    <div class="col-md-6">
                        <label class="form-label small fw-bold">Variedad</label>
                        <input type="text" name="variedad" class="form-control shadow-sm" value="<?= $p['variedad'] ?>">
                    </div>

                    <div class="col-md-4">
                        <label class="form-label small fw-bold">Bancal</label>
                        <select name="id_bancal" class="form-select shadow-sm">
                            <?php while($b = $cat_bancales->fetch_assoc()): ?>
                                <option value="<?= $b['id'] ?>" <?= ($b['id'] == $p['id_bancal']) ? 'selected' : '' ?>>
                                    <?= $b['nombre_bancal'] ?>
                                </option>
                            <?php endwhile; ?>
                        </select>
                    </div>

                    <div class="col-md-4">
                        <label class="form-label small fw-bold">Época</label>
                        <select name="id_epoca" class="form-select shadow-sm">
                            <option value="">Elegir...</option>
                            <?php while($e = $cat_epocas->fetch_assoc()): ?>
                                <option value="<?= $e['id'] ?>" <?= ($e['id'] == $p['id_epoca']) ? 'selected' : '' ?>>
                                    <?= $e['nombre_epoca'] ?>
                                </option>
                            <?php endwhile; ?>
                        </select>
                    </div>

                    <div class="col-md-4">
                        <label class="form-label small fw-bold">Tipo Siembra</label>
                        <select name="id_tipo_siembra" class="form-select shadow-sm">
                            <option value="">Elegir...</option>
                            <?php while($t = $cat_tipos->fetch_assoc()): ?>
                                <option value="<?= $t['id'] ?>" <?= ($t['id'] == $p['id_tipo_siembra']) ? 'selected' : '' ?>>
                                    <?= $t['nombre_tipo'] ?>
                                </option>
                            <?php endwhile; ?>
                        </select>
                    </div>

                    <div class="col-md-12 text-end mt-4">
                        <hr>
                        <button type="submit" class="btn btn-success fw-bold px-5 shadow-sm">
                            <i class="bi bi-check-circle"></i> Guardar Cambios
                        </button>
                    </div>
                </div>
            </form> </div>
    </div>
</div>

<?php include 'pie_pagina.php'; ?>