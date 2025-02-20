# Используем PHP 8.2 с Apache
FROM php:8.2-apache

# Устанавливаем необходимые расширения PHP
RUN docker-php-ext-install pdo pdo_mysql

# Устанавливаем Composer
COPY --from=composer:latest /usr/bin/composer /usr/bin/composer

# Установить Symfony CLI
RUN curl -sS https://get.symfony.com/cli/installer | bash && \
    mv /root/.symfony*/bin/symfony /usr/local/bin/symfony

# Устанавливаем зависимости Symfony
WORKDIR /var/www/html
COPY . .
RUN composer install --no-interaction --optimize-autoloader

# Устанавливаем права доступа
RUN chown -R www-data:www-data /var/www/html/var /var/www/html/public
RUN chmod -R 777 /var/www/html/var /var/www/html/public

# Запускаем сервер Symfony
CMD ["php", "-S", "0.0.0.0:8000", "-t", "public"]