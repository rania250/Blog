# Dockerfile pour Symfony 6.4 sur Render
FROM php:8.2-apache

# Installation des extensions PHP nécessaires
RUN apt-get update && apt-get install -y \
    git \
    unzip \
    libpq-dev \
    libzip-dev \
    && docker-php-ext-install \
    pdo \
    pdo_pgsql \
    zip \
    && a2enmod rewrite

# Installation de Composer
RUN curl -sS https://getcomposer.org/installer | php -- --install-dir=/usr/local/bin --filename=composer

# Configuration d'Apache pour Symfony
RUN echo '<VirtualHost *:80>\n\
    DocumentRoot /var/www/html/public\n\
    <Directory /var/www/html/public>\n\
        AllowOverride All\n\
        Require all granted\n\
    </Directory>\n\
    ErrorLog ${APACHE_LOG_DIR}/error.log\n\
    CustomLog ${APACHE_LOG_DIR}/access.log combined\n\
</VirtualHost>' > /etc/apache2/sites-available/000-default.conf

# Copie du code source
WORKDIR /var/www/html
COPY . .

# Installation des dépendances Composer
RUN composer install --no-dev --optimize-autoloader --no-interaction

# Permissions pour les dossiers var et public
RUN chown -R www-data:www-data var/ public/ && \
    chmod -R 775 var/

# Variables d'environnement par défaut
ENV APP_ENV=prod
ENV APP_SECRET=3aa254392aca1416e4b4f7df2010a289

# Exposition du port 80
EXPOSE 80

# Script de démarrage
RUN echo '#!/bin/bash\n\
# Clear cache\n\
php bin/console cache:clear --env=prod --no-debug\n\
# Run migrations\n\
php bin/console doctrine:migrations:migrate --no-interaction --env=prod\n\
# Start Apache\n\
apache2-foreground' > /usr/local/bin/start.sh && chmod +x /usr/local/bin/start.sh

CMD ["/usr/local/bin/start.sh"]