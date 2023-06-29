FROM php:8.2-apache
RUN echo "ServerName localhost" >> /etc/apache2/apache2.conf
RUN pecl install redis
RUN docker-php-ext-enable redis
RUN a2enmod rewrite