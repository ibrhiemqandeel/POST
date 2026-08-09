<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * حقول مطلوبة لتفعيل لوحة تحكم الأدمن الموجودة فعلياً في
     * resources/views/dashboard.blade.php (كانت واجهة وهمية فقط تعمل على
     * مصفوفة JS محلية). هذه الحقول (سعر التكلفة، المورد، حالة المزامنة)
     * كانت ظاهرة أصلاً في نموذج الإضافة/التعديل بالواجهة، فأضفناها في
     * قاعدة البيانات لتفعيلها فعلياً بدل حذفها من الواجهة.
     */
    public function up(): void
    {
        Schema::table('products', function (Blueprint $table) {
            $table->decimal('cost_price', 10, 2)->nullable()->after('price');
            $table->string('supplier_platform')->nullable()->after('cost_price');
            $table->string('supplier_name')->nullable()->after('supplier_platform');
            $table->string('sync_status')->default('synced')->after('stock'); // synced | pending | out
        });
    }

    public function down(): void
    {
        Schema::table('products', function (Blueprint $table) {
            $table->dropColumn(['cost_price', 'supplier_platform', 'supplier_name', 'sync_status']);
        });
    }
};
