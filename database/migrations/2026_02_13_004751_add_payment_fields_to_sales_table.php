<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('sales', function (Blueprint $table) {

            // Tipo de pago
            $table->string('payment_type',20)
                  ->nullable()
                  ->after('status');

            // Estado del pago
            $table->string('payment_status',20)
                  ->nullable()
                  ->after('payment_type');

            // Fecha y hora en que se pagó
            $table->timestamp('paid_at')
                  ->nullable()
                  ->after('payment_status');
        });
    }

    public function down(): void
    {
        Schema::table('sales', function (Blueprint $table) {

            $table->dropColumn([
                'payment_type',
                'payment_status',
                'paid_at'
            ]);
        });
    }
};
