FROM php:7.1-apache

# Atualiza o sistema e instala pacotes necessários
RUN apt-get update && apt-get upgrade -y && apt-get install -y nano libpq-dev libmemcached-dev zlib1g-dev libpng-dev libwebp-dev libjpeg62-turbo-dev libxpm-dev libfreetype6-dev libzip-dev unzip git tzdata libxml2-dev libapache2-mod-evasive gettext libzstd-dev wget curl gnupg libgd-dev \
    && docker-php-ext-install pdo pdo_pgsql pgsql gd zip exif soap gettext mysqli pdo_mysql bcmath \
    && docker-php-ext-configure gd --with-freetype --with-jpeg \
    && pecl install memcached xdebug igbinary redis \
    && docker-php-ext-enable memcached xdebug igbinary exif \
    && docker-php-ext-configure pgsql -with-pgsql=/usr/local/pgsql \
    && a2enmod rewrite headers ssl evasive proxy proxy_http \
    && wget -O /tmp/libapache2-mod-proxy-html.deb 'http://security.ubuntu.com/ubuntu/pool/universe/a/apache2/libapache2-mod-proxy-html_2.4.7-1ubuntu4.22_amd64.deb' \
    && dpkg -i /tmp/libapache2-mod-proxy-html.deb \
    && rm -f /tmp/libapache2-mod-proxy-html.deb \
    && service apache2 restart \
    && mkdir -p /var/php-session \
    && chmod -Rf 777 /var/php-session \
    && addgroup --gid 1024 docker-apache || true \
    && usermod -a -G docker-apache www-data || true \
    && curl -sS https://getcomposer.org/installer | php -- --install-dir=/usr/bin --filename=composer \
    && curl -sL https://deb.nodesource.com/setup_18.x | bash - \
    && apt-get -y install nodejs \
    && echo 'America/Sao_Paulo' > /etc/timezone \
    && rm /etc/localtime \
    && ln -snf /usr/share/zoneinfo/America/Sao_Paulo /etc/localtime \
    && dpkg-reconfigure -f noninteractive tzdata \
    && a2enmod proxy \
    && a2enmod proxy_http \
    && service apache2 restart \
    && mkdir -p /var/www/html/storage \
    && chmod -R 777 /var/www/html/storage \
    && mkdir -p /var/www/html/public/upload \
    && chmod -R 777 /var/www/html/public/upload \
    && apt-get clean && rm -rf /var/lib/apt/lists/*

RUN mkdir -p /var/php-session && chmod -R 777 /var/php-session
RUN if ! a2enmod rewrite; then echo "Failed to enable mod_rewrite" && exit 1; fi

# Define um diretório de trabalho
WORKDIR /var/www/html