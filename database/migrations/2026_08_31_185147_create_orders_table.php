<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void {
      Schema::create('orders', function (Blueprint $table) {
    $table->id();
    $table->foreignId('user_id')->nullable()->constrained()->nullOnDelete();
    $table->string('order_number')->unique();
    $table->string('customer_name');
    $table->string('customer_phone');
    $table->decimal('total_amount', 12, 2);
    $table->enum('payment_status', ['paid', 'pending', 'failed'])->default('paid');
    $table->enum('order_status', ['completed', 'processing', 'cancelled'])->default('completed');
    $table->text('notes')->nullable();
    $table->timestamps();
});
    }
    public function down(): void { Schema::dropIfExists('orders'); }
};