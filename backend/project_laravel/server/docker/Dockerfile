ARG IMAGE

FROM $IMAGE

ARG TZ

# Aggiorno i pacchetti e prendo alcune dipendenze utili
RUN apt update -y && apt install -y \
    git \
    zip \
    wget \
    curl \
    default-mysql-client \
    && rm -rf /var/lib/apt/lists/*

# Installa il modulo rewrite e riavvia servizio
RUN a2enmod rewrite && service apache2 restart

# Installa composer
RUN curl -sS https://getcomposer.org/installer | php -- --install-dir=/usr/local/bin --filename=composer

# Installa pdo e pdo_mysql 
RUN docker-php-ext-install pdo pdo_mysql

# Abilita pdo / pdo_mysql e xdebug
RUN docker-php-ext-enable pdo pdo_mysql

# Imposta la regione del server
RUN ln -snf /usr/share/zoneinfo/$TZ /etc/localtime


