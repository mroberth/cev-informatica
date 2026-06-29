/**
 * CevAlert — Clase padre reutilizable para SweetAlert2
 *
 * Uso:
 *   CevAlert.success({ title: 'Hecho', text: 'Operación exitosa.' })
 *   CevAlert.error({ title: 'Error', text: 'Algo salió mal.' })
 *   CevAlert.warning({ title: 'Cuidado', text: 'Revisa los datos.' })
 *   CevAlert.info({ title: 'Info', text: 'Novedad disponible.' })
 *   CevAlert.question({ title: '¿Confirmar?', text: '¿Estás seguro?', confirmButtonText: 'Sí' })
 *   CevAlert.toast({ type: 'success', title: 'Guardado' })
 *
 * Cada método acepta cualquier opción válida de Swal.fire().
 * Las opciones proporcionadas sobrescriben los valores por defecto.
 */
export class CevAlert {

    /** Configuración base común a todos los tipos */
    static _base(opts = {}) {
        const base = {
            background: '#ffffff',
            backdrop: 'rgba(13, 110, 253, 0.2)',
            confirmButtonColor: '#0d6efd',
            cancelButtonColor: '#6c757d',
            ...opts,
        };
        base.customClass = {
            popup: 'cev-swal-popup',
            ...(base.customClass || {}),
        };
        return base;
    }

    /** Mensaje de éxito */
    static success(opts = {}) {
        return Swal.fire(this._base({
            icon: 'success',
            confirmButtonColor: '#198754',
            ...opts,
        }));
    }

    /** Mensaje de error */
    static error(opts = {}) {
        return Swal.fire(this._base({
            icon: 'error',
            confirmButtonColor: '#dc3545',
            ...opts,
        }));
    }

    /** Mensaje de advertencia */
    static warning(opts = {}) {
        return Swal.fire(this._base({
            icon: 'warning',
            confirmButtonColor: '#0d6efd',
            ...opts,
        }));
    }

    /** Mensaje informativo */
    static info(opts = {}) {
        return Swal.fire(this._base({
            icon: 'info',
            confirmButtonColor: '#0d6efd',
            ...opts,
        }));
    }

    /** Confirmación tipo pregunta */
    static question(opts = {}) {
        return Swal.fire(this._base({
            icon: 'question',
            showCancelButton: true,
            confirmButtonText: 'Aceptar',
            cancelButtonText: 'Cancelar',
            ...opts,
        }));
    }

    /** Toast ligero que se auto-cierra */
    static toast(opts = {}) {
        const { type = 'success', timer = 2500, ...rest } = opts;
        const iconMap = {
            success: { icon: 'success', confirmButtonColor: '#198754' },
            error:   { icon: 'error',   confirmButtonColor: '#dc3545' },
            warning: { icon: 'warning', confirmButtonColor: '#0d6efd' },
            info:    { icon: 'info',    confirmButtonColor: '#0d6efd' },
        };
        const iconOpts = iconMap[type] || iconMap.success;
        return Swal.fire(this._base({
            ...iconOpts,
            toast: true,
            backdrop: false,
            position: 'top-end',
            timer,
            showConfirmButton: false,
            timerProgressBar: true,
            ...rest,
        }));
    }

    /** Alerta de sesión por expirar con conteo regresivo */
    static sessionExpiring(opts = {}) {
        const {
            title = 'Sesión por Expirar',
            html = '',
            confirmButtonText = 'Mantener sesión activa',
            cancelButtonText = 'Cerrar sesión',
            timerDuration = 30,
            ...rest
        } = opts;

        let countdown = timerDuration;

        const swalOpts = this._base({
            title,
            html: html || `Tu sesión en el CEV está a punto de cerrarse debido a inactividad.<br><br>Tiempo restante: <b id="modal-timer" class="text-danger">${countdown}s</b>`,
            icon: 'warning',
            showCancelButton: true,
            confirmButtonText,
            cancelButtonText,
            confirmButtonColor: '#0d6efd',
            cancelButtonColor: '#dc3545',
            allowOutsideClick: false,
            allowEscapeKey: false,
            didOpen: () => {
                const interval = setInterval(() => {
                    countdown--;
                    const el = document.getElementById('modal-timer');
                    if (el) el.innerText = `${countdown}s`;
                    if (countdown <= 0) {
                        clearInterval(interval);
                        Swal.clickCancel();
                    }
                }, 1000);
                // guardar referencia para limpiar al cerrar
                swalOpts._countdownInterval = interval;
            },
            willClose: () => {
                if (swalOpts._countdownInterval) {
                    clearInterval(swalOpts._countdownInterval);
                }
            },
            ...rest,
        });

        return Swal.fire(swalOpts);
    }
}
