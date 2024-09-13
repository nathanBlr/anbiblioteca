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
        Schema::create('clientes', function (Blueprint $table) {
            $table->id();
            $table->string('nome');
            $table->string('nome_completo');
            $table->date('data_de_nascimento');
            $table->string('morada');
            $table->string('numero_de_telemovel')->nullable();
            $table->string('email')->nullable();
            $table->string('codigo_postal')->nullable();
            $table->boolean('cartao');
            $table->date('validade_cartao')->nullable();
            $table->string('nome_cartão')->nullable();
            $table->string('numero_cartao')->nullable();
            $table->softDeletes();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('clientes');
    }
};
