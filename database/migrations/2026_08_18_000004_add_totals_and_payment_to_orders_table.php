<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * تفصيل مبالغ الطلب (subtotal / shipping) وبيانات الدفع حتى يصبح نظام
     * الشحن والدفع قابلاً للتوسّع: طريقة الدفع، حالة الدفع، وإجمالي الشحن
     * المحسوب من إعدادات الشحن (قد يشمل أكثر من مورد).
     */
    public function up(): void
    {
        Schema::table('orders', function (Blueprint $table) {
            $table->decimal('subtotal', 10, 2)->default(0)->after('status');
            $table->decimal('shipping_total', 10, 2)->default(0)->after('subtotal');
            $table->string('payment_method')->default('cod')->after('total');   // cod | bank_transfer | ...
            $table->string('payment_status')->default('unpaid')->after('payment_method'); // unpaid | paid | refunded
        });
    }

    public function down(): void
    {
        Schema::table('orders', function (Blueprint $table) {
            $table->dropColumn(['subtotal', 'shipping_total', 'payment_method', 'payment_status']);
        });
    }
};
