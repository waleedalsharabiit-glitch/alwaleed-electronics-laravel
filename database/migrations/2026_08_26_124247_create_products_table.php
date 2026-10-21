<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
   public function up(): void
{
    Schema::create('products', function (Blueprint $table) {
        $table->id();
        $table->foreignId('category_id')->constrained()->cascadeOnDelete();
        $table->foreignId('brand_id')->nullable()->constrained()->nullOnDelete();
        $table->foreignId('warranty_id')->nullable()->constrained()->nullOnDelete();
        
        $table->string('name');
        $table->string('slug')->unique();
        $table->string('sku')->unique();
        $table->text('short_description')->nullable();
        $table->longText('description')->nullable();
        
        $table->decimal('price', 12, 2);
        $table->decimal('sale_price', 12, 2)->nullable();
        $table->integer('stock_quantity')->default(0);
        
        // --- الأعمدة المفقودة المطلوبة ---
        $table->integer('critical_stock_alert')->default(5); // حد التنبيه لانخفاض المخزون
        $table->string('image')->nullable();                 // صورة المنتج الرئيسية
        $table->json('specifications')->nullable();          // المواصفات التقنية (JSON)
        $table->json('specs')->nullable();                  // تفاصيل إضافية
        // ----------------------------------
        
        $table->decimal('weight_kg', 8, 2)->nullable();
        $table->boolean('is_heavy_freight')->default(false);
        
        $table->boolean('is_active')->default(true);
        $table->boolean('is_featured')->default(false);
        $table->timestamps();
    });
}

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('products');
    }
};
