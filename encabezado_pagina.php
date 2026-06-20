<?php
/**
 * Archivo: encabezado_pagina.php
 * Descripción: Contiene el diseño de la parte superior del sitio, incluyendo menús de navegación y llamadas a hojas de estilo.
 * Componente: Layout
 */
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Panel de mi Huerta</title>
    <link rel="icon" type="image/png" href="img/ramita.png">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.0/font/bootstrap-icons.css">
    <link rel="stylesheet" href="css/estilos.css">
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
</head>
<body>
    <header style="background-color: #1b4332; padding: 0.5rem 0; margin-bottom: 2rem; box-shadow: 0 4px 10px rgba(0,0,0,0.1);">
        <nav class="navbar navbar-expand-lg navbar-dark" style="background-color: #1b4332;">
            <div class="container">
                <a class="navbar-brand fw-bold" href="index.php">
                    🌿 Mi Huerta
                </a>

                <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav" aria-controls="navbarNav" aria-expanded="false" aria-label="Toggle navigation">
                    <span class="navbar-toggler-icon"></span>
                </button>

                <div class="collapse navbar-collapse" id="navbarNav">
                    <ul class="navbar-nav me-auto mb-2 mb-lg-0">
                        <li class="nav-item">
                            <a class="nav-link" href="index.php"><i class="bi bi-house-door"></i> Panel</a>
                        </li>

                        <li class="nav-item">
                            <a class="nav-link" href="informes.php"><i class="bi bi-bar-chart-line"></i> Informes</a>
                        </li>

                        <?php if ($mi_rol == 1 || $mi_rol == 2): ?>
                        <li class="nav-item">
                            <a class="nav-link" href="usuarios_gestion.php"><i class="bi bi-people"></i> Usuarios</a>
                        </li>
                        <?php endif; ?>

                        <?php if ($mi_rol == 1 || $mi_rol == 2): ?>
                        <li class="nav-item">
                            <a class="nav-link" href="configuracion.php"><i class="bi bi-gear"></i> Configuración</a>
                        </li>
                        <?php endif; ?>

                        <li class="nav-item">
                            <a class="nav-link" href="usuarios_perfil.php"><i class="bi bi-person-circle"></i> Mi Perfil</a>
                        </li>
                    </ul>

                    <div class="d-flex align-items-center mt-3 mt-lg-0">
                        <span class="navbar-text text-white me-3 small">
                            <i class="bi bi-person-badge me-1"></i> <?php echo htmlspecialchars($_SESSION['nombre_real']); ?>
                        </span>
                        <a href="cerrar_sesion.php" class="btn btn-outline-light btn-sm fw-bold shadow-sm">
                            <i class="bi bi-box-arrow-right"></i> Salir
                        </a>
                    </div>
                </div>
            </div>
        </nav>
    </header>