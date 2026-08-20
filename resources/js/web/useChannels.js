import Echo from "laravel-echo";
import Pusher from "pusher-js";

window.Pusher = Pusher;

const reverbKey = import.meta.env.VITE_REVERB_APP_KEY;
const reverbHost = import.meta.env.VITE_REVERB_HOST;

if (reverbKey && reverbHost) {
  window.Echo = new Echo({
    broadcaster: "reverb",
    key: reverbKey,
    wsHost: reverbHost,
    wsPort: import.meta.env.VITE_REVERB_PORT ?? 80,
    wssPort: import.meta.env.VITE_REVERB_PORT ?? 443,
    forceTLS: (import.meta.env.VITE_REVERB_SCHEME ?? "https") === "https",
    enabledTransports: ["ws", "wss"],
  });
}

const userId = document.head.querySelector('meta[name="app-user-id"]')?.content;

function openNotificationsModal() {
  // Preferível: dispara o clique no botão que o Flowbite já controla
  const toggleBtn = document.querySelector('[data-modal-target="notifications-modal"]');
  if (toggleBtn) {
    toggleBtn.click();
    return;
  }

  // fallback manual (se não achar o botão)
  const modal = document.getElementById("notifications-modal");
  if (!modal) return;

  modal.classList.remove("hidden");
  modal.classList.add("flex");
  modal.setAttribute("aria-hidden", "false");
}

function ensureEmptyStateRemoved() {
  const list = document.getElementById("notifications-list");
  if (!list) return;

  // remove o "Nenhuma notificação encontrada."
  const empty = list.querySelector("[data-empty-notifications]");
  if (empty) empty.remove();
}

function updateBadge(delta = 1) {
  const menuBadge = document.getElementById("notif-menu-badge");
  const avatarBadge = document.getElementById("notif-avatar-badge");
  if (!menuBadge && !avatarBadge) return;

  const current = Number(menuBadge?.dataset.count ?? avatarBadge?.dataset.count ?? "0");
  const next = Math.max(0, current + delta);

  [menuBadge, avatarBadge].forEach((badge) => {
    if (!badge) return;
    badge.dataset.count = String(next);
    badge.textContent = badge === avatarBadge && next > 9 ? "9+" : String(next);
    badge.classList.toggle("hidden", next === 0);
  });
}

function prependNotificationToList(e) {
  const list = document.getElementById("notifications-list");
  if (!list) return;

  ensureEmptyStateRemoved();

  const id = e?.notification_id ?? Date.now();
  const title = e?.title ?? "Nova notificação";
  const message = e?.message ?? "";
  const time = "agora"; // depois você pode trocar por timestamp real do back

  // flag ainda não vem do back (você comentou isso). Deixa pronto:
  const flagText = e?.flag_text ?? null;
  const flagColor = e?.flag_color ?? "slate";

  // classes iguais às do seu Blade (mantém identidade visual)
  const li = document.createElement("li");

  li.innerHTML = `
    <button type="button"
      class="notif-item w-full text-left rounded-xl border border-slate-200 bg-white p-3 hover:bg-slate-50 dark:bg-slate-900 dark:border-slate-700 dark:hover:bg-slate-800 black:bg-zinc-900 black:border-zinc-800 black:hover:bg-zinc-800 transition-all group"
      data-notif-id="${String(id)}"
      data-unread-default="1">
      <div class="flex items-start gap-3">
        <span data-notif-dot class="mt-1.5 w-2.5 h-2.5 rounded-full bg-sky-500 shadow-sm shadow-sky-500/50"></span>

        <div class="min-w-0 flex-1">
          <div class="flex items-center gap-2 mb-1">
            <div class="notif-title font-bold text-slate-800 dark:text-slate-100 black:text-zinc-100 truncate text-sm"></div>

            ${
              flagText
                ? `<span class="shrink-0 inline-flex items-center rounded-full px-2 py-0.5 text-[10px] uppercase font-bold tracking-wide"
                    data-flag-color="${flagColor}">${flagText}</span>`
                : ""
            }

            <div class="ml-auto text-xs text-slate-500 dark:text-slate-400 black:text-zinc-500">${time}</div>
          </div>

          <div class="notif-message text-sm text-slate-600 dark:text-slate-300 black:text-zinc-400 leading-snug"></div>
        </div>
      </div>
    </button>
  `;

  // Segurança: evitar XSS — popula via textContent
  li.querySelector(".notif-title").textContent = title;
  li.querySelector(".notif-message").textContent = message;

  // clique na notificação abre o modal (você pode depois ligar isso ao “marcar como lida”)
  li.querySelector("button")?.addEventListener("click", () => {
    openNotificationsModal();
  });

  list.prepend(li);
}

if (userId && window.Echo) {
  const seen = new Set();

  window.Echo.private(`user.${userId}`).listen(".notification.sent", (e) => {
    const id = e?.notification_id ?? null;

    // dedupe simples
    if (id && seen.has(id)) return;
    if (id) {
      seen.add(id);
      setTimeout(() => seen.delete(id), 60_000);
    }

    prependNotificationToList(e);
    updateBadge(1);

    const title = e?.title ?? "Nova notificação";
    const message = e?.message ?? "";

    // toast com clique abrindo o modal grande
    if (window.Alerts?.showInfo) {
      window.Alerts.showInfo(message, title, {
        onClick: () => openNotificationsModal(),
      });
    } else {
      console.log("Nova notificação recebida:", e);
      openNotificationsModal();
    }
  });
}
