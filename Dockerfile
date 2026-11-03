FROM php:8.2-apache

# Cài đặt extension PDO MySQL
RUN docker-php-ext-install pdo pdo_mysql

# Bật Apache rewrite
RUN a2enmod rewrite

# Đổi cổng lắng nghe từ 80 sang 10000 theo chuẩn của Render
RUN sed -i 's/80/10000/g' /etc/apache2/sites-available/000-default.conf \
    && sed -i 's/80/10000/g' /etc/apache2/ports.conf

# Copy mã nguồn vào thư mục web
WORKDIR /var/www/html
COPY . /var/www/html/

# Tự động tạo db.php và security.php từ mẫu nếu chưa tồn tại
RUN [ -f config/db.php ] || cp config/db.example.php config/db.php
RUN [ -f includes/security.php ] || cp includes/security.example.php includes/security.php

# Phân quyền thư mục
RUN chown -R www-data:www-data /var/www/html

EXPOSE 10000

CMD ["apache2-foreground"]
