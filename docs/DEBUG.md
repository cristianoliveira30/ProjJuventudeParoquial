# Xdebug (Laravel Sail) no WSL — Passo a passo definitivo (porta 9003)

Este guia configura o **Xdebug** dentro do **Laravel Sail (Docker)** para debugar no **VSCode** rodando no **WSL**, evitando o problema comum do `host.docker.internal` resolver para **IPv6** e causar `Network is unreachable`.

> ✅ Resultado final: o container do Sail consegue conectar no VSCode via **IPv4 do WSL** na porta **9003**.

---

## Pré‑requisitos

- Projeto Laravel com **Sail** funcionando (`sail up -d`).
- Extensão **PHP Debug** no VSCode (geralmente `xdebug.php-debug`).
- VSCode configurado para **Listen** na porta **9003** (ver seção abaixo).

---

## 1) Configure o VSCode (Listen na 9003)

No `.vscode/launch.json` (exemplo):

```json
{
  "name": "Listen for Xdebug (Sail)",
  "type": "php",
  "request": "launch",
  "port": 9003,
  "pathMappings": {
    "/var/www/html": "${workspaceFolder}"
  }
}
```

> Nota: quando o listener está ligado, é normal aparecer um processo **node** escutando na porta — é o *debug adapter* do VSCode.

Para verificar no WSL:

```bash
ss -ltnp | grep 9003 || true
```

---

## 2) Descubra o IPv4 do WSL (host)

No **WSL** (fora do container), pegue o IP:

```bash
hostname -I | awk '{print $1}'
```

Exemplo de saída:

```
172.22.53.135
```

Guarde esse valor: `WSL_IP`.

---

## 3) Teste se o container consegue alcançar o VSCode (TCP)

Com o VSCode em **Listen** na 9003, rode o teste **de dentro do container** (TCP puro):

```bash
sail exec -e XDEBUG_MODE=off laravel.test sh -lc 'php -r '"'"'$fp=@fsockopen("WSL_IP",9003,$e,$s,2); echo $fp? "tcp_ok
" : "tcp_fail: $s
";'"'"''
```

Substitua `WSL_IP` pelo IP do passo 2.

### Interpretação
- ✅ `tcp_ok` → rede ok (container alcança o host WSL na porta 9003)
- ❌ `tcp_fail: Connection refused` → VSCode não está “Listen”, porta diferente, ou não está no WSL
- ❌ `Network is unreachable` → host errado (muito comum quando cai em IPv6)

---

## 4) “Fix” definitivo: forçar `host.docker.internal` apontar para o IPv4 do WSL

Edite o `compose.yaml` do Sail e no serviço `laravel.test` adicione/ajuste:

```yaml
services:
  laravel.test:
    extra_hosts:
      - "host.docker.internal:WSL_IP"
```

Substitua `WSL_IP` pelo IP do passo 2.

Recrie os containers:

```bash
sail down
sail up -d
```

Valide que o hostname resolve pro IPv4 correto:

```bash
sail exec laravel.test getent hosts host.docker.internal
```

Saída esperada:

```
WSL_IP host.docker.internal
```

---

## 5) Configure o Xdebug para usar host.docker.internal + porta 9003

Você pode configurar via `.env` do projeto:

```env
SAIL_XDEBUG_MODE=debug,develop
SAIL_XDEBUG_CONFIG="client_host=host.docker.internal client_port=9003"
```

Depois:

```bash
sail down
sail up -d
```

Valide dentro do container:

```bash
sail php --ri xdebug | grep -E "client_host|client_port|mode"
```

Saída esperada (exemplo):

```
xdebug.mode => debug => debug
xdebug.client_host => host.docker.internal => host.docker.internal
xdebug.client_port => 9003 => 9003
```

> ⚠️ Se continuar diferente do esperado, então algum `php.ini`/ini do container está sobrescrevendo. Ajuste nele também (seção 6).

---

## 6) (Se necessário) Forçar no php.ini/ini do container

Se sua imagem do Sail copia um `php.ini` para `conf.d` (ex.: `99-sail.ini`), garanta estas linhas:

```ini
xdebug.mode=debug,develop
xdebug.client_host=host.docker.internal
xdebug.client_port=9003
xdebug.discover_client_host=0
xdebug.start_with_request=yes
```

Depois faça rebuild:

```bash
sail down
sail build --no-cache
sail up -d
```

---

## 7) Teste final de debug (CLI)

Com o VSCode em **Listen**, rode:

```bash
sail exec -e XDEBUG_MODE=debug -e XDEBUG_CONFIG="client_host=host.docker.internal client_port=9003" laravel.test php -r 'echo "xdebug test
";'
```

Se ainda aparecer “Could not connect…”, volte ao **teste TCP** do passo 3 e confirme que está `tcp_ok`.

---

# Troubleshooting rápido

## A) “Network is unreachable” para host.docker.internal
Geralmente o `host.docker.internal` resolveu para **IPv6** dentro do container.  
Solução: usar `extra_hosts` no compose apontando para o **IPv4 do WSL** (passo 4).

## B) “Connection refused”
- VSCode não está em **Listen**
- Porta diferente no `launch.json`
- VSCode está rodando no **Windows** mas você está testando o IP do WSL (ou vice‑versa)
- Firewall/regras bloqueando

Faça:
1. Verifique listener:
   ```bash
   ss -ltnp | grep 9003 || true
   ```
2. Refaça o teste do passo 3.

## C) A porta aparece como “node”
Isso é normal: o VSCode (extensão PHP Debug) usa um processo **node** como *debug adapter*.

## D) IP do WSL muda depois de reboot
Isso é comum. Se mudar:
1. Rode:
   ```bash
   hostname -I | awk '{print $1}'
   ```
2. Atualize o `extra_hosts` no `compose.yaml` com o novo IP
3. Rode:
   ```bash
   sail down
   sail up -d
   ```

---

## Checklist final (para confirmar que está 100%)

1. **WSL listener**:
   ```bash
   ss -ltnp | grep 9003
   ```
2. **Hostname dentro do container resolve para IPv4 do WSL**:
   ```bash
   sail exec laravel.test getent hosts host.docker.internal
   ```
3. **Teste TCP do container para o host**:
   ```bash
   sail exec -e XDEBUG_MODE=off laravel.test sh -lc 'php -r '"'"'$fp=@fsockopen("host.docker.internal",9003,$e,$s,2); echo $fp? "tcp_ok
" : "tcp_fail: $s
";'"'"''
   ```
4. **Xdebug configurado para 9003**:
   ```bash
   sail php --ri xdebug | grep -E "client_host|client_port|mode"
   ```

---

**Fim.**
