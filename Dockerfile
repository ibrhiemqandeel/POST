# استخدم نسخة PHP الرسمية مع FPM ونظام دبيان النظيف
FROM php:8.2-fpm

# تعيين دليل العمل داخل الحاوية
WORKDIR /var/www

# تثبيت الاعتماديات الأساسية للنظام وإضافات PHP المطلوبة لـ Laravel
RUN apt-get update && apt-get install -y \
    build-essential \
    libpng-dev \
    libjpeg62-turbo-dev \
    libfreetype6-dev \
    locales \
    zip \
    jpegoptim optipng pngquant gifsicle \
    vim \
    unzip \
    git \
    curl \
    libzip-dev \
    libonig-dev \
    nginx

# تنظيف التخزين المؤقت للملفات لتقليل حجم الحاوية
RUN apt-get clean && rm -rf /var/lib/apt/lists/*

# تثبيت إضافات PHP المخصصة لـ Laravel (قواعد البيانات، النصوص، الضغط)
RUN docker-php-ext-install pdo_mysql mbstring zip exif pcntl
RUN docker-php-ext-configure gd --with-freetype --with-jpeg
RUN docker-php-ext-install gd

# تثبيت أداة Composer داخل الحاوية
COPY --from=composer:latest /usr/bin/composer /usr/bin/composer

# نسخ ملفات المشروع بالكامل إلى الحاوية
COPY . /var/www

# تثبيت اعتماديات PHP الخاصة بـ Laravel بدون حزم التطوير وحزم الاختبار
RUN composer install --no-interaction --optimize-autoloader --no-dev

# --- التعديل الجديد: إنشاء مجلد وقاعدة بيانات SQLite تلقائياً ---
RUN mkdir -p /var/www/database && touch /var/www/database/database.sqlite

# ضبط الصلاحيات للمجلدات الحساسة وملف قاعدة البيانات (خطوة إجبارية لـ Laravel)
RUN chown -R www-data:www-data /var/www/storage /var/www/bootstrap/cache /var/www/database

# نسخ إعدادات Nginx المخصصة لـ Laravel داخل الحاوية
COPY ./nginx.conf /etc/nginx/sites-available/default

# فتح المنفذ 80 للاستضافة
EXPOSE 80

# أمر التشغيل الذي يقوم بتشغيل PHP-FPM و خادم Nginx معاً عند بدء الحاوية
# أمر التشغيل: تنفيذ الترحيل لقاعدة البيانات أولاً، ثم تشغيل Nginx و PHP-FPM
CMD php artisan migrate --force && service nginx start && php-fpm
