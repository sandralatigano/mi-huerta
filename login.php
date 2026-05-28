
<?php
/**
 * Archivo: login.php
 * Descripción: Página de autenticación para acceder al sistema de gestión de huertas.
 * Componente: Autenticación
 */ 
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Ingreso - Mi Huerta</title>
    <link rel="icon" type="image/png" href="img/ramita.png">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">
</head>
<body style="background-color: #f1f3f2;">

    <header style="background-color: #1b4332; padding: 2rem 0; margin-bottom: 3rem; box-shadow: 0 4px 10px rgba(0,0,0,0.1);">
        <div class="container text-center">
            <h2 class="text-white fw-bold m-0">
                🌿 Mi Huerta | <span style="font-weight: 300; opacity: 0.8;">Ingreso</span>
            </h2>
        </div>
    </header>

    <div class="container">
        <div class="row justify-content-center">
            <div class="col-md-5 col-lg-4">
                <div class="card border-0 shadow-sm" style="border-radius: 15px;">
                    <div class="card-body p-4">
                        <p class="text-center text-muted mb-4">Ingresá para gestionar tus cultivos</p>
                        
                        <?php if(isset($_GET['error'])): ?>
                            <?php if($_GET['error'] == 'sesion_expirada'): ?>
                                <div class="alert alert-warning py-2 small text-center border-0" style="border-radius: 10px;">
                                    <i class="bi bi-clock-history me-2"></i> Sesión expirada por inactividad.
                                </div>
                            <?php else: ?>
                                <div class="alert alert-danger py-2 small text-center border-0" style="border-radius: 10px;">
                                    <i class="bi bi-exclamation-triangle-fill me-2"></i> Datos incorrectos.
                                </div>
                            <?php endif; ?>
                        <?php endif; ?>

                        <form action="validar_login.php" method="POST" autocomplete="off">
                            <div class="mb-3">
                                <label class="form-label small fw-bold text-secondary">Usuario</label>
                                <div class="input-group">
                                    <span class="input-group-text bg-light border-0"><i class="bi bi-person text-success"></i></span>
                                    <input type="text" name="usuario" class="form-control bg-light border-0" placeholder="Nombre de usuario" required autofocus>
                                </div>
                            </div>
                            
                            <div class="mb-3">
                                <label class="form-label small fw-bold text-secondary">Contraseña</label>
                                <div class="input-group">
                                    <span class="input-group-text bg-light border-0"><i class="bi bi-lock text-success"></i></span>
                                    <input type="password" name="password" class="form-control bg-light border-0" placeholder="••••••••" required>
                                </div>
                            </div>
                            
                            <button type="submit" class="btn btn-success w-100 fw-bold py-2 mt-3" style="background-color: #2d6a4f; border: none; border-radius: 10px;">
                                Entrar <i class="bi bi-box-arrow-in-right ms-2"></i>
                            </button>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="py-4"></div>

    <footer class="mt-auto py-4 border-top">
        <div class="container">
            <div class="d-flex flex-column flex-md-row justify-content-between align-items-center gap-3">
                <span class="text-muted small">
                    &copy; <?= date('Y') ?> <strong>Mi Huerta</strong> - Salta, Argentina 🇦🇷
                </span>
                
                <div class="d-flex align-items-center gap-3">
                    <span class="badge rounded-pill bg-secondary px-3" style="font-size: 0.7rem; opacity: 0.8;">
                        v1.2.0-stable
                    </span>
                    <span class="text-muted" style="font-size: 0.75rem;">
                        Desarrollado con <i class="bi bi-heart-fill text-danger"></i> por Sandra
                    </span>
                </div>
            </div>
        </div>
    </footer>

</body>
</html>