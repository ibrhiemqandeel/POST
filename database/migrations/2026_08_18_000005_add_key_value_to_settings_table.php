<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * جدول settings كان فارغاً (id + timestamps فقط). نحوّله إلى مخزن
     * key/value حقيقي يُستخدم لإعدادات المتجر: روابط السوشيال ميديا، إعدادات
     * الشحن والدفع، ومعلومات المتجر العامة — كلها قابلة للتعديل من لوحة التحكم.
     */
    public function up(): void
    {
        Schema::table('settings', function (Blueprint $table) {
            $table->string('key')->unique()->after('id');
            $table->text('value')->nullable()->after('key');
        });
    }

    public function down(): void
    {
        Schema::table('settings', function (Blueprint $table) {
            $table->dropColumn(['key', 'value']);
        });
    }
};
