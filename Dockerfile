# Используем официальный образ PHP с поддержкой Apache
FROM php:8.0-apache

# Устанавливаем необходимые расширения PHP
RUN docker-php-ext-install pdo pdo_mysql

# Копируем файлы в контейнер
COPY . /var/www/html/

# Устанавливаем права на файлы
RUN chown -R www-data:www-data /var/www/html

# Открываем порт для Apache
EXPOSE 80
