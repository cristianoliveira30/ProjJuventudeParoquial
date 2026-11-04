# Deploy rápido na Hostinger

Os scripts compilam o JavaScript, criam `artifacts/hostinger-deploy.zip`, enviam o arquivo para `u401198718@149.62.37.88:65002`, descompactam em `$HOME/domains/sistemasapex.com.br/public_html/paroquiasaodomingos` e executam as migrations e o cache do Laravel.

Ubuntu ou WSL:

```bash
bash scripts/deploy-hostinger.sh
```

Windows PowerShell:

```powershell
powershell -ExecutionPolicy Bypass -File .\scripts\Deploy-Hostinger.ps1
```

A senha é solicitada pelo SSH no terminal e não é salva. No PowerShell ela pode ser pedida duas vezes: no envio e na execução remota. O servidor precisa ter `unzip`, PHP, as dependências PHP em `vendor/`, um `.env` já configurado e permissão de escrita em `storage` e `bootstrap/cache`.
