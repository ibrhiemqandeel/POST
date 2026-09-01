# استخدم نسخة PHP الرسمية مع FPM ونظام دبيان النظيف
FROM php:8.2-fpm

# تعيين دليل العمل داخل الحاوية
WORKDIR /var/www

# تثبيت الاعتماديات الأساسية للنظام وإضافات PHP المطلوبة لـ Laravel و PostgreSQL
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
    libpq-dev \
    nginx

# تنظيف التخزين المؤقت للملفات لتقليل حجم الحاوية
RUN apt-get clean && rm -rf /var/lib/apt/lists/*

# تثبيت إضافات PHP المخصصة لـ Laravel (بما فيها pdo_pgsql و pgsql)
RUN docker-php-ext-install pdo pdo_mysql pdo_pgsql pgsql mbstring zip exif pcntl
RUN docker-php-ext-configure gd --with-freetype --with-jpeg
RUN docker-php-ext-install gd

# تثبيت أداة Composer داخل الحاوية
COPY --from=composer:latest /usr/bin/composer /usr/bin/composer

# نسخ ملفات المشروع بالكامل إلى الحاوية
COPY . /var/www

# تثبيت اعتماديات PHP الخاصة بـ Laravel بدون حزم التطوير
RUN composer install --no-interaction --optimize-autoloader --no-dev

# إنشاء مجلدات التخزين والجلسات وقاعدة البيانات تلقائياً
RUN mkdir -p /var/www/storage/framework/sessions \
    /var/www/storage/framework/views \
    /var/www/storage/framework/cache \
    /var/www/database \
    && touch /var/www/database/database.sqlite

# ضبط ملكية وصلاحيات المجلدات
RUN chown -R www-data:www-data /var/www/storage /var/www/bootstrap/cache /var/www/database \
    && chmod -R 775 /var/www/storage /var/www/bootstrap/cache /var/www/database

# نسخ إعدادات Nginx المخصصة لـ Laravel داخل الحاوية
COPY ./nginx.conf /etc/nginx/sites-available/default

# فتح المنفذ 80 للاستضافة
EXPOSE 80

# أمر التشغيل: تشغيل الميجريشن (يطبّق أي تعديل ناقص على قاعدة بيانات الإنتاج
# تلقائياً عند كل نشر — بلا حاجة لوصول Shell)، ثم تنظيف الكاش وتشغيل الخادم.
# migrate يُجعَل غير قاتل (|| true) حتى لا يمنع أي تعثّر مؤقت في الاتصال بقاعدة
# البيانات وقت الإقلاع تشغيلَ خادم الويب.
CMD ["sh", "-c", "php artisan migrate --force || true; php artisan route:clear && php artisan config:clear && php artisan cache:clear && service nginx start && php-fpm"]
