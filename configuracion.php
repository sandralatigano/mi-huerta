<?php
/**
 * Archivo: configuracion.php
 * Descripción: Página de configuración del catálogo maestro.
 * Componente: Configuración / Catálogo Maestro
 */

include 'auth.php'; 
include 'conexion.php';
include 'encabezado_pagina.php'; 

// 1. Definimos origen para redirecciones
$from = isset($_GET['from']) ? $_GET['from'] : 'index';
$url_volver = ($from == 'nueva_planta') ? 'nueva_planta.php' : 'index.php';

// 2. Consultas para los selectores (Usando los nombres exactos de tu DB)
$res_ep_opt = $conexion->query("SELECT * FROM epocas ORDER BY nombre_epoca");
$res_ti_opt = $conexion->query("SELECT * FROM tipos_siembra ORDER BY nombre_tipo");
$res_tc_opt = $conexion->query("SELECT * FROM tipos_cultivo ORDER BY nombre_tipo");
$res_gr_opt = $conexion->query("SELECT * FROM grupos_rotacion ORDER BY nombre_grupo");
?>

<div class="container mt-4 mb-5">
    <?php if (isset($_GET['status']) && $_GET['status'] === 'success'): ?>
        <div class="alert alert-dismissible fade show shadow-sm" role="alert" 
            style="background-color: #d1e7dd !important; color: #0f5132 !important; border-left: 6px solid #2d6a4f !important; border-top: none; border-right: none; border-bottom: none;">
            <i class="bi bi-check-circle-fill me-2" style="color: #2d6a4f;"></i> 
            <strong>Agregado:</strong> El registro se agregó al con éxito.
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
    <?php endif; ?>
    
    <?php if (isset($_GET['status']) && $_GET['status'] === 'deleted'): ?>
        <div class="alert alert-dismissible fade show shadow-sm" role="alert" 
            style="background-color: #f8d7da; color: #842029; border-left: 6px solid #d33; border-top: none; border-right: none; border-bottom: none;">
            <i class="bi bi-trash-fill me-2" style="color: #d33;"></i> 
            <strong>Eliminado:</strong> El registro se quitó del sistema con éxito.
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
    <?php endif; ?>

    <div class="d-flex justify-content-between align-items-center mb-4 pb-2 border-bottom">
        <h4 class="text-secondary m-0 fw-normal">
            <i class="bi bi-gear-fill text-success"></i> Configuración del Catálogo Maestro
        </h4>
        <a href="<?= $url_volver ?>" class="btn btn-outline-secondary btn-sm">
            <i class="bi bi-arrow-left"></i> Volver
        </a>
    </div>

    <div class="card p-4 shadow-sm mb-4 border-0 rounded-3">
        <h5 class="fw-bold text-success border-bottom pb-2 mb-3">
            <i class="bi bi-plus-circle"></i> Agregar al Catálogo
        </h5>
        <form action="guardar_config.php" method="POST" class="row g-2 align-items-end">
            <input type="hidden" name="from" value="<?= $from ?>">
            <input type="hidden" name="tabla" value="catalogo">
            
            <div class="col-md-2">
                <label class="form-label small fw-bold text-secondary">Planta</label>
                <input type="text" name="nombre" class="form-control form-control-sm shadow-sm" required>
            </div>
            
            <div class="col-md-2">
                <label class="form-label small fw-bold text-secondary">Época</label>
                <select name="id_epoca" class="form-select form-select-sm shadow-sm">
                    <option value="">Elegir...</option>
                    <?php while($e = $res_ep_opt->fetch_assoc()): ?>
                        <option value="<?= $e['id'] ?>"><?= $e['nombre_epoca'] ?></option>
                    <?php endwhile; ?>
                </select>
            </div>

            <div class="col-md-2">
                <label class="form-label small fw-bold text-secondary">Siembra</label>
                <select name="id_tipo_siembra" class="form-select form-select-sm shadow-sm">
                    <option value="">Elegir...</option>
                    <?php while($t = $res_ti_opt->fetch_assoc()): ?>
                        <option value="<?= $t['id'] ?>"><?= $t['nombre_tipo'] ?></option>
                    <?php endwhile; ?>
                </select>
            </div>

            <div class="col-md-2">
                <label class="form-label small fw-bold text-secondary">Tipo Cultivo</label>
                <select name="id_tipo_cultivo" class="form-select form-select-sm shadow-sm">
                    <option value="">Elegir...</option>
                    <?php while($tc = $res_tc_opt->fetch_assoc()): ?>
                        <option value="<?= $tc['id'] ?>"><?= $tc['nombre_tipo'] ?></option>
                    <?php endwhile; ?>
                </select>
            </div>

            <div class="col-md-2">
                <label class="form-label small fw-bold text-secondary">Rotación</label>
                <select name="id_grupo_rotacion" class="form-select form-select-sm shadow-sm">
                    <option value="">Elegir...</option>
                    <?php 
                    $res_gr_opt->data_seek(0); // Reiniciamos el puntero por si se usó antes
                    while($gr = $res_gr_opt->fetch_assoc()): ?>
                        <option value="<?= $gr['id'] ?>"><?= $gr['nombre_grupo'] ?></option>
                    <?php endwhile; ?>
                </select>
            </div>

            <div class="col-md-2">
                <label class="form-label small fw-bold text-secondary">Riego</label>
                <input type="text" name="riego" class="form-control form-control-sm shadow-sm">
            </div>

            <div class="col-md-1">
                <label class="form-label small fw-bold text-secondary">Dist(cm)</label>
                <input type="number" name="distancia" class="form-control form-control-sm shadow-sm">
            </div>

            <div class="col-md-2">
                <label class="form-label small fw-bold text-secondary">Cosecha</label>
                <input type="text" name="cosecha" class="form-control form-control-sm shadow-sm">
            </div>
            <div class="col-md-1">
                <label class="form-label small fw-bold text-secondary">Días Cosecha</label>
                <input type="number" name="dias_cosecha_num" class="form-control form-control-sm shadow-sm" placeholder="Ej: 90">
            </div>
            <div class="col-md-1">
                <button type="submit" class="btn btn-success btn-sm w-100 fw-bold shadow-sm">Cargar</button>
            </div>
        </form>
    </div>

    <div class="card p-3 shadow-sm border-0 mb-4 rounded-3">
        <h5 class="fw-bold text-secondary border-bottom pb-2 mb-3">Plantas en Catálogo</h5>
        
        <?php 
        // Ejecutamos la consulta original del catálogo maestro
        $res_cat = $conexion->query("SELECT c.*, e.nombre_epoca, t.nombre_tipo, tc.nombre_tipo as cultivo_tipo, gr.nombre_grupo 
                                    FROM catalogo_plantas c 
                                    LEFT JOIN epocas e ON c.id_epoca = e.id 
                                    LEFT JOIN tipos_siembra t ON c.id_tipo_siembra = t.id 
                                    LEFT JOIN tipos_cultivo tc ON c.id_tipo_cultivo = tc.id
                                    LEFT JOIN grupos_rotacion gr ON c.id_grupo_rotacion = gr.id
                                    ORDER BY c.nombre_comun");
        ?>

        <div class="d-none d-md-block">
            <div class="table-responsive" style="max-height: 400px; overflow-y: auto;">
                <table class="table table-hover table-sm align-middle mt-2">
                    <thead class="table-dark small">
                        <tr>
                            <th>Nombre</th>
                            <th>Época</th>
                            <th>Siembra</th>
                            <th>Tipo Cultivo</th> 
                            <th>Grupo Rotación</th> 
                            <th>Riego</th>
                            <th>Cosecha</th>
                            <th>Dist.</th>
                            <th class="text-center">Acción</th>
                        </tr>
                    </thead>
                    <tbody class="small">
                        <?php 
                        if($res_cat && $res_cat->num_rows > 0):
                            $res_cat->data_seek(0); // Puntero al inicio para PC
                            while($cat = $res_cat->fetch_assoc()): ?>
                            <tr>
                                <td class="fw-bold text-success"><?= $cat['nombre_comun'] ?></td>
                                <td><?= $cat['nombre_epoca'] ?? '-' ?></td>
                                <td><?= $cat['nombre_tipo'] ?? '-' ?></td>
                                <td><span class="badge bg-light text-dark border"><?= $cat['cultivo_tipo'] ?? '-' ?></span></td>
                                <td><small><?= $cat['nombre_grupo'] ?? '-' ?></small></td>
                                <td><?= $cat['riego'] ?></td>
                                <td><?= $cat['tiempo_harvest'] ?? $cat['tiempo_cosecha'] ?></td>
                                <td><?= $cat['distancia_sugerida'] ?> cm</td>
                                <td class="text-center">
                                    <a href="borrar_config.php?tabla=catalogo&id=<?= $cat['id'] ?>&from=<?= $from ?>" 
                                       class="text-danger btn-eliminar-conf">
                                         <i class="bi bi-trash"></i>
                                    </a>
                                </td>
                            </tr>
                        <?php endwhile; endif; ?>
                    </tbody>
                </table>
            </div>
        </div>

        <div class="d-block d-md-none" style="max-height: 500px; overflow-y: auto; padding-right: 5px;">
            <?php 
            if($res_cat && $res_cat->num_rows > 0):
                $res_cat->data_seek(0); // Reiniciamos puntero para Móvil
                while($cat = $res_cat->fetch_assoc()): ?>
                <div class="card p-3 shadow-sm border rounded-3 mb-2 bg-white" style="font-size: 0.85rem;">
                    <div class="d-flex justify-content-between align-items-center border-bottom pb-1 mb-2">
                        <span class="fw-bold text-success" style="font-size: 0.95rem;">
                            <i class="bi bi-flower1"></i> <?= $cat['nombre_comun'] ?>
                        </span>
                        <a href="borrar_config.php?tabla=catalogo&id=<?= $cat['id'] ?>&from=<?= $from ?>" class="text-danger">
                            <i class="bi bi-trash fs-6"></i>
                        </a>
                    </div>
                    <div class="row g-1 text-muted">
                        <div class="col-6"><strong>Época:</strong> <?= $cat['nombre_epoca'] ?? '-' ?></div>
                        <div class="col-6"><strong>Siembra:</strong> <?= $cat['nombre_tipo'] ?? '-' ?></div>
                        <div class="col-6"><strong>Cultivo:</strong> <?= $cat['cultivo_tipo'] ?? '-' ?></div>
                        <div class="col-6"><strong>Distancia:</strong> <?= $cat['distancia_sugerida'] ?> cm</div>
                        <div class="col-12"><strong>Riego:</strong> <?= $cat['riego'] ?></div>
                        <div class="col-12"><strong>Cosecha:</strong> <?= $cat['tiempo_harvest'] ?? $cat['tiempo_cosecha'] ?></div>
                        <div class="col-12 small"><i class="bi bi-arrow-repeat text-success"></i> <strong>Rotación:</strong> <?= $cat['nombre_grupo'] ?? '-' ?></div>
                    </div>
                </div>
            <?php endwhile; else: ?>
                <div class="text-center py-3 text-muted">No hay plantas en el catálogo.</div>
            <?php endif; ?>
        </div>

    </div>

    <div class="row g-4">
        <?php
        $aux = [
            ['epoca', 'Épocas', 'nombre_epoca', 'epocas'],
            ['tipo_siembra', 'Tipos Siembra', 'nombre_tipo', 'tipos_siembra'],
            ['bancal', 'Bancales', 'nombre_bancal', 'bancales'],
            ['tipo_cultivo', 'Tipos de Cultivo', 'nombre_tipo', 'tipos_cultivo'],
            ['grupo_rotacion', 'Grupos Rotación', 'nombre_grupo', 'grupos_rotacion']
        ];
        foreach ($aux as $a): ?>
        <div class="col-md-4">
            <div class="card p-3 shadow-sm border-0 rounded-3">
                <h6 class="fw-bold border-bottom pb-2 text-success"><?= $a[1] ?></h6>
                <form action="guardar_config.php" method="POST" class="d-flex gap-1 mb-2 mt-2">
                    <input type="hidden" name="from" value="<?= $from ?>">
                    <input type="hidden" name="tabla" value="<?= $a[0] ?>">
                    <input type="text" name="nombre" class="form-control form-control-sm shadow-sm" required placeholder="Nuevo...">
                    <button type="submit" class="btn btn-success btn-sm shadow-sm">+</button>
                </form>
                <div style="max-height: 150px; overflow-y: auto;" class="border rounded p-1">
                    <table class="table table-sm small mb-0">
                        <?php 
                        $res = $conexion->query("SELECT * FROM {$a[3]} ORDER BY {$a[2]}");
                        while($r = $res->fetch_assoc()): ?>
                        <tr>
                            <td><?= $r[$a[2]] ?></td>
                            <td class="text-end">
                                <a href="borrar_config.php?tabla=<?= $a[0] ?>&id=<?= $r['id'] ?>&from=<?= $from ?>" 
                                   class="text-danger btn-eliminar-conf">
                                    <i class="bi bi-trash"></i>
                                </a>
                            </td>
                        </tr>
                        <?php endwhile; ?>
                    </table>
                </div>
            </div>
        </div>
        <?php endforeach; ?>
    </div>
</div>

<?php include 'pie_pagina.php'; ?>