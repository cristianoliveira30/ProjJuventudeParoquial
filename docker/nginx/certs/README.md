# Certificados locais

Os certificados de desenvolvimento não ficam no repositório. Gere um par autoassinado antes de subir o nginx:

```bash
openssl req -x509 -nodes -newkey rsa:2048 -days 365 \
  -keyout docker/nginx/certs/selfsigned.key \
  -out docker/nginx/certs/selfsigned.crt \
  -subj "/CN=localhost"
```
