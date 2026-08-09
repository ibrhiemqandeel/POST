<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('cart_items', function (Blueprint $table) {
            $table->foreignId('cart_id')->after('id')->constrained('carts')->cascadeOnDelete();
            $table->foreignId('product_id')->after('cart_id')->constrained('products')->cascadeOnDelete();
            $table->unsignedInteger('quantity')->default(1)->after('product_id');
            // نخزّن السعر وقت الإضافة للسلة (Price snapshot) حتى لا يتغيّر
            // إجمالي سلة عميل حالي إذا عدّل الأدمن سعر المنتج لاحقاً.
            $table->decimal('price', 10, 2)->after('quantity');
        });
    }

    public function down(): void
    {
        Schema::table('cart_items', function (Blueprint $table) {
            $table->dropConstrainedForeignId('product_id');
            $table->dropConstrainedForeignId('cart_id');
            $table->dropColumn(['quantity', 'price']);
        });
    }
};
