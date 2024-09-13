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
        Schema::create('livros', function (Blueprint $table) {
            $table->id();
            $table->foreignId('seccao_id')
                ->constrained()
                ->cascadeOnDelete();
            $table->string('titulo');
            $table->integer('numero_de_paginas');
            $table->date('data_de_publicacao');
            $table->enum('classificacao', ['livre', 'juvenil', 'maduro', 'adulto'])->default('adulto');
            $table->string('sinopse')->nullable();
            $table->softDeletes();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('livros');
    }
};
