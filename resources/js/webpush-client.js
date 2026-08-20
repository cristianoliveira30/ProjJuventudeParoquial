function urlBase64ToUint8Array(base64String) {
  const padding = "=".repeat((4 - (base64String.length % 4)) % 4);
  const base64 = (base64String + padding).replace(/-/g, "+").replace(/_/g, "/");
  const rawData = atob(base64);
  const outputArray = new Uint8Array(rawData.length);
  for (let i = 0; i < rawData.length; ++i) outputArray[i] = rawData.charCodeAt(i);
  return outputArray;
}

async function subscribeWebPush() {
  if (!("serviceWorker" in navigator) || !("PushManager" in window)) return;

  const vapidPublicKey =
    document.head.querySelector('meta[name="vapid-public-key"]')?.content;

  if (!vapidPublicKey) {
    console.warn("VAPID public key não encontrada no meta tag.");
    return;
  }

  // 1) Registra o SW (coloque o sw.js em public/sw.js)
  const reg = await navigator.serviceWorker.register("/sw.js", { scope: "/" });

  // 2) Permissão
  const perm = await Notification.requestPermission();
  if (perm !== "granted") return;

  // 3) Já tem subscription?
  let sub = await reg.pushManager.getSubscription();
  if (!sub) {
    sub = await reg.pushManager.subscribe({
      userVisibleOnly: true,
      applicationServerKey: urlBase64ToUint8Array(vapidPublicKey),
    });
  }

  // 4) Descobre o content encoding suportado (evita dor de cabeça)
  const contentEncoding =
    (window.PushManager?.supportedContentEncodings?.[0]) || "aes128gcm";

  // 5) Envia pro backend
  const csrf = document.head.querySelector('meta[name="csrf-token"]')?.content;

  await fetch("/notifications/webpush/subscribe", {
    method: "POST",
    headers: {
      "Content-Type": "application/json",
      "X-CSRF-TOKEN": csrf,
      "Accept": "application/json",
    },
    body: JSON.stringify({
      ...sub.toJSON(),
      contentEncoding,
    }),
    credentials: "same-origin",
  });
}

// chame isso quando fizer sentido (ideal: clique do usuário)
subscribeWebPush().catch(console.error);
