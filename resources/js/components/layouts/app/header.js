const $ = (selector) => document.querySelector(selector);
const $$ = (selector) => document.querySelectorAll(selector);

document.addEventListener("DOMContentLoaded", () => {
    initNotifications();
    initPasswordToggles();
    initProfilePreview();
    initSettingsModal();
    initSettingsSubmit();
});

function initNotifications() {
    const list = $("#notifications-list");
    const avatarBadge = $("#notif-avatar-badge");
    const menuBadge = $("#notif-menu-badge");

    const updateBadges = () => {
        if (!list) return;

        const unreadCount = list.querySelectorAll('.notif-item[data-unread-default="1"]').length;

        [avatarBadge, menuBadge].forEach((badge, index) => {
            if (!badge) return;

            badge.textContent = unreadCount > 0
                ? index === 0 && unreadCount > 9 ? "9+" : unreadCount
                : "";

            badge.classList.toggle("hidden", unreadCount === 0);
        });
    };

    list?.addEventListener("click", async (e) => {
        const button = e.target.closest(".notif-item");
        if (!button || !button.dataset.notifId || button.dataset.unreadDefault !== "1") return;

        const dot = button.querySelector("[data-notif-dot]");
        const url = button.dataset.readUrl ||
            `/notifications/${encodeURIComponent(button.dataset.notifId)}/mark-read`;

        button.dataset.unreadDefault = "0";
        button.classList.add("opacity-60");
        dot?.classList.add("hidden");
        updateBadges();

        try {
            await window.Api.put(url);
        } catch (err) {
            button.dataset.unreadDefault = "1";
            button.classList.remove("opacity-60");
            dot?.classList.remove("hidden");
            updateBadges();

            window.Alerts?.showError?.({
                title: "Erro ao marcar como lida",
                msg: err?.response?.data?.error ?? err?.response?.data?.message ?? err?.message ?? err,
            });
        }
    });

    updateBadges();
}

function initPasswordToggles() {
    [
        ["current_password", "toggle-current-password", "icon-current-password-hide", "icon-current-password-show"],
        ["password", "toggle-password", "icon-password-hide", "icon-password-show"],
        ["password_confirmation", "toggle-password-confirmation", "icon-password-confirmation-hide", "icon-password-confirmation-show"],
    ].forEach(([inputId, buttonId, hideIconId, showIconId]) => {
        setupToggle({ inputId, buttonId, hideIconId, showIconId });
    });
}

function setupToggle({ inputId, buttonId, hideIconId, showIconId }) {
    const input = document.getElementById(inputId);
    const button = document.getElementById(buttonId);
    const hideIcon = document.getElementById(hideIconId);
    const showIcon = document.getElementById(showIconId);

    if (!input || !button || !hideIcon || !showIcon) return;

    button.addEventListener("click", (e) => {
        e.preventDefault();

        const showing = input.type === "text";

        input.type = showing ? "password" : "text";
        hideIcon.classList.toggle("hidden", !showing);
        showIcon.classList.toggle("hidden", showing);
    });
}

function initProfilePreview() {
    const photoInput = $("#photo");
    const previewImg = $("#preview-avatar");

    if (!photoInput || !previewImg) return;

    photoInput.addEventListener("change", (event) => {
        const file = event.target.files?.[0];
        if (!file) return;

        const reader = new FileReader();

        reader.onload = (e) => {
            previewImg.src = e.target.result;
        };

        reader.readAsDataURL(file);
    });
}

function setButtonLoading(button, loading = true) {
    if (!button) return;

    const text = button.querySelector("[data-loading-text]");
    const defaultIcon = button.querySelector("[data-loading-default-icon]");
    const loadingIcon = button.querySelector("[data-loading-icon]");

    button.disabled = loading;
    button.setAttribute("aria-busy", loading ? "true" : "false");

    button.classList.toggle("opacity-80", loading);
    button.classList.toggle("cursor-not-allowed", loading);
    button.classList.toggle("hover:bg-sky-500", !loading);
    button.classList.toggle("active:scale-95", !loading);

    text?.classList.toggle("hidden", loading);
    defaultIcon?.classList.toggle("hidden", loading);
    loadingIcon?.classList.toggle("hidden", !loading);
    loadingIcon?.classList.toggle("inline-block", loading);
}

function initSettingsModal() {
    const THEME_KEY = "theme";

    const settingsModal = $("#settings-modal");
    const tabs = $$(".settings-tab-link");
    const contents = $$(".settings-content");
    const themeBtns = $$(".set-theme-btn");
    const applyThemeBtn = $("#apply-theme-btn");
    const saveAccountBtn = $("#save-account-btn");
    const cancelSettingsBtn = $("#cancel-settings-btn");
    const themePendingAlert = $("#theme-pending-alert");

    if (!settingsModal) return;

    const activeClasses = [
        "active", "bg-white", "dark:bg-gray-900", "black:bg-zinc-800",
        "shadow-sm", "text-sky-600", "dark:text-sky-400",
        "ring-1", "ring-black/5", "dark:ring-white/5",
    ];

    const inactiveClasses = [
        "text-slate-600", "dark:text-slate-400", "black:text-zinc-400",
        "hover:bg-slate-100", "dark:hover:bg-slate-800",
        "black:hover:bg-zinc-900", "black:hover:text-zinc-200",
    ];

    const themeStyles = {
        light: ["border-sky-500", "bg-sky-500"],
        dark: ["border-purple-500", "bg-purple-500"],
        black: ["border-gray-500", "bg-gray-500"],
    };

    const allBorders = ["border-sky-500", "border-purple-500", "border-gray-500"];
    const allBackgrounds = ["bg-sky-500", "bg-purple-500", "bg-gray-500"];

    let savedTheme = localStorage.getItem(THEME_KEY) || "light";
    let selectedTheme = savedTheme;
    let hasPendingTheme = false;
    let currentTab = $(".settings-tab-link.active")?.dataset.tabTarget || "geral";

    const updateThemeUI = (activeTheme) => {
        themeBtns.forEach((btn) => {
            const ring = btn.querySelector(".theme-ring");
            const isActive = btn.dataset.theme === activeTheme;

            btn.classList.remove("ring-2", ...allBorders);
            ring?.classList.remove(...allBorders, ...allBackgrounds);

            if (!isActive) return;

            const [borderClass, bgClass] = themeStyles[btn.dataset.theme];

            btn.classList.add("ring-2", borderClass);
            ring?.classList.add(borderClass, bgClass);
        });
    };

    const updateFooter = () => {
        applyThemeBtn?.classList.toggle("hidden", !(currentTab === "personalizacao" && hasPendingTheme));
        saveAccountBtn?.classList.toggle("hidden", currentTab !== "conta");
    };

    const showTab = (targetId) => {
        currentTab = targetId;

        contents.forEach((content) => {
            content.classList.add("hidden");
            content.classList.remove("block");
        });

        tabs.forEach((tab) => {
            tab.classList.remove(...activeClasses);
            tab.classList.add(...inactiveClasses);
        });

        const targetContent = document.getElementById(`content-${targetId}`);
        const activeTab = document.querySelector(`[data-tab-target="${targetId}"]`);

        targetContent?.classList.remove("hidden");
        targetContent?.classList.add("block");

        activeTab?.classList.remove(...inactiveClasses);
        activeTab?.classList.add(...activeClasses);

        updateFooter();
    };

    const applyThemeToHtml = (theme) => {
        document.documentElement.classList.remove("dark", "black");

        if (theme === "dark") document.documentElement.classList.add("dark");
        if (theme === "black") document.documentElement.classList.add("black");
    };

    const cleanupModal = () => {
        document.body.classList.remove("overflow-hidden");
        document.documentElement.classList.remove("overflow-hidden");

        document.body.style.overflow = "";
        document.body.style.paddingRight = "";

        $$("[modal-backdrop], .modal-backdrop").forEach((el) => el.remove());
    };

    const cancelChanges = () => {
        selectedTheme = savedTheme;
        hasPendingTheme = false;

        updateThemeUI(savedTheme);
        themePendingAlert?.classList.add("hidden");
        updateFooter();

        const modalInstance = window.FlowbiteInstances?.getInstance("Modal", "settings-modal");

        if (modalInstance) {
            modalInstance.hide();
        } else {
            settingsModal.classList.add("hidden");
            settingsModal.classList.remove("flex");
            settingsModal.setAttribute("aria-hidden", "true");
        }

        setTimeout(cleanupModal, 100);
    };

    tabs.forEach((tab) => {
        tab.addEventListener("click", () => showTab(tab.dataset.tabTarget));
    });

    themeBtns.forEach((btn) => {
        btn.addEventListener("click", () => {
            selectedTheme = btn.dataset.theme;
            hasPendingTheme = selectedTheme !== savedTheme;

            updateThemeUI(selectedTheme);
            themePendingAlert?.classList.toggle("hidden", !hasPendingTheme);
            updateFooter();
        });
    });

    applyThemeBtn?.addEventListener("click", () => {
        if (!hasPendingTheme) return;

        setButtonLoading(applyThemeBtn, true);

        localStorage.setItem(THEME_KEY, selectedTheme);
        applyThemeToHtml(selectedTheme);

        window.Alerts?.showSuccess?.("Tema aplicado com sucesso!");

        setTimeout(() => {
            window.location.reload();
        }, 1200);
    });

    cancelSettingsBtn?.addEventListener("click", cancelChanges);

    updateThemeUI(savedTheme);
    showTab(currentTab);
}

function initSettingsSubmit() {
    const form = $("#form-update-user");
    if (!form) return;

    form.addEventListener("submit", async (e) => {
        e.preventDefault();

        const submitBtn = document.querySelector('button[form="form-update-user"]');

        setButtonLoading(submitBtn, true);

        try {
            const response = await fetch(form.action, {
                method: "POST",
                body: new FormData(form),
                headers: {
                    "X-Requested-With": "XMLHttpRequest",
                    Accept: "application/json",
                },
            });

            const data = await response.json();

            if (!response.ok) throw data;

            window.Alerts?.showSuccess?.(data.message || "Dados atualizados com sucesso!");

            setTimeout(() => window.location.reload(), 1500);
        } catch (error) {
            const msgErro = error?.errors
                ? Object.values(error.errors).flat().join("\n")
                : error?.message || "Ocorreu um erro ao salvar.";

            window.Alerts?.showError?.({
                title: "Verifique os dados",
                msg: msgErro,
            });

            setButtonLoading(submitBtn, false);
        }
    });
}
