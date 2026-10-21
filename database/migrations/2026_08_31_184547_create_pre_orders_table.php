<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
       Schema::create('pre_orders', function (Blueprint $table) {
    $table->id();
    // أضف السطر هنا بدون استخدام after()
    $table->foreignId('user_id')->nullable()->constrained()->nullOnDelete(); 
    
    $table->string('reference_code');
    $table->string('customer_name');
    $table->string('customer_phone');
    $table->foreignId('product_id')->constrained()->cascadeOnDelete();
    $table->integer('quantity')->default(1);
    $table->decimal('total_price', 10, 2);
    $table->decimal('deposit_amount', 10, 2)->default(0);
    $table->decimal('remaining_amount', 10, 2)->default(0);
    $table->date('expected_date');
    $table->enum('status', ['pending', 'confirmed', 'arrived', 'delivered', 'cancelled'])->default('pending');
    $table->text('notes')->nullable();
    $table->timestamps();
});
    }

    public function down(): void
    {
        Schema::dropIfExists('pre_orders');
    }
};