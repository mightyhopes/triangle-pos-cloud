#!/bin/bash

# Configuration
REPO_URL="https://github.com/mightyhopes/triangle-pos-cloud.git"
APP_DIR="/var/www/triangle-pos"

# Colors
GREEN='\033[0;32m'
NC='\033[0m'

echo -e "${GREEN}Starting Triangle POS VPS Setup...${NC}"

# 1. Update System
echo "Updating system packages..."
apt-get update && apt-get upgrade -y

# 2. Install Docker & Docker Compose
if ! command -v docker &> /dev/null; then
    echo "Installing Docker..."
    curl -fsSL https://get.docker.com -o get-docker.sh
    sh get-docker.sh
    rm get-docker.sh
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
    
    # Update .env
    sed -i "s/DB_PASSWORD=/DB_PASSWORD=$DB_PASS/" .env
    sed -i "s/DB_USERNAME=root/DB_USERNAME=triangle_user/" .env
    sed -i "s/DB_DATABASE=triangle_pos/DB_DATABASE=triangle_pos/" .env
    sed -i "s/APP_KEY=/APP_KEY=$(echo $APP_KEY | sed 's/\//\\\//g')/" .env
    sed -i "s/APP_ENV=local/APP_ENV=production/" .env
    sed -i "s/APP_DEBUG=true/APP_DEBUG=false/" .env
    
    echo -e "${GREEN}Generated secure passwords.${NC}"
fi

# 5. Start Application
echo "Starting Docker containers..."
docker compose up -d --build

# 6. Run Migrations & Seed
echo "Waiting for database to initialize (10s)..."
sleep 10
echo "Running migrations..."
docker compose exec app php artisan migrate --force
docker compose exec app php artisan db:seed --class=SuperUserSeeder
docker compose exec app php artisan storage:link

echo -e "${GREEN}Deployment Complete!${NC}"
echo "Your app should be live at: http://$(curl -s ifconfig.me)"
