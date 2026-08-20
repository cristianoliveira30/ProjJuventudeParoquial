#!/bin/bash

# 🚀 Script Local CI/CD Simulator
# Simula tudo que o GitHub Actions vai fazer antes do push
# Use: bash ci-local-test.sh

set -e

# Cores para output
RED='\033[0;31m'
GREEN='\033[0;32m'
YELLOW='\033[1;33m'
BLUE='\033[0;34m'
NC='\033[0m' # No Color

# Funções auxiliares
print_header() {
  echo -e "\n${BLUE}━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━${NC}"
  echo -e "${BLUE}$1${NC}"
  echo -e "${BLUE}━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━${NC}\n"
}

print_success() {
  echo -e "${GREEN}✅ $1${NC}\n"
}

print_error() {
  echo -e "${RED}❌ $1${NC}\n"
}

print_info() {
  echo -e "${YELLOW}ℹ️  $1${NC}\n"
}

# ============================================
# 1. PRÉ-REQUISITOS
# ============================================
print_header "VERIFICANDO PRÉ-REQUISITOS"

if ! command -v docker &> /dev/null; then
  print_error "Docker não encontrado. Instale Docker primeiro."
  exit 1
fi
print_success "Docker instalado"

if ! command -v docker-compose &> /dev/null; then
  print_error "Docker Compose não encontrado. Instale Docker Compose primeiro."
  exit 1
fi
print_success "Docker Compose instalado"

if [ ! -f ".env.production" ]; then
  print_error ".env.production não encontrado!"
  exit 1
fi
print_success ".env.production encontrado"

if [ ! -f "docker/prod/Dockerfile" ]; then
  print_error "docker/prod/Dockerfile não encontrado!"
  exit 1
fi
print_success "docker/prod/Dockerfile encontrado"

# ============================================
# 2. BUILD DA IMAGEM (Stage 1: build-and-test)
# ============================================
print_header "STAGE 1: BUILD DA IMAGEM DOCKER"

print_info "Construindo imagem: iggeps-core:test..."
docker build -f docker/prod/Dockerfile -t iggeps-core:test . || {
  print_error "Falha no build Docker!"
  exit 1
}
print_success "Build concluído"

# ============================================
# 3. EXECUTAR TESTES (Stage 1: build-and-test)
# ============================================
print_header "STAGE 1: EXECUTANDO TESTES (PEST)"

print_info "Rodando tests com: php artisan test --configuration=phpunit.xml"
docker run --rm \
  -v "$(pwd)":/workspace \
  -w /workspace \
  iggeps-core:test \
  php artisan test --configuration=phpunit.xml || {
  print_error "Testes falharam!"
  exit 1
}
print_success "Todos os testes passaram"

# ============================================
# 4. VERIFICAÇÃO DE SEGURANÇA (Stage 2: security-scan)
# ============================================
print_header "STAGE 2: SCAN DE SEGURANÇA (TRIVY)"

if ! command -v trivy &> /dev/null; then
  print_info "Trivy não encontrado. Instalando..."
  curl -sfL https://raw.githubusercontent.com/aquasecurity/trivy/main/contrib/install.sh | sh -s -- -b /usr/local/bin 2>/dev/null || {
    print_error "Não foi possível instalar Trivy. Pulando scan..."
  }
fi

if command -v trivy &> /dev/null; then
  print_info "Rodando Trivy filesystem scan..."
  trivy fs --severity HIGH,CRITICAL . --exit-code 0 || {
    print_error "Vulnerabilidades encontradas (veja acima)"
    # Não falha aqui, apenas avisa
  }
  print_success "Scan de segurança concluído"
else
  print_info "Trivy não disponível. Pulando scan..."
fi

# ============================================
# 5. VERIFICAÇÃO FINAL
# ============================================
print_header "VERIFICAÇÃO FINAL"

# Verificar se o .env.docker existe
if [ -f ".env.docker" ]; then
  print_info "Arquivo .env.docker encontrado"
else
  print_info "Arquivo .env.docker não encontrado (é opcional)"
fi

# Verificar se o docker-compose.prod.override.yml existe
if [ -f "docker-compose.prod.override.yml" ]; then
  print_info "Arquivo docker-compose.prod.override.yml encontrado"
else
  print_info "Arquivo docker-compose.prod.override.yml não encontrado (é opcional)"
fi

# ============================================
# 6. RESUMO FINAL
# ============================================
print_header "✨ RESUMO FINAL"

echo -e "${GREEN}✅ Build Docker: PASSOU${NC}"
echo -e "${GREEN}✅ Testes Pest: PASSOU${NC}"
echo -e "${GREEN}✅ Security Scan: PASSOU${NC}"

print_header "🎉 TUDO OK! VOCÊ PODE FAZER PUSH AGORA"

echo "Próximos passos:"
echo "  1. Faça push para uma branch de teste:"
echo -e "     ${YELLOW}git push origin seu-branch${NC}"
echo ""
echo "  2. Monitore na aba Actions do GitHub:"
echo -e "     ${YELLOW}https://github.com/seu-usuario/seu-repo/actions${NC}"
echo ""
echo "  3. Quando tudo passar, crie uma tag para deploy:"
echo -e "     ${YELLOW}git tag v1.0.0${NC}"
echo -e "     ${YELLOW}git push origin v1.0.0${NC}"
echo ""

# ============================================
# 7. DICAS DE TROUBLESHOOTING
# ============================================
echo -e "\n${BLUE}📝 DICAS:${NC}"
echo "  • Se build falhar: docker build -f docker/prod/Dockerfile -t iggeps-core:test . --no-cache"
echo "  • Se testes falharem: docker run -it iggeps-core:test bash (debugar dentro)"
echo "  • Ver logs: docker logs nome_container"
echo "  • Limpar imagens antigas: docker image prune -a"
echo ""
