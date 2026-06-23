FROM php:8.2-apache
RUN chown -R www-data:www-data /var/www/html
COPY . /var/www/html/
EXPOSE 80
