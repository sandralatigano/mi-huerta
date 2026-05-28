/**
 * Archivo: scripts.js
 * Descripción: Funciones JavaScript para interactividad, como validaciones dinámicas y alertas de confirmación con SweetAlert2.
 * Componente: UI / Scripts
 */



document.addEventListener('DOMContentLoaded', function() {
    const urlParams = new URLSearchParams(window.location.search);

    // --- 1. DETECCIÓN DE PARÁMETROS EN URL (SweetAlerts Automáticos) ---

    // Éxito: Registro eliminado (General) - Busca 'borrado' o 'deleted'
    if (urlParams.get('msg') === 'borrado' || urlParams.get('status') === 'deleted') {
        Swal.fire({
            icon: 'success',
            title: '¡Eliminado!',
            text: 'El registro se procesó correctamente.',
            showConfirmButton: false,
            timer: 2500,
            backdrop: `rgba(45, 106, 79, 0.2)`
        });
    }

    // Éxito: Guardado/Actualizado - Busca 'success'
   /*if (urlParams.get('status') === 'success') {
        Swal.fire({
            icon: 'success',
            title: '¡Excelente!',
            text: 'Los datos se guardaron correctamente.',
            confirmButtonColor: '#2d6a4f'
        });
    }*/

    // Error: Permisos insuficientes (Escudo de Sandra)
    if (urlParams.get('error') === 'prohibido') {
        Swal.fire({
            icon: 'error',
            title: 'Acceso Denegado',
            text: 'No tienes permisos para eliminar a un Administrador Principal.',
            confirmButtonColor: '#d33'
        });
    }

    // Error: Contraseña incorrecta (Para el operador)
    if (urlParams.get('error') === 'password_incorrecto') {
        Swal.fire({
            icon: 'error',
            title: 'Validación Fallida',
            text: 'La contraseña actual no es correcta.',
            confirmButtonColor: '#d33'
        });
    }

    // --- 2. CONFIRMACIÓN AUTOMÁTICA (Para botones con clase .btn-eliminar-conf) ---
    // Esta es la que agregamos para las tablas de Configuración
    const botonesEliminar = document.querySelectorAll('.btn-eliminar-conf');
    botonesEliminar.forEach(boton => {
        boton.addEventListener('click', function(e) {
            e.preventDefault();
            const url = this.getAttribute('href');

            Swal.fire({
                title: '¿Estás seguro?',
                text: "Este registro se eliminará de la configuración.",
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#2d6a4f',
                cancelButtonColor: '#d33',
                confirmButtonText: 'Sí, eliminar',
                cancelButtonText: 'Cancelar',
                reverseButtons: true,
                backdrop: `rgba(45, 106, 79, 0.2)`
            }).then((result) => {
                if (result.isConfirmed) {
                    window.location.href = url;
                }
            });
        });
    });
});

// --- 3. FUNCIONES GLOBALES (Llamadas desde el onclick de HTML) ---

// Confirmar borrado de usuario con MOTIVO
function confirmarBorradoUsuario(id, nombre) {
    Swal.fire({
        title: '¿Eliminar a ' + nombre + '?',
        text: "Ingresa el motivo de la baja:",
        icon: 'warning',
        input: 'text',
        showCancelButton: true,
        confirmButtonColor: '#d33',
        confirmButtonText: 'Confirmar Baja',
        cancelButtonText: 'Cancelar',
        inputValidator: (value) => {
            if (!value) return '¡Debes escribir un motivo!';
        }
    }).then((result) => {
        if (result.isConfirmed) {
            window.location.href = 'usuarios_borrar.php?id=' + id + '&motivo=' + encodeURIComponent(result.value);
        }
    });
}

// Borrar plantas del Panel Principal
function confirmarBorrado(id, nombre) {
    Swal.fire({
        title: '¿Eliminar planta?',
        text: "Vas a quitar la " + nombre + " de tu huerta.",
        icon: 'warning',
        showCancelButton: true,
        confirmButtonColor: '#2d6a4f',
        confirmButtonText: 'Sí, borrar',
        cancelButtonText: 'No'
    }).then((result) => {
        if (result.isConfirmed) {
            window.location.href = 'eliminar.php?id=' + id;
        }
    });
}

// Borrar registros del Seguimiento
function confirmarBorradoSeg(id, planta_id) {
    Swal.fire({
        title: '¿Borrar registro?',
        icon: 'question',
        showCancelButton: true,
        confirmButtonColor: '#2d6a4f',
        confirmButtonText: 'Eliminar'
    }).then((result) => {
        if (result.isConfirmed) {
            window.location.href = 'eliminar_seguimiento.php?id=' + id + '&planta_id=' + planta_id;
        }
    });
}