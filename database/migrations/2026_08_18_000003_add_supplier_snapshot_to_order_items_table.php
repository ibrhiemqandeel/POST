<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * لقطة (snapshot) للمورد وتكلفته على مستوى كل سطر طلب، حتى يستطيع الطلب
     * الواحد أن يحتوي منتجات من أكثر من مورد (Multi-Supplier Order)، ويمكن
     * تجميع كل مورد وحده لإرسال أمر الشراء إليه وحساب الربح الفعلي.
     */
    public function up(): void
    {
        Schema::table('order_items', function (Blueprint $table) {
            $table->foreignId('supplier_id')->nullable()->after('product_id')
                ->constrained('suppliers')->nullOnDelete();
            $table->string('supplier_name')->nullable()->after('supplier_id');
            $table->decimal('cost_price', 10, 2)->nullable()->after('price'); // تكلفة القطعة وقت الطلب
        });
    }

    public function down(): void
    {
        Schema::table('order_items', function (Blueprint $table) {
            $table->dropConstrainedForeignId('supplier_id');
            $table->dropColumn(['supplier_name', 'cost_price']);
        });
    }
};
