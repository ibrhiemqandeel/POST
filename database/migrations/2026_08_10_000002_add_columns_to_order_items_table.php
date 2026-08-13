<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('order_items', function (Blueprint $table) {
            $table->foreignId('order_id')->after('id')->constrained('orders')->cascadeOnDelete();
            // product_id nullable + nullOnDelete: لو انحذف المنتج لاحقاً، يبقى
            // سطر الطلب موجوداً (بالاسم والسعر المحفوظين) بدل ما ينحذف الطلب نفسه.
            $table->foreignId('product_id')->nullable()->after('order_id')->constrained('products')->nullOnDelete();
            $table->string('product_name')->after('product_id'); // نسخة من اسم المنتج وقت الطلب
            $table->unsignedInteger('quantity')->default(1)->after('product_name');
            $table->decimal('price', 10, 2)->after('quantity'); // سعر القطعة وقت الطلب
        });
    }

    public function down(): void
    {
        Schema::table('order_items', function (Blueprint $table) {
            $table->dropConstrainedForeignId('product_id');
            $table->dropConstrainedForeignId('order_id');
            $table->dropColumn(['product_name', 'quantity', 'price']);
        });
    }
};
