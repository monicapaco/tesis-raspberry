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
        Schema::create('detail_sales', function (Blueprint $table) {
            $table->id();
            $table->foreignId('sale_id')->constrained(
                table: 'sales'
            );
            $table->foreignId('item_id')->constrained(
                table: 'items'
            );
            $table->tinyInteger('quantity');
            $table->decimal('sale_price',total:10,places:2);
            //$table->decimal('discount',total:10,places:2);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('detail_sales');
    }
};
