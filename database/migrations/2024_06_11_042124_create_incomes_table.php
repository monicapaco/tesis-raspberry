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
        Schema::create('incomes', function (Blueprint $table) {
            $table->id();
            $table->foreignId('provider_id')->constrained(
                table: 'entities'
            );
            $table->string('type_voucher', length:20);
            $table->string('serial_voucher', length:7);
            $table->string('number_voucher', length:10);
            $table->string('status', length:20);
            //$table->decimal('total',total:10,places:2);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('incomes');
    }
};
