import { initAdminShell } from '/js/modules/admin/common.js';
import { apiClient } from '/js/api/client.js';
import { CevAlert } from '/js/utils/CevAlert.js';

let dataTable = null;

const inicializarDataTable = () => {
  dataTable = new DataTable('#tablaSecciones', {
    ajax: {
      url: '/a/secciones/consultar_secciones',
      dataSrc: 'data',
    },
    columns: [
      { data: 'codigo_seccion' },
      { data: 'periodo' },
      { data: 'trayecto' },
      { data: 'turno' },
      {
        data: null,
        orderable: false,
        render: () => '<button class="btn btn-outline-primary btn-sm"><i class="bi bi-pencil"></i></button>',
      },
    ],
    language: {
      url: '/plugins/datatables/es-ES.json',
    },
    responsive: true,
    autoWidth: false,
    pageLength: 10,
    order: [[1, 'desc'], [2, 'asc']],
    dom: '<"d-flex flex-wrap align-items-center justify-content-between gap-2 mb-3"Bf>rt<"d-flex flex-wrap align-items-center justify-content-between gap-2 pt-3"<"d-inline-flex"i><"d-inline-flex"p>>',
    buttons: [
      {
        extend: 'excelHtml5',
        text: '<i class="bi bi-file-earmark-excel me-1"></i>Excel',
        className: 'btn btn-success',
        titleAttr: 'Exportar a Excel',
        exportOptions: {
          columns: [0, 1, 2, 3] // Exporta todo menos las Acciones
        }
      },
      {
        extend: 'pdfHtml5',
        text: '<i class="bi bi-file-earmark-pdf me-1"></i>PDF',
        className: 'btn btn-danger',
        titleAttr: 'Exportar a PDF',
        exportOptions: {
          columns: [0, 1, 2, 3] // Exporta todo menos las Acciones
        }
      }
    ],
    initComplete: function () {
      initEditarSec(dataTable);
    },
  });
};

const initEditarSec = (dataTable) => {
  const modal = document.getElementById('modalEditarSec');
  if (!modal) return;

  const form = document.getElementById('formEditarSec');
  const idInput = document.getElementById('editar_id');
  const idPeriodo = document.getElementById('editar_id_periodo');
  const idTrayecto = document.getElementById('editar_id_trayecto');
  const codigoInput = document.getElementById('editar_codigo_seccion');
  const turnoInput = document.getElementById('editar_turno');

  let periodosCargados = false;
  let trayectosCargados = false;

  const cargarPeriodosEdit = async () => {
    if (periodosCargados) return;
    try {
      const response = await apiClient.get('a/secciones/obtener_periodos');
      const periodos = response.data || [];
      periodos.forEach(p => {
        const opt = document.createElement('option');
        opt.value = p.id;
        opt.textContent = p.nombre + (p.estado === 'Activo' ? ' (Activo)' : '');
        idPeriodo.appendChild(opt);
      });
      periodosCargados = true;
    } catch (error) {
      console.error('Error al cargar períodos:', error);
    }
  };

  const cargarTrayectosEdit = async () => {
    if (trayectosCargados) return;
    try {
      const response = await apiClient.get('a/secciones/obtener_trayectos');
      const trayectos = response.data || [];
      trayectos.forEach(t => {
        const opt = document.createElement('option');
        opt.value = t.id;
        opt.textContent = t.nombre;
        idTrayecto.appendChild(opt);
      });
      trayectosCargados = true;
    } catch (error) {
      console.error('Error al cargar trayectos:', error);
    }
  };

  const showError = (field, msg) => {
    const errorElement = document.getElementById(`${field.id}Error`);
    if (errorElement) errorElement.textContent = msg;
    field.classList.add('is-invalid');
    field.classList.remove('is-valid');
  };

  const clearError = (field) => {
    const errorElement = document.getElementById(`${field.id}Error`);
    if (errorElement) errorElement.textContent = '';
    field.classList.remove('is-invalid');
    field.classList.add('is-valid');
  };

  function validarPeriodo() {
    if (!idPeriodo.value) {
      showError(idPeriodo, 'El período académico es obligatorio.');
      return false;
    }
    clearError(idPeriodo);
    return true;
  }

  function validarTrayecto() {
    if (!idTrayecto.value) {
      showError(idTrayecto, 'El trayecto es obligatorio.');
      return false;
    }
    clearError(idTrayecto);
    return true;
  }

  function validarCodigo() {
    const val = codigoInput.value.trim().toUpperCase();
    codigoInput.value = val;
    if (!val) {
      showError(codigoInput, 'El código de sección es obligatorio.');
      return false;
    }
    if (!/^IN-[1-4]1\d{2}$/.test(val)) {
      showError(codigoInput, 'El formato debe ser IN-X1YY (ej: IN-1101).');
      return false;
    }
    if (idTrayecto.value && val.charAt(3) !== idTrayecto.value) {
      showError(codigoInput, `El código debe iniciar con IN-${idTrayecto.value}.`);
      return false;
    }
    clearError(codigoInput);
    return true;
  }

  async function verificarCodigo() {
    if (!validarCodigo()) return false;
    try {
      const response = await apiClient.get(`a/secciones/verificar_codigo?codigo_seccion=${encodeURIComponent(codigoInput.value)}&id_excluir=${idInput.value}`);
      if (response.existe) {
        showError(codigoInput, 'El código de sección ya existe.');
        return false;
      }
    } catch { /* ignore */ }
    return true;
  }

  idTrayecto.addEventListener('change', () => {
    validarTrayecto();
    const trayectoId = idTrayecto.value;
    const currentCode = codigoInput.value.trim();
    if (trayectoId && currentCode === '') {
      codigoInput.value = `IN-${trayectoId}1`;
      codigoInput.setSelectionRange(codigoInput.value.length, codigoInput.value.length);
      codigoInput.focus();
    } else if (currentCode !== '') {
      validarCodigo();
    }
  });

  codigoInput.addEventListener('input', validarCodigo);

  dataTable.on('click', 'tbody .btn-outline-primary', async function () {
    const data = dataTable.row(this.closest('tr')).data();

    idInput.value = data.id;
    codigoInput.value = data.codigo_seccion;
    turnoInput.value = data.turno;

    form.querySelectorAll('.is-invalid, .is-valid').forEach(el => el.classList.remove('is-invalid', 'is-valid'));

    await cargarPeriodosEdit();
    await cargarTrayectosEdit();

    idPeriodo.value = data.id_periodo || '';
    idTrayecto.value = data.id_trayecto || '';

    new bootstrap.Modal(modal).show();
  });

  form.addEventListener('submit', async (e) => {
    e.preventDefault();

    const validaciones = [
      validarPeriodo(),
      validarTrayecto(),
      validarCodigo(),
    ];

    if (!validaciones.every(v => v === true)) return;

    const codigoOk = await verificarCodigo();
    if (!codigoOk) return;

    const btnSubmit = form.querySelector('[type="submit"]');
    const originalText = btnSubmit.innerHTML;
    btnSubmit.disabled = true;
    btnSubmit.innerHTML = '<span class="spinner-border spinner-border-sm me-2"></span> Actualizando...';

    try {
      const payload = {
        id: parseInt(idInput.value),
        id_periodo: parseInt(idPeriodo.value),
        id_trayecto: parseInt(idTrayecto.value),
        codigo_seccion: codigoInput.value.trim(),
        turno: turnoInput.value,
      };

      await apiClient.post('a/secciones/actualizar', payload);

      CevAlert.success({
        title: 'Actualización Exitosa',
        text: 'La sección ha sido actualizada correctamente.',
        confirmButtonText: 'Aceptar',
      });

      bootstrap.Modal.getInstance(modal).hide();
      dataTable.ajax.reload(null, false);
    } catch (error) {
      CevAlert.error({
        title: 'Error al actualizar',
        text: error.message || 'Ocurrió un error inesperado.',
        confirmButtonText: 'Aceptar',
      });
    } finally {
      btnSubmit.disabled = false;
      btnSubmit.innerHTML = originalText;
    }
  });
};

const initConsultarSecciones = () => {
  initAdminShell();
  inicializarDataTable();
};

if (document.readyState === 'loading') {
  document.addEventListener('DOMContentLoaded', initConsultarSecciones);
} else {
  initConsultarSecciones();
}
