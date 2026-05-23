<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('orders', function (Blueprint $table) {
            $table->id();
            
            // Kolom baru yang menyebabkan error
            $table->string('code')->unique();
            
            $table->foreignId('user_id')->constrained()->cascadeOnDelete();

           
            $table->foreignId('product_id')->nullable()->constrained()->cascadeOnDelete();
            $table->integer('quantity')->nullable();

            $table->decimal('total_price', 12, 2);

            $table->enum('status', ['pending', 'paid', 'shipped', 'cancelled'])->default('pending');
            
            // Kolom baru yang menyebabkan error
            $table->string('payment_status')->default('unpaid'); 
            
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('orders');
    }
};