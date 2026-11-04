$ErrorActionPreference = 'Stop'

$Remote = 'u401198718@149.62.37.88'
$Port = '65002'
$RemoteDir = 'public_html/paroquiasaodomingos'
$ProjectRoot = Resolve-Path (Join-Path $PSScriptRoot '..')
$ArchiveDir = Join-Path $ProjectRoot 'artifacts'
$Archive = Join-Path $ArchiveDir 'hostinger-deploy.zip'
$StageDir = Join-Path ([System.IO.Path]::GetTempPath()) ("paroquia-deploy-" + [guid]::NewGuid())

function Show-Step([int]$Current, [string]$Message) {
    Write-Progress -Activity 'Deploy Hostinger' -Status $Message -PercentComplete ($Current * 20)
    Write-Host "[$Current/5] $Message"
}

try {
    Show-Step 1 'Instalando dependências JavaScript'
    Push-Location $ProjectRoot
    npm ci

    Show-Step 2 'Compilando assets JavaScript'
    npm run build

    Show-Step 3 'Montando pacote de deploy'
    New-Item -ItemType Directory -Force -Path $ArchiveDir, $StageDir | Out-Null
    & robocopy $ProjectRoot $StageDir /E /XD .git node_modules storage artifacts /XF .env .env.production | Out-Null
    if ($LASTEXITCODE -gt 7) { throw "Falha ao preparar os arquivos (robocopy: $LASTEXITCODE)." }
    Remove-Item $Archive -Force -ErrorAction SilentlyContinue
    Compress-Archive -Path (Join-Path $StageDir '*') -DestinationPath $Archive -CompressionLevel Optimal
    Write-Host "Pacote criado: $Archive"

    Show-Step 4 'Enviando para a Hostinger'
    Write-Host 'A senha SSH será solicitada para o envio e para os comandos remotos.'
    scp -P $Port $Archive "${Remote}:/tmp/paroquiasaodomingos-deploy.zip"
    if ($LASTEXITCODE -ne 0) { throw "Falha no envio (scp: $LASTEXITCODE)." }
    $remoteCommand = "set -eu; mkdir -p '$RemoteDir'; unzip -oq /tmp/paroquiasaodomingos-deploy.zip -d '$RemoteDir'; rm -f /tmp/paroquiasaodomingos-deploy.zip; cd '$RemoteDir'; php artisan migrate --force; php artisan optimize"
    ssh -p $Port $Remote $remoteCommand
    if ($LASTEXITCODE -ne 0) { throw "Falha no deploy remoto (ssh: $LASTEXITCODE)." }

    Show-Step 5 'Deploy concluído'
} finally {
    Pop-Location -ErrorAction SilentlyContinue
    Remove-Item $StageDir -Recurse -Force -ErrorAction SilentlyContinue
    Write-Progress -Activity 'Deploy Hostinger' -Completed
}
