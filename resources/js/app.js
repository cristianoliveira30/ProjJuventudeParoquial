import "bootstrap-icons/font/bootstrap-icons.css";
import "./accessibility";
import { api } from "./api/useApi.js";
import "./bootstrap";
import { useAlerts } from "./composables/useAlerts.js";
import { ensureFlowbite } from "./lib/lazyVendors.js";
import { loadPageScripts } from "./page-loader.js";

window.Api = api;
window.Alerts = useAlerts();

loadPageScripts();

document.addEventListener("DOMContentLoaded", async () => {
    const { initFlowbite } = await ensureFlowbite();
    initFlowbite?.();
});
