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
        Schema::create('contas', function (Blueprint $table) {
            $table->id();
            $table->decimal('saldo', 10, 2)->default(0.00);
            $table->decimal('limite', 10, 2);
            $table->boolean('bloqueado')->default(false);
            $table->unsignedBigInteger('cliente_id');
            $table->foreign('cliente_id')->references('id')->on('users');
            $table->unsignedBigInteger('gerente_id');
            $table->foreign('gerente_id')->references('id')->on('users');
            $table->timestamps();
            $table->softDeletes();
            $table->unique('cliente_id');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('contas');
    }
};
