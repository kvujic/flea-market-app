<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{

    public function up(): void
    {
        Schema::create('items', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->cascadeOnDelete();
            $table->string('name');
            $table->string('description', 255);
            $table->string('brand')->nullable();
            $table->decimal('price', 10, 2)->unsigned();
            $table->boolean('is_sold')->default(false);
            $table->foreignId('condition_id')->constrained()->cascadeOnDelete();
            $table->string('item_image');
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('items');
    }
};
