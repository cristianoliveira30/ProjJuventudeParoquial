#!/usr/bin/env bash
set -euo pipefail

REMOTE="u401198718@149.62.37.88"
PORT="65002"
REMOTE_DIR="public_html/paroquiasaodomingos"
ARCHIVE_DIR="artifacts"
ARCHIVE="$ARCHIVE_DIR/hostinger-deploy.zip"
STAGE_DIR="$(mktemp -d)"

cd "$(dirname "${BASH_SOURCE[0]}")/.."

step() {
    local current="$1" total="$2" message="$3"
    local filled=$((current * 20 / total)) empty=$((20 - filled))
    printf '\n[%s%s] %s\n' "$(printf '%*s' "$filled" '' | tr ' ' '#')" "$(printf '%*s' "$empty" '' | tr ' ' '-')" "$message"
}

cleanup() {
    rm -rf "$STAGE_DIR"
}
trap cleanup EXIT

command -v npm >/dev/null || { echo "npm não encontrado."; exit 1; }
command -v zip >/dev/null || { echo "zip não encontrado. Instale com: sudo apt install zip"; exit 1; }
command -v rsync >/dev/null || { echo "rsync não encontrado. Instale com: sudo apt install rsync"; exit 1; }
command -v ssh >/dev/null || { echo "ssh não encontrado."; exit 1; }
command -v scp >/dev/null || { echo "scp não encontrado."; exit 1; }

step 1 5 "Instalando dependências JavaScript"
npm ci

step 2 5 "Compilando assets JavaScript"
npm run build

step 3 5 "Montando pacote de deploy"
mkdir -p "$ARCHIVE_DIR"
rm -f "$ARCHIVE"
rsync -a --exclude='.git' --exclude='node_modules' --exclude='storage' --exclude='.env' --exclude='.env.production' --exclude='artifacts' ./ "$STAGE_DIR/"
(cd "$STAGE_DIR" && zip -qr "$OLDPWD/$ARCHIVE" .)
echo "Pacote criado: $ARCHIVE"

step 4 5 "Enviando para a Hostinger"
echo "A senha SSH será solicitada agora."
scp -P "$PORT" "$ARCHIVE" "$REMOTE:/tmp/paroquiasaodomingos-deploy.zip"
ssh -p "$PORT" "$REMOTE" "set -eu; mkdir -p '$REMOTE_DIR'; unzip -oq /tmp/paroquiasaodomingos-deploy.zip -d '$REMOTE_DIR'; rm -f /tmp/paroquiasaodomingos-deploy.zip; cd '$REMOTE_DIR'; php artisan migrate --force; php artisan optimize"

step 5 5 "Deploy concluído"
