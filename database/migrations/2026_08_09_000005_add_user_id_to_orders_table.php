<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * DashboardController كان يستدعي Order::with('user') رغم أن جدول orders
     * لا يملك عمود user_id ولا Order model علاقة user() — ما كان يسبب خطأ
     * "Call to undefined relationship [user]" فور محاولة عرض لوحة تحكم الأدمن
     * (بعد إصلاح مشكلة الـ view المفقودة). هذا العمود ضروري لإتمام هذه الوظيفة.
     */
    public function up(): void
    {
        Schema::table('orders', function (Blueprint $table) {
            $table->foreignId('user_id')->nullable()->after('id')->constrained('users')->nullOnDelete();
        });
    }

    public function down(): void
    {
        Schema::table('orders', function (Blueprint $table) {
            $table->dropConstrainedForeignId('user_id');
        });
    }
};
