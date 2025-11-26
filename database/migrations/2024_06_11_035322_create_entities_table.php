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
        Schema::create('entities', function (Blueprint $table) {
            $table->id();
            $table->string('type', length:25);
            $table->string('name',length:100);
            $table->string('type_document',length:20);
            $table->string('n_document',length:15);
            $table->string('address',length:70);
            $table->string('region',length:50);
            $table->string('province',length:50);
            $table->string('district',length:50);
            $table->string('phone',length:15);
            $table->string('email',length:50);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('entities');
    }
};
