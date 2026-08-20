export async function loadPageScripts() {
  await import('./webpush-client.js');
  await import('./components/layouts/app/header.js');
}
