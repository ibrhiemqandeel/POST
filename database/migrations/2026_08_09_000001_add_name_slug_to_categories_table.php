<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * جدول categories كان بلا أي أعمدة فعلية (id + timestamps فقط).
     * نضيف name وslug لنتمكن من ربط المنتجات بالفئات الحالية
     * (Women / Children / Beauty / Accessories) المستخدمة أصلاً في الموقع.
     */
    public function up(): void
    {
        Schema::table('categories', function (Blueprint $table) {
            $table->string('name')->after('id');
            $table->string('slug')->unique()->after('name');
        });
    }

    public function down(): void
    {
        Schema::table('categories', function (Blueprint $table) {
            $table->dropColumn(['name', 'slug']);
        });
    }
};
