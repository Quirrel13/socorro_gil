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
        Schema::create('solicitacaos', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('conta_id');
            $table->foreign('conta_id')->references('id')->on('contas');
            $table->decimal('limite', 10, 2);
            $table->string('status');
            $table->unsignedBigInteger('gerente_id')->nullable();
            $table->foreign('gerente_id')->references('id')->on('users');
            $table->string('motivo_recusa')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('solicitacaos');
    }
};
