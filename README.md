# Portal da Paróquia São Domingos de Gusmão

<p align="center">
  <a href="https://laravel.com" target="_blank"><img src="https://raw.githubusercontent.com/laravel/art/master/logo-lockup/5%20SVG/2%20CMYK/1%20Full%20Color/laravel-logolockup-cmyk-red.svg" width="320" alt="Laravel Logo"></a>
</p>

**Descrição:** Projeto baseado em Laravel (v12) com Vite, TailwindCSS e componentes Flowbite. Este README descreve como configurar e executar o projeto localmente tanto com **Laravel Sail (Docker)** quanto sem Docker usando `npm run dev` + `php artisan serve`.

---

## 🧰 Stack principal

- **PHP** >= 8.3
- **Laravel** ^12
- **Laravel Sail** (Docker) para ambiente de desenvolvimento isolado
- **Node.js / npm** + **Vite** (dev server e build)
- **Tailwind CSS**, **Flowbite** e **SweetAlert2**
- **Pest / PHPUnit** para testes
- **Composer** para dependências PHP

---

## 📦 Requisitos

- Para usar Laravel Sail (recomendado):
  - Docker e Docker Compose instalados
  - WSL ou ambiente linux
- Para rodar sem Sail:
  - PHP >= 8.3, Composer
  - Node.js (recomendado >= 16) e npm

---

## 🚀 Iniciando o projeto (opção A: com Laravel Sail)

1. Copie o arquivo de ambiente e ajuste variáveis conforme necessário:

```bash
# faça o clone e instancia o docker
mkdir ~/projects
cd ~/projects
git clone link
cp .env.example .env

# instala o sail
docker run --rm \
  -u "$(id -u):$(id -g)" \
  -v "$PWD":/var/www/html -w /var/www/html \
  laravelsail/php83-composer:latest \
  composer install
```

2. Suba os containers e execute instalação (usa o binário Sail):

```bash
# se quiser usar um alias
echo "alias sail='sh \$([ -f sail ] && echo sail || echo vendor/bin/sail)'" >> ~/.bashrc
source ~/.bashrc

# Inicia os containers em background
./vendor/bin/sail up -d

# (Opcional) instale dependências PHP dentro do container
./vendor/bin/sail composer install

# Gere a chave de app
./vendor/bin/sail artisan key:generate

# Rode migrations e seeders (se aplicável)
./vendor/bin/sail artisan migrate --seed

# Instale dependências JS e rode o dev server do Vite
./vendor/bin/sail npm install
./vendor/bin/sail npm run dev

# Para encerrar
sail down # use -v se quiser dropar os container
```

3. Acesse a aplicação em `http://localhost` (ou na porta configurada pelo Sail).

### Primeiro acesso administrativo

O seeder cria apenas a conta administrativa inicial. Antes de executar `migrate --seed`, ajuste no `.env`:

```dotenv
PARISH_ADMIN_NAME="Administrador Paroquial"
PARISH_ADMIN_USERNAME=admin
PARISH_ADMIN_EMAIL=admin@paroquia.local
PARISH_ADMIN_PASSWORD="uma-senha-temporaria-segura"
```

Entre em `/login` com essas credenciais. O sistema exigirá uma nova senha no primeiro acesso. Depois disso, use **Usuários** para criar contas e **Perfis de acesso** para configurar as autorizações.

Dica: Você pode adicionar um alias para facilitar os comandos no terminal (opcional):

```bash
alias sail='./vendor/bin/sail'
# Agora pode usar: sail up -d, sail artisan migrate, sail npm run dev, etc.
```

---

## ⚙️ Iniciando sem Docker (opção B: `npm run dev` + `php artisan serve`)

> Use esta opção se você preferir rodar os serviços localmente (PHP/Composer instalados no host).

1. Copie o .env e configure:

```bash
cp .env.example .env
```

2. Instale dependências PHP e JS:

```bash
composer install
npm install
```

3. Gere a chave de app e rode migrations:

```bash
php artisan key:generate
php artisan migrate --seed
```

4. Rode o Vite e o servidor PHP (em terminais separados):

```bash
# Terminal 1
npm run dev

# Terminal 2
php artisan serve --host=127.0.0.1 --port=8000
```

Abra `http://127.0.0.1:8000` no navegador. Se o Vite usar hot module replacement, acesse também a porta do Vite (tipicamente 5173) quando solicitado.

Observação: O `composer` do projeto já inclui um script `dev` que executa `php artisan serve`, `php artisan queue:listen` e `npm run dev` em paralelo (via `concurrently`) caso prefira uma abordagem centralizada:

```bash
composer dev
```

---

## 🧪 Testes

- Com Sail:

```bash
./vendor/bin/sail test
```

- Sem Sail:

```bash
php artisan test
# ou
composer test
```

---

## 🛠️ Comandos úteis

- Rodar migrations: `php artisan migrate`
- Rodar seeders: `php artisan db:seed`
- Criar link de storage: `php artisan storage:link`
- Limpar caches: `php artisan config:clear && php artisan route:clear && php artisan view:clear`
- Rodar fila: `php artisan queue:work` (ou `sail artisan queue:work` com Sail)
- Build para produção (assets): `npm run build`

---

## 🔧 Deploy / Produção (pontos rápidos)

- Execute `npm run build` para gerar os assets com Vite.
- Configure variáveis de ambiente (`APP_ENV=production`, `APP_DEBUG=false`, `APP_KEY` definida).
- Execute `php artisan migrate --force` em produção.
- Configure servidor web (Nginx/Apache) para apontar para a pasta `public/`.

### 🐳 Deploy usando Docker (produção)

1. Copie o exemplo de ambiente e ajuste todas as variáveis. `APP_KEY`, `DB_*` e `PARISH_ADMIN_PASSWORD` são obrigatórias:

```bash
cp .env.production.example .env.production
```

Instale certificados TLS reais em `docker/prod/certs/server.crt` e `docker/prod/certs/server.key`. Esses arquivos não são versionados. O Nginx não inicia sem eles.

2. Construa e rode os containers:

```bash
docker compose --env-file .env.production -f docker-compose.prod.yml build

docker compose --env-file .env.production -f docker-compose.prod.yml up -d
```

3. Execute migrações (se necessário):

```bash
docker compose --env-file .env.production -f docker-compose.prod.yml exec app php artisan migrate --force
```

A aplicação ficará disponível em `http://localhost`.

---

## ⚠️ Troubleshooting (Problemas comuns)

- Problema: **Erros de porta/vite** — verifique se a porta do Vite (por padrão 5173) não está em uso.
- Problema: **Permissões em storage** — ajuste com `chown -R www-data:www-data storage bootstrap/cache` (ou conforme seu ambiente).
- Problema: **Containers inconsistentes** — reinicie com `./vendor/bin/sail down -v && ./vendor/bin/sail up -d`.

---

## 📄 Uso

Projeto de uso interno da Paróquia São Domingos de Gusmão. Antes de uma distribuição pública, defina e adicione ao repositório a licença apropriada.

---
