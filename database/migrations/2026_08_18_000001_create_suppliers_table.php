<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * جدول الموردين (Suppliers) — أساس نظام الـ Multi-Supplier / Multi-Vendor /
     * Dropshipping. كل مورد له دولة ومنصة وعملة وتكلفة/مدة شحن افتراضية،
     * ويُربط بمنتجاته. النظام يدعم موردين من دول مختلفة في نفس المتجر.
     */
    public function up(): void
    {
        Schema::create('suppliers', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('slug')->unique();
            $table->string('country')->nullable();          // دولة المورد (شحن دولي)
            $table->string('platform')->nullable();         // CJ Dropshipping | AliExpress | Local ...
            $table->string('contact_email')->nullable();
            $table->string('contact_phone')->nullable();
            $table->string('currency', 8)->default('USD');  // عملة تكلفة المورد
            $table->decimal('default_shipping_cost', 10, 2)->default(0);
            $table->unsignedInteger('shipping_days_min')->nullable();
            $table->unsignedInteger('shipping_days_max')->nullable();
            $table->boolean('is_active')->default(true);
            $table->text('notes')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('suppliers');
    }
};
