# Deploy na Hostinger sem npm no servidor

O GitHub Actions compila o Vite e publica uma branch chamada `deploy`. Ela contém o mesmo código da `main` mais `public/build`; a Hostinger não executa npm.

Não há secrets nem Environments no GitHub. O único acesso privado fica na Hostinger: gere uma chave SSH de leitura no hPanel/servidor e adicione **a chave pública** como Deploy key de leitura no repositório GitHub.

## Uma vez na Hostinger

1. Crie ou conecte o repositório Git e selecione a branch `deploy`.
2. Use `public_html/paroquiasaodomingos` como a pasta do projeto.
3. Aponte o domínio para `public_html/paroquiasaodomingos/public`; somente essa subpasta pode ficar exposta na web.
4. Crie `.env` em `public_html/paroquiasaodomingos` a partir de `.env.production.example`, com valores reais para `APP_KEY`, banco de dados e `PARISH_ADMIN_PASSWORD`.
5. Execute uma vez no terminal SSH da Hostinger:

```bash
cd ~/public_html/paroquiasaodomingos
php artisan key:generate --force
php artisan migrate --force
php artisan config:cache
php artisan route:cache
php artisan view:cache
```

O servidor também precisa de `vendor/`. Se o plano tiver Composer, rode `composer install --no-dev --optimize-autoloader` na primeira instalação. Caso não tenha, envie `vendor/` pelo Gerenciador de Arquivos ou SFTP uma única vez, usando a mesma versão de PHP do servidor.

## Em cada deploy

Cada push na `main` gera a branch `deploy`. No hPanel, faça o deploy dessa branch. Se a integração Git do seu plano oferecer auto-deploy, configure-a para observar `deploy`.

Depois de atualizar o código, execute no terminal SSH:

```bash
cd ~/public_html/paroquiasaodomingos
php artisan migrate --force
php artisan optimize
```

Não coloque `.env`, certificados, chaves VAPID nem arquivos de `storage/` no repositório.
