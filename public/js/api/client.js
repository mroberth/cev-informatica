// En un monolito, la raíz es relativa. Dejamos una barra para que parta de la base del host actual.
const API_BASE_URL = '/';

class ApiClient {
  constructor(baseUrl = API_BASE_URL) {
    this.baseUrl = baseUrl;
    this._refrescando = false;
  }

  async request(endpoint, options = {}) {
    const token = localStorage.getItem('token');

    const headers = {
      'Content-Type': 'application/json',
      'Accept': 'application/json',
      ...options.headers,
    };

    if (token) {
      headers['Authorization'] = `Bearer ${token}`;
    }

    // Aseguramos que el endpoint no duplique barras inclinadas al concatenar
    const urlLimpia = `${this.baseUrl}${endpoint.replace(/^\//, '')}`;
    const config = { ...options, headers };
    
    let response = await fetch(urlLimpia, config);

    // Ajustamos la condición de refresco para que coincida con tu endpoint del backend
    if (response.status === 401 && !this._refrescando && !endpoint.includes('refresh')) {
      const renovado = await this._refrescarToken();
      if (renovado) {
        headers['Authorization'] = `Bearer ${localStorage.getItem('token')}`;
        config.headers = headers;
        response = await fetch(urlLimpia, config);
      } else {
        localStorage.clear();
        sessionStorage.setItem('redirect_reason', 'expired');
        window.location.href = '/login';
        throw new Error('Sesión expirada');
      }
    }

    if (!response.ok) {
      const body = await response.json().catch(() => ({}));
      const mensajeDelServidor = body.error || body.data?.error || body.message || '';

      const erroresHTTP = {
        400: 'Solicitud inválida. Verifica los datos enviados.',
        401: 'No autorizado. Tu sesión podría haber expirado.',
        403: 'No tienes permisos para realizar esta acción.',
        404: 'El recurso solicitado no existe.',
        419: 'La sesión ha expirado. Inicia sesión nuevamente.',
        422: 'Los datos enviados no son válidos.',
        429: 'Demasiadas peticiones. Espera unos segundos e inténtalo de nuevo.',
        500: 'Error interno del servidor. Intenta más tarde.',
        503: 'Servicio no disponible. El servidor está en mantenimiento.',
      };

      const status = response.status;
      const prioridadServidor = status === 400 || status === 422;

      let errorMsg = prioridadServidor
        ? (mensajeDelServidor || erroresHTTP[status])
        : (erroresHTTP[status] || mensajeDelServidor);

      throw new Error(errorMsg || `Error del servidor (${status})`);
    }

    try {
      return await response.json();
    } catch (_) {
      throw new Error(
        `El servidor respondió con un formato inesperado (status ${response.status}). ` +
        `Verifica que la ruta '${endpoint}' con método ${options.method || 'GET'} esté definida en el backend.`
      );
    }
  }

  async _refrescarToken() {
    this._refrescando = true;
    const refreshToken = localStorage.getItem('refresh_token');
    if (!refreshToken) {
      this._refrescando = false;
      return false;
    }

    try {
      const urlRefresh = `${this.baseUrl}refresh`; 
      
      const res = await fetch(urlRefresh, {
        method: 'POST',
        headers: { 'Content-Type': 'application/json', 'Accept': 'application/json' },
        credentials: 'include',
        body: JSON.stringify({ refresh_token: refreshToken }),
      });

      if (!res.ok) {
        this._refrescando = false;
        return false;
      }

      const body = await res.json();
      const data = body.data || body;

      if (data.access_token) {
        localStorage.setItem('token', data.access_token);
        if (data.refresh_token) {
          localStorage.setItem('refresh_token', data.refresh_token);
        }
        this._refrescando = false;
        return true;
      }

      this._refrescando = false;
      return false;
    } catch {
      this._refrescando = false;
      return false;
    }
  }

  get(endpoint, options = {}) {
    return this.request(endpoint, { ...options, method: 'GET' });
  }

  post(endpoint, data, options = {}) {
    return this.request(endpoint, {
      ...options,
      method: 'POST',
      body: JSON.stringify(data),
    });
  }

  put(endpoint, data, options = {}) {
    return this.request(endpoint, {
      ...options,
      method: 'PUT',
      body: JSON.stringify(data),
    });
  }

  delete(endpoint, options = {}) {
    return this.request(endpoint, { ...options, method: 'DELETE' });
  }
}

export const apiClient = new ApiClient();
export default ApiClient;