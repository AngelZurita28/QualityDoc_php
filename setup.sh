#!/bin/bash

# =================================================================
# Script de Configuracion Inicial - QualityDoc PHP (Linux)
# =================================================================

CYAN='\033[0;36m'
GREEN='\033[0;32m'
YELLOW='\033[1;33m'
RED='\033[0;31m'
NC='\033[0m'

echo -e "${CYAN}========================================${NC}"
echo -e "${CYAN}  Configuracion de QualityDoc PHP      ${NC}"
echo -e "${CYAN}========================================${NC}"

# 1. Verificar Docker
DOCKER_CMD="docker"
if ! docker info > /dev/null 2>&1; then
    if sudo docker info > /dev/null 2>&1; then
        DOCKER_CMD="sudo docker"
    else
        echo -e "${RED}ERROR: Docker no esta ejecutandose.${NC}"
        exit 1
    fi
fi

if $DOCKER_CMD compose version > /dev/null 2>&1; then
    DOCKER_COMPOSE_CMD="$DOCKER_CMD compose"
else
    DOCKER_COMPOSE_CMD="docker-compose"
fi

# 2. Pedir credenciales
echo -e "\n${GREEN}Configuracion de PostgreSQL:${NC}"
read -p "Ingresa el USUARIO (Enter para 'postgres'): " dbUser
if [ -z "$dbUser" ]; then dbUser="postgres"; fi

read -p "Ingresa el nombre de la BASE DE DATOS (Enter para 'qualitydoc'): " dbName
if [ -z "$dbName" ]; then dbName="qualitydoc"; fi

isValidPassword=false
while [ "$isValidPassword" = false ]; do
    echo -e "${YELLOW}Ingresa una contrasena para PostgreSQL (minimo 6 caracteres):${NC}"
    read -p "Contrasena: " dbPasswordPlain
    if [ ${#dbPasswordPlain} -ge 6 ]; then
        isValidPassword=true
    else
        echo -e "${RED}ERROR: Demasiado corta.${NC}"
    fi
done

read -p "Ingresa la URL de la API de Login (Enter para 'http://127.0.0.1:5000'): " apiLoginUri
if [ -z "$apiLoginUri" ]; then apiLoginUri="http://127.0.0.1:5000"; fi

# 2.5. Detectar puerto disponible para PostgreSQL en el host
echo -e "\n${GREEN}Detectando puerto libre para PostgreSQL en el host...${NC}"
dbHostPort=5432

port_in_use() {
    local port=$1
    if command -v ss &>/dev/null; then
        ss -tuln | grep -q ":$port "
    elif command -v netstat &>/dev/null; then
        netstat -tuln | grep -q ":$port "
    else
        # Alternativa con /dev/tcp en bash si esta disponible
        (echo > /dev/tcp/127.0.0.1/$port) &>/dev/null
    fi
}

if port_in_use 5432; then
    echo -e "${YELLOW}Advertencia: El puerto 5432 ya esta ocupado en el host.${NC}"
    dbHostPort=5433
    while port_in_use $dbHostPort; do
        dbHostPort=$((dbHostPort + 1))
    done
    echo -e "${YELLOW}Se usara el puerto $dbHostPort para exponer PostgreSQL en el host.${NC}"
else
    echo -e "Puerto 5432 libre. Se usara este puerto."
fi

# 3. Guardar en .env
echo -e "\nGenerando archivo .env..."
cat <<EOF > .env
DB_USER=$dbUser
DB_PASSWORD=$dbPasswordPlain
DB_NAME=$dbName
API_LOGIN_URI=$apiLoginUri
DB_PORT_HOST=$dbHostPort
EOF

# 4. Limpiar e Iniciar Docker
echo -e "\n${CYAN}Limpiando y levantando contenedores...${NC}"
$DOCKER_COMPOSE_CMD down -v
$DOCKER_COMPOSE_CMD up -d --build

echo -e "\n${GREEN}========================================${NC}"
echo -e "${GREEN}  ¡Entorno configurado con exito!       ${NC}"
echo -e "${CYAN}  Aplicacion PHP en: http://localhost:8080${NC}"
echo -e "${CYAN}  PostgreSQL en el puerto: $dbHostPort        ${NC}"
echo -e "${GREEN}========================================${NC}"
