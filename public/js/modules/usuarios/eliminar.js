import { apiClient } from '/js/api/client.js';
import { CevAlert } from '/js/utils/CevAlert.js';

const manejarEliminacion = async (usuario) => {
  const resultado = await CevAlert.question({
    title: 'Desactivar usuario',
    text: `¿Estás seguro de desactivar a ${usuario.nombre} ${usuario.apellido}? El usuario no podrá acceder al sistema hasta que sea reactivado.`,
    confirmButtonText: 'Sí, desactivar',
    cancelButtonText: 'Cancelar',
  });

  if (!resultado.isConfirmed) return;

  try {
    await apiClient.post('a/usuarios/cambiar_estado', {
      id: usuario.id,
      estado: 'inactivo',
    });

    CevAlert.success({
      title: 'Desactivado',
      text: `El usuario ${usuario.nombre} ${usuario.apellido} ha sido desactivado.`,
    });

    document.dispatchEvent(new CustomEvent('cev:usuario-actualizado'));
  } catch (error) {
    CevAlert.error({
      title: 'Error',
      text: error.message || 'No se pudo desactivar el usuario.',
    });
  }
};

export const initEliminarUsuarios = (dataTableInstance) => {
  if (!dataTableInstance) return;

  document.getElementById('tablaUsuarios').addEventListener('click', (e) => {
    const btn = e.target.closest('.btn-outline-danger');
    if (!btn) return;

    const tr = btn.closest('tr');
    if (!tr) return;

    const rowData = dataTableInstance.row(tr).data();
    if (rowData) manejarEliminacion(rowData);
  });
};
