<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('offers', function (Blueprint $table) {
            $table->ulid('id')->primary();
            $table->unsignedBigInteger('external_id')->unique();
            $table->string('product');
            $table->unsignedBigInteger('price');
            $table->string('currency');
            $table->longText('description');
            $table->foreignUlid('shop_id')->constrained()->restrictOnDelete()->cascadeOnUpdate();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('offers');
    }
};
