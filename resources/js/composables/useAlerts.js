// resources/js/composables/useAlerts.js
import Swal from "sweetalert2";
import "sweetalert2/dist/sweetalert2.min.css";

function isDark() {
  return document.documentElement.classList.contains("dark");
}
function isBlack() {
  return document.documentElement.classList.contains("black");
}

function normalizeError(err) {
  if (!err) return "Erro inesperado.";
  if (typeof err === "string") return err;
  if (err.message) return err.message;
  try { return JSON.stringify(err); } catch { return String(err); }
}

function themeOptions() {
  // classes base (Tailwind)
  const base = {
    container: "!z-[99999]",
    popup: "shadow-lg border !z-[99999]",
    title: "text-base",
    htmlContainer: "text-sm",
  };

  if (isDark()) {
    return {
      customClass: {
        ...base,
        popup: `${base.popup} border-slate-700`,
      },
    };
  }
  if (isBlack()) {
    return {
      background: '#18181b', // zinc-900 (fallback)
      color: '#f4f4f5',      // zinc-100 (fallback)
      customClass: {
        ...base,
        popup: `${base.popup} bg-zinc-900 border-zinc-800`,
        title: `${base.title} text-zinc-100`,
        htmlContainer: `${base.htmlContainer} text-zinc-300`,
      },
    };
  }

  return {
    customClass: {
      ...base,
      popup: `${base.popup} border-slate-200`,
    },
  };
}

export function useAlerts() {
  function fireToast({ icon, title, text, onClick } = {}) {
    return Swal.fire({
      toast: true,
      position: "top-end",
      showConfirmButton: false,
      timer: 3500,
      timerProgressBar: true,
      icon,
      title,
      text,
      didOpen: (toast) => {
        toast.addEventListener("mouseenter", Swal.stopTimer);
        toast.addEventListener("mouseleave", Swal.resumeTimer);

        if (typeof onClick === "function") {
          toast.style.cursor = "pointer";
          toast.addEventListener("click", () => {
            Swal.close(); // fecha o toast
            onClick();
          });
        }
      },
      ...themeOptions(),
    });
  }

  // ✅ agora aceita 3º parâmetro opcional: opts
  function showSuccess(message, title = "Sucesso", opts = {}) {
    return fireToast({ icon: "success", title, text: message, onClick: opts.onClick });
  }

  function showInfo(message, title = "Info", opts = {}) {
    return fireToast({ icon: "info", title, text: message, onClick: opts.onClick });
  }

  function showError({ title = "Erro", msg = "Erro inesperado.", onClick } = {}) {
    return fireToast({ icon: "error", title, text: normalizeError(msg), onClick });
  }

  function loading(title = "Carregando...", text = "Aguarde um instante.") {
    return Swal.fire({
      title,
      text,
      allowOutsideClick: false,
      allowEscapeKey: false,
      showConfirmButton: false,
      didOpen: () => Swal.showLoading(),
      ...themeOptions(),
    });
  }

  function close() {
    Swal.close();
  }

  async function confirm({
    title = "Confirmar?",
    text = "Deseja continuar?",
    confirmButtonText = "Sim",
    cancelButtonText = "Cancelar",
    icon = "question",
  } = {}) {
    const res = await Swal.fire({
      title,
      text,
      icon,
      showCancelButton: true,
      confirmButtonText,
      cancelButtonText,
      reverseButtons: true,
      focusCancel: true,
      ...themeOptions(),
    });
    return res.isConfirmed;
  }

  function bindThemeSync() {
    const html = document.documentElement;
    const obs = new MutationObserver(() => {
      if (Swal.isVisible()) Swal.close();
    });
    obs.observe(html, { attributes: true, attributeFilter: ["class"] });
    return () => obs.disconnect();
  }

  return { showSuccess, showError, showInfo, loading, close, confirm, bindThemeSync };
}

window.Alerts = useAlerts();