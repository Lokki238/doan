FROM php:8.2-apache

# Cài đặt ca-certificates và extension PDO MySQL
RUN apt-get update && apt-get install -y ca-certificates && rm -rf /var/lib/apt/lists/*
RUN docker-php-ext-install pdo pdo_mysql

# Bật Apache rewrite và headers
RUN a2enmod rewrite headers

# Đổi cổng lắng nghe từ 80 sang 10000 theo chuẩn của Render
RUN sed -i 's/80/10000/g' /etc/apache2/sites-available/000-default.conf \
    && sed -i 's/80/10000/g' /etc/apache2/ports.conf

# Copy mã nguồn vào thư mục web
WORKDIR /var/www/html
COPY . /var/www/html/

# Tự động tạo db.php và security.php từ mẫu nếu chưa tồn tại
RUN [ -f csdl/db.php ] || cp csdl/db.example.php csdl/db.php
RUN [ -f php/includes/security.php ] || cp php/includes/security.example.php php/includes/security.php

# Phân quyền thư mục
RUN chown -R www-data:www-data /var/www/html

EXPOSE 10000

CMD ["apache2-foreground"]
