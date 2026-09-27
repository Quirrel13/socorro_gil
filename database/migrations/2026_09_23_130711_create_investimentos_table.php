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
        Schema::create('investimentos', function (Blueprint $table) {
            $table->id();
            $table->decimal('valor', 10, 2);
            $table->unsignedBigInteger('conta_id');
            $table->foreign('conta_id')->references('id')->on('contas');
            $table->unsignedBigInteger('tipo_investimento_id');
            $table->foreign('tipo_investimento_id')->references('id')->on('tipo_investimentos');
            $table->timestamps();
            $table->unique(['conta_id', 'tipo_investimento_id']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('investimentos');
    }
};
