<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * جدول carts كان فارغاً بلا أعمدة. نضيف دعم سلة لكل مستخدم مسجل
     * (user_id) وسلة للزوار غير المسجلين (session_id) بحيث تعمل السلة
     * فوراً بدون إجبار المستخدم على تسجيل الدخول، تماماً كما كانت تعمل
     * سابقاً (بشكل وهمي) عبر localStorage في public/app.js.
     */
    public function up(): void
    {
        Schema::table('carts', function (Blueprint $table) {
            $table->foreignId('user_id')->nullable()->after('id')->constrained('users')->cascadeOnDelete();
            $table->string('session_id')->nullable()->after('user_id')->index();
        });
    }

    public function down(): void
    {
        Schema::table('carts', function (Blueprint $table) {
            $table->dropConstrainedForeignId('user_id');
            $table->dropColumn('session_id');
        });
    }
};
