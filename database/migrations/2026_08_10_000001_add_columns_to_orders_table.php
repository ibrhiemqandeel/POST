<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * جدول orders كان بلا أعمدة حقيقية (id + timestamps فقط، بالإضافة لـ
     * user_id المضاف سابقاً). نكمله الآن بالحقول اللازمة لعمل Checkout
     * ونظام إدارة طلبات حقيقي: الحالة، الإجمالي، وبيانات الشحن الأساسية.
     */
    public function up(): void
    {
        Schema::table('orders', function (Blueprint $table) {
            $table->string('status')->default('pending')->after('user_id'); // pending|processing|shipped|delivered|cancelled
            $table->decimal('total', 10, 2)->default(0)->after('status');
            $table->string('shipping_name')->nullable()->after('total');
            $table->string('shipping_email')->nullable()->after('shipping_name');
            $table->string('shipping_phone')->nullable()->after('shipping_email');
            $table->string('shipping_city')->nullable()->after('shipping_phone');
            $table->text('shipping_address')->nullable()->after('shipping_city');
            $table->text('notes')->nullable()->after('shipping_address');
        });
    }

    public function down(): void
    {
        Schema::table('orders', function (Blueprint $table) {
            $table->dropColumn([
                'status', 'total', 'shipping_name', 'shipping_email',
                'shipping_phone', 'shipping_city', 'shipping_address', 'notes',
            ]);
        });
    }
};
