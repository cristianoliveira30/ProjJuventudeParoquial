import axios from "axios";

export const api = axios.create({
    withCredentials: true,
    xsrfCookieName: "XSRF-TOKEN",
    xsrfHeaderName: "X-XSRF-TOKEN",
    headers: {
        "X-Requested-With": "XMLHttpRequest",
        Accept: "application/json",
    },
});

const token = document.querySelector('meta[name="csrf-token"]')?.content;
if (token) api.defaults.headers.common["X-CSRF-TOKEN"] = token;

api.interceptors.response.use(
    response => response,
    error => {
        if (error?.response?.status === 419) window.location.reload();

        return Promise.reject(error);
    },
);
