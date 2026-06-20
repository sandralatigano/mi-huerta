<?php
/**
 * Archivo: index.php
 * Descripción: Panel principal con diseño responsivo garantizado mediante duplicación de vista (Tabla para PC / Tarjetas para Celular).
 * Componente: Gestión de Plantas
 */
include 'auth.php'; 
include 'conexion.php';
include 'encabezado_pagina.php'; 

// --- CONFIGURACIÓN DE PAGINADO ---
$por_pagina = 8; 
$pagina = isset($_GET['pagina']) ? (int)$_GET['pagina'] : 1;
$inicio = ($pagina > 1) ? ($pagina * $por_pagina) - $por_pagina : 0;

// --- CONFIGURACIÓN DE ORDENAMIENTO ---
$columnas_permitidas = ['id', 'nombre', 'variedad', 'epoca', 'tipo', 'distancia', 'nombre_bancal'];
$columna = (isset($_GET['orden']) && in_array($_GET['orden'], $columnas_permitidas)) ? $_GET['orden'] : 'id';
$direccion = (isset($_GET['dir']) && $_GET['dir'] == 'ASC') ? 'ASC' : 'DESC';
$siguiente_dir = ($direccion == 'ASC') ? 'DESC' : 'ASC';

// --- BÚSQUEDA ---
$buscar = isset($_GET['q']) ? $_GET['q'] : "";
$busqueda_param = "%$buscar%";

// --- CONSULTA PARA PAGINADO (Modificada para incluir ubicación en la búsqueda) ---
$total_query = "SELECT COUNT(*) as total FROM plantas p 
                LEFT JOIN bancales b ON p.id_bancal = b.id 
                WHERE p.nombre LIKE ? OR p.variedad LIKE ? OR b.nombre_bancal LIKE ?";
$stmt_total = $conexion->prepare($total_query);
$stmt_total->bind_param("sss", $busqueda_param, $busqueda_param, $busqueda_param);
$stmt_total->execute();
$total_filas = $stmt_total->get_result()->fetch_assoc()['total'];
$total_paginas = ceil($total_filas / $por_pagina);

// --- CONSULTA DE DATOS (Modificada para filtrar también por ubicación) ---
$query_datos = "SELECT p.*, e.nombre_epoca, t.nombre_tipo, b.nombre_bancal, c.tiempo_cosecha, gr.sugerencia_siguiente,
                (SELECT MAX(fecha) FROM seguimiento WHERE id_planta = p.id AND accion = 'Riego') as ultimo_riego
                FROM plantas p
                LEFT JOIN epocas e ON p.id_epoca = e.id
                LEFT JOIN tipos_siembra t ON p.id_tipo_siembra = t.id
                LEFT JOIN bancales b ON p.id_bancal = b.id
                LEFT JOIN catalogo_plantas c ON p.nombre = c.nombre_comun
                LEFT JOIN grupos_rotacion gr ON c.id_grupo_rotacion = gr.id
                WHERE p.nombre LIKE ? OR p.variedad LIKE ? OR b.nombre_bancal LIKE ?
                ORDER BY $columna $direccion LIMIT ?, ?";

$stmt_datos = $conexion->prepare($query_datos);
$stmt_datos->bind_param("sssii", $busqueda_param, $busqueda_param, $busqueda_param, $inicio, $por_pagina);
$stmt_datos->execute();

// Guardamos los datos en un array para poder iterarlo dos veces (una para PC y otra para celular)
$resultado_datos = $stmt_datos->get_result();
$plantas_array = [];
while($row = $resultado_datos->fetch_assoc()) {
    $plantas_array[] = $row;
}
?>

<div class="container mt-4">

    <?php if (isset($_GET['status'])): ?>
        <div class="alert alert-success alert-dismissible fade show border-0 shadow-sm mb-4" role="alert" style="color: #000 !important;">
            <i class="bi bi-check-circle-fill me-2 text-success"></i>
            <span class="fw-bold">
                <?php 
                    $st = $_GET['status'];
                    if($st == 'updated') echo "¡Planta actualizada con éxito!";
                    if($st == 'success') echo "¡Nueva planta agregada a la huerta!";
                    if($st == 'riego_ok') echo "¡Riego general registrado! 💧";
                    if($st == 'deleted') echo "¡Planta y su historial eliminados correctamente!";
                ?>
            </span>
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
    <?php endif; ?>

    <div class="card mb-3 border-0 shadow-sm rounded-3">
        <div class="card-body p-3 d-flex flex-wrap justify-content-between align-items-center gap-3">
            <div class="d-flex flex-column flex-grow-1" style="max-width: 500px;">
                <form class="d-flex gap-2 w-100" method="GET">
                    <input type="text" name="q" class="form-control form-control-sm shadow-sm" placeholder="Buscar en la huerta..." value="<?= htmlspecialchars($buscar) ?>">
                    <input type="hidden" name="orden" value="<?= htmlspecialchars($columna) ?>">
                    <input type="hidden" name="dir" value="<?= htmlspecialchars($direccion) ?>">
                    <button type="submit" class="btn btn-success btn-sm px-3"><i class="bi bi-search"></i></button>
                </form>
                <small class="text-muted mt-1 ps-1" style="font-size: 0.75rem;">
                    <i class="bi bi-info-circle text-success"></i> Puedes buscar por nombre de planta, variedad o ubicación (bancal).
                </small>
            </div>
            
            <div class="d-flex gap-2 align-self-start align-self-sm-center">
                <button type="button" class="btn btn-info btn-sm fw-bold text-white shadow-sm px-3" onclick="confirmarRiegoMasivo()">
                    <i class="bi bi-droplet-fill"></i> Riego General
                </button>
                <a href="nueva_planta.php" class="btn btn-success fw-bold px-4 shadow-sm">
                    <i class="bi bi-plus-circle"></i> Agregar Planta
                </a>
            </div>
        </div>
    </div>

    <div class="d-none d-md-block card shadow-sm border-0 rounded-3 mb-4">
        <div class="card-body p-0"> 
            <div class="table-responsive">
                <table class="table align-middle mb-0 table-hover">
                    <thead>
                        <tr>
                            <th class="ps-3">
                                <a href="?orden=nombre&dir=<?= $siguiente_dir ?>&q=<?= urlencode($buscar) ?>">
                                    NOMBRE <?= ($columna == 'nombre') ? ($direccion == 'ASC' ? '↑' : '↓') : '⇅' ?>
                                </a>
                            </th>
                            <th>
                                <a href="?orden=variedad&dir=<?= $siguiente_dir ?>&q=<?= urlencode($buscar) ?>">
                                    VARIEDAD <?= ($columna == 'variedad') ? ($direccion == 'ASC' ? '↑' : '↓') : '⇅' ?>
                                </a>
                            </th>
                            <th>ÉPOCA / TIPO</th>
                            <th>
                                <a href="?orden=nombre_bancal&dir=<?= $siguiente_dir ?>&q=<?= urlencode($buscar) ?>">
                                    UBICACIÓN <?= ($columna == 'nombre_bancal') ? ($direccion == 'ASC' ? '↑' : '↓') : '⇅' ?>
                                </a>
                            </th>
                            <th>ROTACIÓN SUGERIDA</th> 
                            <th class="text-center pe-3">ACCIONES</th>
                        </tr>
                    </thead>
                    <tbody class="small">
                        <?php if(empty($plantas_array)): ?>
                            <tr><td colspan="6" class="text-center py-4 text-muted">No se encontraron plantas.</td></tr>
                        <?php endif; ?>
                        
                        <?php foreach($plantas_array as $row): 
                            $fecha_siembra = new DateTime($row['fecha_siembra'] ?? 'now');
                            $hoy = new DateTime();
                            $dias_vida = $hoy->diff($fecha_siembra)->days;
                            $t_estimado = (int)($row['tiempo_cosecha'] ?? 0);

                            $color_semaforo = "text-info"; 
                            $msg_cosecha = "Recién sembrado / Creciendo";

                            if ($t_estimado > 0) {
                                if ($dias_vida > ($t_estimado + 15)) { $color_semaforo = "text-purple"; $msg_cosecha = "Pasada / Floración / Semilla"; }
                                elseif ($dias_vida >= $t_estimado) { $color_semaforo = "text-success"; $msg_cosecha = "¡Lista para cosechar!"; }
                                elseif ($dias_vida >= ($t_estimado * 0.7)) { $color_semaforo = "text-warning"; $msg_cosecha = "Cosecha próxima"; }
                            }
                      
                            $alerta_riego = true; 
                            if (!empty($row['ultimo_riego'])) {
                                $diff_riego = $hoy->diff(new DateTime($row['ultimo_riego']))->days;
                                if ($diff_riego < 2) $alerta_riego = false;
                            }
                        ?>
                        <tr>
                            <td class="ps-3 py-3">
                                <div class="d-flex align-items-center">
                                    <i class="bi bi-circle-fill me-2 <?= $color_semaforo ?>" title="<?= $msg_cosecha ?>" style="font-size: 0.65rem;"></i>
                                    <span class="fw-bold text-dark me-2"><?= htmlspecialchars($row['nombre']) ?></span>
                                    <i class="bi bi-droplet-fill <?= $alerta_riego ? 'text-danger' : 'text-info' ?>" title="Último riego: <?= htmlspecialchars($row['ultimo_riego'] ?? 'Nunca') ?>"></i>
                                </div>
                                <div class="small text-muted" style="font-size: 0.7rem;">
                                    <i class="bi bi-hourglass-split"></i> <?= $dias_vida ?> días de vida
                                </div>
                            </td>
                            <td class="text-muted"><?= htmlspecialchars($row['variedad']) ?></td>
                            <td>
                                <?= htmlspecialchars($row['nombre_epoca'] ?: '-') ?>
                                <span class="badge bg-light text-dark border rounded-pill small ms-1" style="font-size: 0.65rem;"><?= htmlspecialchars($row['nombre_tipo'] ?? 'General') ?></span>
                            </td>
                            <td>
                                <i class="bi bi-grid-3x3-gap text-muted me-1"></i>
                                <span><?= htmlspecialchars($row['nombre_bancal'] ?: 'Sin asignar') ?></span>
                            </td>
                            <td>
                                <div class="sugerencia-box">
                                    <?php if($row['sugerencia_siguiente']): ?>
                                        <i class="bi bi-lightbulb text-success"></i> <?= htmlspecialchars($row['sugerencia_siguiente']) ?>
                                    <?php else: ?>
                                        <span class="text-muted small">Sin datos</span>
                                    <?php endif; ?>
                                </div>
                            </td>
                            <td class="text-center pe-3">
                                <div class="btn-group shadow-sm">
                                    <a href="seguimiento.php?id=<?= (int)$row['id'] ?>" class="btn btn-sm btn-outline-primary" title="Ver Seguimiento"><i class="bi bi-eye"></i></a>
                                    <a href="editar_planta.php?id=<?= (int)$row['id'] ?>" class="btn btn-sm btn-outline-warning" title="Editar"><i class="bi bi-pencil"></i></a>
                                    <button onclick="confirmarBorrado(<?= (int)$row['id'] ?>)" class="btn btn-sm btn-outline-danger" title="Eliminar"><i class="bi bi-trash"></i></button>
                                </div>
                            </td>  
                        </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>


    <div class="d-block d-md-none mb-4">
        <?php if(empty($plantas_array)): ?>
            <div class="text-center py-4 text-muted bg-white rounded-3 shadow-sm">No se encontraron plantas.</div>
        <?php endif; ?>

        <?php foreach($plantas_array as $row): 
            $fecha_siembra = new DateTime($row['fecha_siembra'] ?? 'now');
            $hoy = new DateTime();
            $dias_vida = $hoy->diff($fecha_siembra)->days;
            $t_estimado = (int)($row['tiempo_cosecha'] ?? 0);

            $color_semaforo = "text-info"; 
            if ($t_estimado > 0) {
                if ($dias_vida > ($t_estimado + 15)) { $color_semaforo = "text-purple"; }
                elseif ($dias_vida >= $t_estimado) { $color_semaforo = "text-success"; }
                elseif ($dias_vida >= ($t_estimado * 0.7)) { $color_semaforo = "text-warning"; }
            }
            
            $alerta_riego = true; 
            if (!empty($row['ultimo_riego'])) {
                $diff_riego = $hoy->diff(new DateTime($row['ultimo_riego']))->days;
                if ($diff_riego < 2) $alerta_riego = false;
            }
        ?>
        <div class="card shadow-sm border-0 rounded-3 mb-3" style="background-color: #ffffff;">
            <div class="card-body p-3">
                <div class="d-flex justify-content-between align-items-center border-bottom pb-2 mb-2">
                    <div class="d-flex align-items-center">
                        <i class="bi bi-circle-fill me-2 <?= $color_semaforo ?>" style="font-size: 0.75rem;"></i>
                        <h5 class="text-dark fw-bold m-0" style="font-size: 1.05rem;"><?= htmlspecialchars($row['nombre']) ?></h5>
                    </div>
                    <div>
                        <i class="bi bi-droplet-fill <?= $alerta_riego ? 'text-danger' : 'text-info' ?> fs-5"></i>
                    </div>
                </div>

                <div class="small text-muted mb-2">
                    <strong>Variedad:</strong> <?= htmlspecialchars($row['variedad'] ?: '-') ?><br>
                    <strong>Época/Tipo:</strong> <?= htmlspecialchars($row['nombre_epoca'] ?: '-') ?> (<?= htmlspecialchars($row['nombre_tipo'] ?? 'General') ?>)<br>
                    <strong>Ubicación:</strong> <i class="bi bi-grid-3x3-gap text-muted"></i> <?= htmlspecialchars($row['nombre_bancal'] ?: 'Sin asignar') ?><br>
                    <strong>Edad:</strong> <?= $dias_vida ?> días de vida
                </div>

                <div class="p-2 rounded-2 mb-3 bg-light" style="font-size: 0.75rem;">
                    <strong>Rotación sugerida:</strong><br>
                    <?php if($row['sugerencia_siguiente']): ?>
                        <i class="bi bi-lightbulb text-success"></i> <?= htmlspecialchars($row['sugerencia_siguiente']) ?>
                    <?php else: ?>
                        <span class="text-muted">Sin datos</span>
                    <?php endif; ?>
                </div>

                <div class="d-flex gap-2">
                    <a href="seguimiento.php?id=<?= (int)$row['id'] ?>" class="btn btn-sm btn-outline-primary flex-grow-1 text-center py-2">
                        <i class="bi bi-eye"></i> Historial
                    </a>
                    <a href="editar_planta.php?id=<?= (int)$row['id'] ?>" class="btn btn-sm btn-outline-warning flex-grow-1 text-center py-2">
                        <i class="bi bi-pencil"></i> Editar
                    </a>
                    <button onclick="confirmarBorrado(<?= (int)$row['id'] ?>)" class="btn btn-sm btn-outline-danger px-3 py-2">
                        <i class="bi bi-trash"></i>
                    </button>
                </div>
            </div>
        </div>
        <?php endforeach; ?>
    </div>

    <nav class="mt-4 pb-5">
        <ul class="pagination pagination-sm justify-content-center">
            <?php for($i=1; $i<=$total_paginas; $i++): ?>
                <li class="page-item <?= ($i==$pagina) ? 'active' : '' ?>">
                    <a class="page-link shadow-sm" href="?pagina=<?= $i ?>&q=<?= urlencode($buscar) ?>&orden=<?= htmlspecialchars($columna) ?>&dir=<?= htmlspecialchars($direccion) ?>"><?= $i ?></a>
                </li>
            <?php endfor; ?>
        </ul>
    </nav>
</div>

<script>
function confirmarRiegoMasivo() {
    Swal.fire({
        title: '¿Regaste todo hoy?',
        text: "Se registrará el riego para todas las plantas",
        icon: 'question',
        showCancelButton: true,
        confirmButtonColor: '#2d6a4f',
        confirmButtonText: 'Sí, registrar',
        cancelButtonText: 'Cancelar'
    }).then((result) => {
        if (result.isConfirmed) {
            const form = document.createElement('form');
            form.method = 'POST'; form.action = 'acciones_masivas.php';
            const hiddenField = document.createElement('input');
            hiddenField.type = 'hidden'; hiddenField.name = 'accion'; hiddenField.value = 'riego_general';
            form.appendChild(hiddenField);
            document.body.appendChild(form);
            form.submit();
        }
    })
}

function confirmarBorrado(id) {
    Swal.fire({
        title: '¿Estás segura?',
        text: "Se borrará la planta y todo su historial.",
        icon: 'warning',
        showCancelButton: true,
        confirmButtonColor: '#d33',
        confirmButtonText: 'Sí, eliminar',
        cancelButtonText: 'Cancelar'
    }).then((result) => {
        if (result.isConfirmed) {
            window.location.href = 'borrar_planta.php?id=' + id;
        }
    })
}
</script>
<?php include 'pie_pagina.php'; ?>