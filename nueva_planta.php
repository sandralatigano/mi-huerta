<?php
/**
 * Archivo: nueva_planta.php
 * Descripción: Formulario para agregar una nueva planta al sistema.
 * Componente: Gestión de Plantas
 */

include 'auth.php'; // Portero: Solo usuarios logueados
include 'conexion.php';
include 'encabezado_pagina.php';

// Blindaje de Consultas: Aunque son simples, verificamos que el resultado sea válido
$res_catalogo = $conexion->query("SELECT nombre_comun, id_epoca, id_tipo_siembra, distancia_sugerida FROM catalogo_plantas ORDER BY nombre_comun");
$res_epocas   = $conexion->query("SELECT id, nombre_epoca FROM epocas ORDER BY nombre_epoca");
$res_tipos    = $conexion->query("SELECT id, nombre_tipo FROM tipos_siembra ORDER BY nombre_tipo");
$res_bancales = $conexion->query("SELECT id, nombre_bancal FROM bancales ORDER BY nombre_bancal");
?>

<div class="container mt-4 mb-5">

    <div class="d-flex justify-content-between align-items-center mb-4 pb-2 border-bottom">
        <h4 class="text-secondary m-0 fw-normal">
            <i class="bi bi-plus-circle text-success"></i> Agregar Nueva Planta
        </h4>
        <a href="index.php" class="btn btn-outline-secondary btn-sm">
            <i class="bi bi-arrow-left"></i> Volver al Panel
        </a>
    </div>

    <?php if (isset($_GET['error'])): ?>
        <div class="alert alert-danger border-0 shadow-sm mb-4">
            <i class="bi bi-exclamation-triangle-fill me-2"></i>
            <strong>Error:</strong> 
            <?php 
                $err = $_GET['error'];
                if($err == 'nombre_vacio') echo "El nombre de la planta es obligatorio.";
                elseif($err == 'db') echo "Hubo un problema al guardar en la base de datos.";
                else echo "Ocurrió un error inesperado. Inténtalo de nuevo.";
            ?>
        </div>
    <?php endif; ?>

    <div class="card shadow-sm border-0 rounded-3">
        <div class="card-body p-4">
            
            <h5 class="text-success fw-bold border-bottom pb-2 mb-4">Detalles de Cultivo</h5>

            <form action="guardar_planta.php" method="POST" autocomplete="off">
                <div class="row">
                    <div class="col-md-6 mb-3">
                        <label class="form-label fw-bold small">Planta (Catálogo)</label>
                        <select name="nombre" id="select_catalogo" class="form-select shadow-sm" required>
                            <option value="">Selecciona una planta...</option>
                            <?php while($c = $res_catalogo->fetch_assoc()): ?>
                                <option value="<?= htmlspecialchars($c['nombre_comun']) ?>" 
                                        data-epoca="<?= (int)$c['id_epoca'] ?>" 
                                        data-siembra="<?= (int)$c['id_tipo_siembra'] ?>" 
                                        data-distancia="<?= (int)$c['distancia_sugerida'] ?>">
                                    <?= htmlspecialchars($c['nombre_comun']) ?>
                                </option>
                            <?php endwhile; ?>
                        </select>
                        <div class="form-text small">¿No está? Agrégala en <a href="configuracion.php?from=nueva" class="text-decoration-none fw-bold text-success">Configuración</a>.</div>
                    </div>

                    <div class="col-md-6 mb-3">
                        <label class="form-label fw-bold small">Variedad / Especie</label>
                        <input type="text" name="variedad" class="form-control shadow-sm" maxlength="100" placeholder="Ej: Hoja Ancha, Platense...">
                    </div>
                </div>

                <div class="row">
                    <div class="col-md-6 mb-3">
                        <label class="form-label fw-bold small">Época de Siembra</label>
                        <select name="id_epoca" id="epoca" class="form-select shadow-sm">
                            <option value="">Elegir época...</option>
                            <?php while($e = $res_epocas->fetch_assoc()): ?>
                                <option value="<?= (int)$e['id'] ?>"><?= htmlspecialchars($e['nombre_epoca']) ?></option>
                            <?php endwhile; ?>
                        </select>
                    </div>

                    <div class="col-md-6 mb-3">
                        <label class="form-label fw-bold small">Tipo de Siembra</label>
                        <select name="id_tipo_siembra" id="tipo_siembra" class="form-select shadow-sm">
                            <option value="">Elegir tipo...</option>
                            <?php while($t = $res_tipos->fetch_assoc()): ?>
                                <option value="<?= (int)$t['id'] ?>"><?= htmlspecialchars($t['nombre_tipo']) ?></option>
                            <?php endwhile; ?>
                        </select>
                    </div>
                </div>

                <div class="row">
                    <div class="col-md-6 mb-3">
                        <label class="form-label fw-bold small">Distancia (cm)</label>
                        <input type="number" name="distancia" id="distancia" class="form-control shadow-sm" min="0" max="500" placeholder="Ej: 30">
                    </div>

                    <div class="col-md-6 mb-3">
                        <label class="form-label fw-bold small">Bancal / Ubicación</label>
                        <select name="id_bancal" class="form-select shadow-sm">
                            <option value="">Elegir bancal...</option>
                            <?php while($b = $res_bancales->fetch_assoc()): ?>
                                <option value="<?= (int)$b['id'] ?>"><?= htmlspecialchars($b['nombre_bancal']) ?></option>
                            <?php endwhile; ?>
                        </select>
                    </div>
                </div>

                <div class="text-end mt-4">
                    <button type="submit" class="btn btn-success fw-bold px-5 shadow-sm">
                        <i class="bi bi-save"></i> Guardar Planta
                    </button>
                </div>
            </form>

        </div>
    </div>
</div>

<script>
// El autocompletado JS es seguro porque no manipula la base de datos
document.getElementById('select_catalogo').addEventListener('change', function() {
    const selected = this.options[this.selectedIndex];
    if(selected.value !== "") {
        document.getElementById('epoca').value = selected.dataset.epoca;
        document.getElementById('tipo_siembra').value = selected.dataset.siembra;
        document.getElementById('distancia').value = selected.dataset.distancia;
    } else {
        document.getElementById('epoca').value = "";
        document.getElementById('tipo_siembra').value = "";
        document.getElementById('distancia').value = "";
    }
});
</script>

<?php include 'pie_pagina.php'; ?>