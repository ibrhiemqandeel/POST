<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * ربط كل منتج بمورّده الحقيقي (supplier_id) مع تكلفة/مدة الشحن الخاصة
     * بهذا المنتج من هذا المورد. حقول supplier_name/supplier_platform القديمة
     * تبقى كنسخة نصية للتوافق، لكن المصدر الحقيقي الآن هو جدول suppliers.
     */
    public function up(): void
    {
        Schema::table('products', function (Blueprint $table) {
            $table->foreignId('supplier_id')->nullable()->after('category_id')
                ->constrained('suppliers')->nullOnDelete();
            $table->decimal('shipping_cost', 10, 2)->nullable()->after('cost_price');
            $table->unsignedInteger('shipping_days')->nullable()->after('shipping_cost');
        });
    }

    public function down(): void
    {
        Schema::table('products', function (Blueprint $table) {
            $table->dropConstrainedForeignId('supplier_id');
            $table->dropColumn(['shipping_cost', 'shipping_days']);
        });
    }
};
