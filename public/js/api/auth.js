import { apiClient } from "./api";

export const apiLogin = (formData) => {
    return apiClient.post('auth/login', formData);
}