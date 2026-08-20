self.addEventListener('push', (event) => {
  const data = event.data ? event.data.json() : {};
  const title = data.title || 'Notificação';
  const body = data.body || '';

  event.waitUntil(
    self.registration.showNotification(title, {
      body,
      data,
      tag: data.notification_id ? `notif-${data.notification_id}` : undefined,
      renotify: true,
    })
  );
});

self.addEventListener('notificationclick', (event) => {
  event.notification.close();
  const url = event.notification.data?.url || '/notifications/dashboard';
  event.waitUntil(clients.openWindow(url));
});
