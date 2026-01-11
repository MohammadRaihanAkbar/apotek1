<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('products', function (Blueprint $table) {
            $table->id();

            $table->string('kode_obat')->unique();
            $table->string('name');
            $table->text('description')->nullable();

            $table->foreignId('category_id')
                ->constrained('categories')
                ->restrictOnDelete();


            $table->string('image')->nullable();

            $table->integer('stock')->default(0);
            $table->decimal('price', 12, 2)->default(0);

            $table->boolean('is_active')->default(true);

            $table->timestamps();
            $table->index(['name']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('products');
    }
};
