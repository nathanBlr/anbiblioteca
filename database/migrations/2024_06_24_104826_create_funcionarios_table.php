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
        Schema::create('funcionarios', function (Blueprint $table) {
            $table->id();
            $table->string('nome');
            $table->string('nome_completo');
            $table->date('data_de_nascimento');
            $table->string('nacionalidade');
            $table->date('data_de_contrato');
            $table->string('foto')->nullable();
            $table->string('numero_de_telemovel');
            $table->string('email')->unique();
            $table->string('morada');
            $table->string('codigo_postal');
            $table->integer('redimento_salarial');
            $table->foreignId('tipo_de_contrato_id')
                ->constrained('tipo_de_contrato')
                ->onDelete('cascade');
            $table->string('password');
            $table->rememberToken();
            $table->softDeletes();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('funcionarios');
    }
};
