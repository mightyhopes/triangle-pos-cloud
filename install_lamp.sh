#!/bin/bash

# Configuration
APP_DIR="/var/www/triangle-pos"
DB_NAME="triangle_pos"
DB_USER="triangle_user"

# Colors
GREEN='\033[0;32m'
RED='\033[0;31m'
NC='\033[0m'

echo -e "${GREEN}Starting Native LAMP Setup (Fixed)...${NC}"

# 1. Install Dependencies (Apache, MySQL, PHP 8.2)
echo "Installing LAMP Stack..."
export DEBIAN_FRONTEND=noninteractive
export LC_ALL=C.UTF-8

# Clean apt cache to ensure fresh package lists
rm -rf /var/lib/apt/lists/*

apt-get update
apt-get install -y software-properties-common lsb-release ca-certificates apt-transport-https

# Add PHP PPA
add-apt-repository -y ppa:ondrej/php
apt-get update

# Added libapache2-mod-php8.2 and explicit php8.2
apt-get install -y apache2 mysql-server unzip git curl
apt-get install -y php8.2 php8.2-cli php8.2-common php8.2-mysql php8.2-zip php8.2-gd php8.2-mbstring php8.2-curl php8.2-xml php8.2-bcmath libapache2-mod-php8.2

# Verify PHP installation
if ! command -v php8.2 &> /dev/null; then
    echo -e "${RED}PHP 8.2 failed to install. Exiting.${NC}"
    exit 1
fi

# 2. Configure MySQL
echo "Configuring Database..."
# Generate secure password
DB_PASS=$(openssl rand -base64 12)

mysql -e "CREATE DATABASE IF NOT EXISTS $DB_NAME;"
mysql -e "CREATE USER IF NOT EXISTS '$DB_USER'@'localhost' IDENTIFIED BY '$DB_PASS';"
mysql -e "GRANT ALL PRIVILEGES ON $DB_NAME.* TO '$DB_USER'@'localhost';"
mysql -e "FLUSH PRIVILEGES;"

echo -e "${GREEN}Database Created. User: $DB_USER, Pass: $DB_PASS${NC}"

# 3. Setup Application
if [ ! -d "$APP_DIR" ]; then
    echo "Cloning repository..."
    git clone https://github.com/mightyhopes/triangle-pos-cloud.git $APP_DIR
fi

cd $APP_DIR
git pull

# Install Composer
echo "Installing Composer..."
if [ -f /usr/local/bin/composer ]; then
    rm /usr/local/bin/composer
fi
curl -sS https://getcomposer.org/installer | php8.2
mv composer.phar /usr/local/bin/composer
chmod +x /usr/local/bin/composer

# Install Dependencies
echo "Installing PHP Dependencies..."
# Use php8.2 explicitly to run composer if needed, but composer is a script
/usr/local/bin/composer install --no-dev --optimize-autoloader

# 4. Configure Environment
if [ ! -f .env ]; then
    cp .env.example .env
    sed -i "s|DB_DATABASE=triangle_pos|DB_DATABASE=$DB_NAME|" .env
    sed -i "s|DB_USERNAME=root|DB_USERNAME=$DB_USER|" .env
    sed -i "s|DB_PASSWORD=|DB_PASSWORD=$DB_PASS|" .env
    sed -i "s|APP_ENV=local|APP_ENV=production|" .env
    sed -i "s|APP_DEBUG=true|APP_DEBUG=false|" .env
    
    VPS_IP=$(curl -s ifconfig.me)
    sed -i "s|APP_URL=http://localhost|APP_URL=http://$VPS_IP|" .env
    
    php8.2 artisan key:generate
fi

# 5. Permissions
chown -R www-data:www-data $APP_DIR
chmod -R 775 $APP_DIR/storage $APP_DIR/bootstrap/cache

# 6. Configure Apache
echo "Configuring Apache..."
# Enable PHP module explicitly
a2enmod php8.2
a2enmod rewrite

cat > /etc/apache2/sites-available/triangle-pos.conf <<EOF
<VirtualHost *:80>
    ServerAdmin webmaster@localhost
    DocumentRoot $APP_DIR/public

    <Directory $APP_DIR/public>
        Options Indexes FollowSymLinks
        AllowOverride All
        Require all granted
    </Directory>

    ErrorLog \${APACHE_LOG_DIR}/error.log
    CustomLog \${APACHE_LOG_DIR}/access.log combined
</VirtualHost>
EOF

a2dissite 000-default.conf
a2ensite triangle-pos.conf
systemctl restart apache2

# 7. Run Migrations
echo "Running Migrations..."
php8.2 artisan migrate --force
php8.2 artisan db:seed --class=SuperUserSeeder
php8.2 artisan storage:link

echo -e "${GREEN}Deployment Complete!${NC}"
echo "Your app is live at: http://$(curl -s ifconfig.me)"
echo "Database Password: $DB_PASS"
