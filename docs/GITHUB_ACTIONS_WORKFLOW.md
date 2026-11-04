## Fluxo Docker legado

> Para o deploy atual na Hostinger, use [HOSTINGER_DEPLOY.md](HOSTINGER_DEPLOY.md). O workflow ativo compila os assets e publica a branch `deploy`, sem secrets ou Environments no GitHub.

Este documento explica o pipeline de CI/CD usado neste repositório e como testá-lo localmente.

**Arquivos principais**
- Workflow: [.github/workflows/ci-cd.yml](.github/workflows/ci-cd.yml)
- Dockerfile de produção: [docker/prod/Dockerfile](docker/prod/Dockerfile)
- Entrypoint runtime: [docker/prod/entrypoint.sh](docker/prod/entrypoint.sh)
- Script de teste local (simulador CI): [ci-local-test.sh](testar-push.sh)

## Objetivo
- Construir assets Vite em CI com valores públicos `VITE_*`.
- Construir imagem Docker otimizada (multi-stage).
- Rodar testes dentro da imagem de teste (evita sobrescrever `vendor/`).
- Rodar scanner de vulnerabilidades (Trivy).
- Fazer deploy via SSH apenas em tags (`v*`) usando GitHub Environment `production`.

## Como usar
```bash
git add .
git commit -m "deploy: alguma coisa"
git tag -a v1.0.0 -m "Lançamento versão '1.0.0"
git push origin main
```
- ver detalhes da tag criada com git show v1.0.0

## Fluxo resumido do workflow
- Gatilhos: `push` (branches `main`, `develop`, `feat/actions`) e `tags` `v*`.
- Passos principais (job `build-and-test`):
  1. Gera um arquivo `.env.production` temporário contendo apenas variáveis públicas `VITE_*` (a partir de secrets do Environment).
  2. Constrói uma imagem de teste (usa o stage `composer` com `INSTALL_DEV=true`) para que `vendor/bin/pest`/`phpunit` exista.
  3. Executa os testes dentro da imagem (sem montar o workspace). O container é inicializado com `APP_ENV=testing`, `BROADCAST_CONNECTION=log` e outras variáveis seguras para evitar conexões externas. Esta etapa é o gate de deploy: se Pest/PHPUnit falhar, o job falha e nenhuma imagem é publicada.
  4. Se não for `pull_request` e os testes tiverem passado, constrói e publica a imagem final e roda o job de `security-scan` (Trivy).
  5. Em tags `v*`, o job `deploy` faz login no registry e conecta via SSH ao servidor para `docker-compose pull && up -d`.

## Segredos / Environment `production` (recomendado)
Adicione estes segredos no GitHub Environment `production` (não no repo):
- `REVERB_APP_KEY`, `REVERB_APP_SECRET`, `REVERB_APP_ID`, `REVERB_HOST`, `REVERB_PORT`, `REVERB_SCHEME`
- `DEPLOY_HOST`, `DEPLOY_USER`, `DEPLOY_PATH`, `DEPLOY_KEY` (chave privada)
- `REGISTRY_TOKEN`, `REGISTRY_USERNAME`
- Outras variáveis sensíveis de runtime: `APP_KEY`, `MAIL_*`, `AWS_*`, `VAPID_PRIVATE_KEY`

Proteja o Environment com reviewers antes de permitir deploy em production.

## Testando localmente (simular CI)
Existe um script de conveniência `testar-push.sh` que reproduz os passos principais localmente — prefira usá-lo. Alternativamente, executar manualmente:

```bash
# Build da imagem de teste (igual ao CI):
docker build --target composer \
  --build-arg INSTALL_DEV=true \
  -t paroquia-core:test -f docker/prod/Dockerfile .

# Rodar testes dentro da imagem (exemplo):
docker run --rm -e APP_ENV=testing -e BROADCAST_CONNECTION=log \
  paroquia-core:test sh -lc "cd /app && vendor/bin/pest --configuration=phpunit.xml || vendor/bin/phpunit --configuration=phpunit.xml"
```

Observações:
- Não exponha secrets de produção quando testar localmente.
- O `entrypoint.sh` foi ajustado para não rodar migrations automaticamente em `APP_ENV=testing`.

## Notas de implementação importantes
- O Dockerfile copia `resources`, `tests`, `phpunit.xml` e instala dependências dev no estágio `composer` quando construído com `INSTALL_DEV=true` (assim o image de teste tem tudo que precisa).
- O workflow constrói a imagem de teste com `target: composer` e depois usa `docker run` para executar os testes sem montar o workspace — isso preserva o `vendor/` gerado pela build e evita erros de dependências faltantes.
- O pipeline gera um `.env.production` mínimo durante CI contendo apenas `VITE_*` (retirado de secrets). Não commite ` .env.production` no repositório — as variáveis sensíveis devem ficar somente no Environment ou no servidor.

## Como depurar falhas no CI
- Verifique os logs do passo `Run unit tests with Pest` na Actions; se faltar `vendor/bin`, confirme que o build de teste foi feito com `INSTALL_DEV=true`.
- Para confirmar conteúdo do container, adicione temporariamente comandos de debug no passo `docker run`, por exemplo `ls -la /app /var/www/html /app/vendor/bin`.
- Se houver erros relacionados a broadcasting (Pusher/Reverb), verifique se o job de testes está configurado com `BROADCAST_CONNECTION=log` e as `REVERB_*` definidas (mesmo vazias) no `docker run` para evitar valores `null`.

## Perguntas frequentes rápidas
- Por que não montamos o workspace para rodar os testes? Porque montar sobrescreve os artefatos construídos dentro da imagem (por exemplo `/vendor`), quebrando a execução dos testes.
- Onde adicionar novas secrets? Use a UI do GitHub ou o `gh` CLI: `gh secret set --env production NAME`.

----
Arquivo de referência e pontos de edição rápida:
- Workflow: [.github/workflows/ci-cd.yml](.github/workflows/ci-cd.yml)
- Dockerfile: [docker/prod/Dockerfile](docker/prod/Dockerfile)
- Entrypoint: [docker/prod/entrypoint.sh](docker/prod/entrypoint.sh)

Se quiser, eu gero também uma versão curta deste README como `README_CI.md` na raiz.
