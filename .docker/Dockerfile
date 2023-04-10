ARG PHP_VERSION
ARG WEBSITE_NAME

FROM php:${PHP_VERSION}-apache-bullseye

RUN a2enmod rewrite
 
RUN apt-get update \
  && apt-get install -y libzip-dev git wget curl gnupg --no-install-recommends \
  && apt-get clean \
  && rm -rf /var/lib/apt/lists/* /tmp/* /var/tmp/*
 
RUN docker-php-ext-install pdo mysqli pdo_mysql zip;

RUN curl -fsSL https://deb.nodesource.com/setup_18.x | bash -
RUN apt-get -y install nodejs
RUN npm install -g yarn

RUN wget https://getcomposer.org/download/2.0.9/composer.phar \
    && mv composer.phar /usr/bin/composer && chmod +x /usr/bin/composer
 
COPY apache/cmd-vhost.conf /etc/apache2/sites-enabled/cmd-vhost.conf
 
WORKDIR /var/www/${WEBSITE_NAME}

COPY .. /var/www/${WEBSITE_NAME}

COPY entrypoint.sh /entrypoint.sh
RUN chmod +x /entrypoint.sh

CMD ["apache2-foreground"]

ENTRYPOINT ["/entrypoint.sh"]