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
        Schema::create('projects', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('slug')->unique();
            $table->text('description');
            $table->longText('full_description')->nullable();
            $table->string('image');
            $table->json('gallery')->nullable(); // Array de imágenes adicionales
            $table->enum('status', ['development', 'deployed', 'paused', 'completed'])->default('development');
            $table->date('start_date');
            $table->date('end_date')->nullable();
            $table->string('url')->nullable(); // URL del proyecto desplegado
            $table->string('repository')->nullable(); // URL del repositorio GitHub
            $table->json('features')->nullable(); // Array de características del proyecto
            $table->boolean('is_featured')->default(false);
            $table->boolean('is_active')->default(true);
            $table->integer('order')->default(0);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('projects');
    }
};
