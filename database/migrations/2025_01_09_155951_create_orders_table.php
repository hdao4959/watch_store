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
        Schema::create('orders', function (Blueprint $table) {
            $table->id();
            $table->string('full_name',50);
            $table->string('phone_number',20);
            $table->string('address',100);
            $table->enum('type_pay', [0, 1])->default(0);// 0: thanh toán khi nhận hàng - 1: thanh toán online
            $table->unsignedBigInteger('id_user')->nullable();
            $table->decimal('amount');
            $table->enum('status', ['pending', 'expired', 'completed', 'failed', 'canceled'])->default('pending');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('orders');
    }
};
