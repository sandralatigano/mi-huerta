<?php
/**
 * Archivo: pie_pagina.php
 * Descripción: Contiene el diseño de la parte inferior del sitio, incluyendo información de copyright y scripts.
 * Componente: Layout
 */
?>
<footer class="mt-auto py-4 border-top bg-white">
    <div class="container">
        <div class="d-flex flex-column flex-md-row justify-content-between align-items-center gap-3 text-center text-md-start">
            
            <span class="text-muted small">
                &copy; <?= date('Y') ?> <strong>Mi Huerta</strong> - Salta, Argentina 🇦🇷
            </span>
            
            <div class="d-flex align-items-center justify-content-center gap-3">
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

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<script src="js/scripts.js"></script>
</body>
</html>