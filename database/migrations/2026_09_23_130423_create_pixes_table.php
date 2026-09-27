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
        Schema::create('pixes', function (Blueprint $table) {
            $table->id();
            $table->decimal('valor', 10, 2);
            $table->unsignedBigInteger('conta_origem_id');
            $table->foreign('conta_origem_id')->references('id')->on('contas');
            $table->unsignedBigInteger('conta_destino_id');
            $table->foreign('conta_destino_id')->references('id')->on('contas');
            $table->string('descricao')->default('Nenhum comentário');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('pixes');
    }
};
