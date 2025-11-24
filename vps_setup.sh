#!/bin/bash

# Configuration
REPO_URL="https://github.com/mightyhopes/triangle-pos-cloud.git"
APP_DIR="/var/www/triangle-pos"

# Colors
GREEN='\033[0;32m'
RED='\033[0;31m'
NC='\033[0m'

echo -e "${GREEN}Starting Triangle POS VPS Setup...${NC}"

# 1. Update System
echo "Updating system packages..."
apt-get update && apt-get upgrade -y
apt-get install -y ca-certificates curl gnupg lsb-release

# 2. Install Docker (Manual Robust Method)
if ! command -v docker &> /dev/null; then
    echo "Installing Docker..."
    
    # Add Docker's official GPG key:
    mkdir -p /etc/apt/keyrings
    rm -f /etc/apt/keyrings/docker.gpg
    curl -fsSL https://download.docker.com/linux/ubuntu/gpg | gpg --dearmor -o /etc/apt/keyrings/docker.gpg
    chmod a+r /etc/apt/keyrings/docker.gpg

    # Set up the repository:
    echo \
      "deb [arch=$(dpkg --print-architecture) signed-by=/etc/apt/keyrings/docker.gpg] https://download.docker.com/linux/ubuntu \
      $(lsb_release -cs) stable" | tee /etc/apt/sources.list.d/docker.list > /dev/null
      
    apt-get update
    
    # Install Docker Engine (without the problematic docker-model-plugin)
    apt-get install -y docker-ce docker-ce-cli containerd.io docker-buildx-plugin docker-compose-plugin
    
    if ! command -v docker &> /dev/null; then
        echo -e "${RED}Docker installation failed. Please install Docker manually and re-run.${NC}"
        exit 1
    fi
else
    echo "Docker is already installed."
fi

# 3. Clone Repository
if [ -d "$APP_DIR" ]; then
    echo "Directory exists. Pulling latest changes..."
    cd $APP_DIR
    git pull
else
    echo "Cloning repository..."
    git clone $REPO_URL $APP_DIR
    cd $APP_DIR
fi

# 4. Setup Environment Variables
if [ ! -f .env ]; then
    echo "Creating .env file..."
    cp .env.example .env
    
    # Generate random passwords
    DB_PASS=$(openssl rand -base64 12)
    APP_KEY="base64:$(openssl rand -base64 32)"
    VPS_IP=$(curl -s ifconfig.me)
    
    # Update .env (Using | as delimiter to avoid conflict with / in APP_KEY)
    sed -i "s|DB_PASSWORD=|DB_PASSWORD=$DB_PASS|" .env
    sed -i "s|DB_USERNAME=root|DB_USERNAME=triangle_user|" .env
    sed -i "s|DB_DATABASE=triangle_pos|DB_DATABASE=triangle_pos|" .env
    sed -i "s|APP_KEY=|APP_KEY=$APP_KEY|" .env
    sed -i "s|APP_ENV=local|APP_ENV=production|" .env
    sed -i "s|APP_DEBUG=true|APP_DEBUG=false|" .env
    sed -i "s|APP_URL=http://localhost|APP_URL=http://$VPS_IP|" .env
    
    # Add VPS_IP to .env for Docker Compose substitution
    echo "VPS_IP=$VPS_IP" >> .env
    
    echo -e "${GREEN}Generated secure passwords and configured IP ($VPS_IP).${NC}"
fi

# Ensure VPS_IP is in .env if file already existed
if ! grep -q "VPS_IP=" .env; then
    VPS_IP=$(curl -s ifconfig.me)
    echo "VPS_IP=$VPS_IP" >> .env
    sed -i "s|APP_URL=http://localhost|APP_URL=http://$VPS_IP|" .env
fi

# 5. Start Application
echo "Starting Docker containers..."
docker compose down # Stop if running
docker compose up -d --build

# 6. Run Migrations & Seed
echo "Waiting for database to initialize (30s)..."
sleep 30
echo "Running migrations..."
docker compose exec -T app php artisan migrate --force
docker compose exec -T app php artisan db:seed --class=SuperUserSeeder
docker compose exec -T app php artisan storage:link

echo -e "${GREEN}Deployment Complete!${NC}"
echo "Your app should be live at: http://$(curl -s ifconfig.me)"
