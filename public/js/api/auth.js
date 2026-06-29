import { apiClient } from "./api";

export const apiLogin = (formData) => {
    return apiClient.post('/iniciar_sesion', formData);
}