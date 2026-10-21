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
    Schema::create('warranties', function (Blueprint $table) {
        $table->id();
        $table->string('title');
        $table->integer('duration_months');
        $table->enum('type', ['full', 'limited', 'maintenance_only'])->default('full');
        $table->text('terms')->nullable();
        $table->boolean('is_active')->default(true);
        $table->timestamps();
    });
}

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('warranties');
    }
};
