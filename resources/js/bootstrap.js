import axios from 'axios';
window.axios = axios;
window.axios.defaults.headers.common['X-Requested-With'] = 'XMLHttpRequest';

/**
 * Echo exposes an expressive API for subscribing to channels and listening
 * for events that are broadcast by Laravel. Echo and event broadcasting
 * allow your team to quickly build robust real-time web applications.
 */

import './web/useChannels.js';

// Interceptor para erros 403 - Acesso Negado
window.axios.interceptors.response.use(
    response => response,
    error => {
        if (error.response?.status === 403) {
            const msg = error.response.data?.message || 'Acesso negado.';
            if (window.Alerts && window.Alerts.showError) {
                window.Alerts.showError({ msg });
            }
            setTimeout(() => {
                window.location.href = window.__HOME_URL__ || '/';
            }, 1500);
        }
        return Promise.reject(error);
    }
);
