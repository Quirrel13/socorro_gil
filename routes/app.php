<?php

use App\Http\Controllers\AuditoriaController;
use App\Http\Controllers\ContaController;
use App\Http\Controllers\SolicitacaoController;
use App\Http\Controllers\UserController;
use Illuminate\Support\Facades\Route;

Route::get('/home', function () {
    return view('home');
})->name('home')->middleware(['auth', 'verified']);

Route::get('/audit/curso/{id}', [CursoController::class, 'audit'])
    ->name('curso.audit')->middleware(['auth', 'verified']);

    Route::middleware(['auth', 'verified'])->group(function () {

    // Gerente Geral: gerentes de conta e auditoria
    Route::resource('/users', UserController::class);
    Route::get('/auditoria', [AuditoriaController::class, 'index'])->name('auditoria.index');

    // Gerente de Conta: contas dos clientes
    Route::resource('/conta', ContaController::class)->parameters(['conta' => 'conta']);;
    Route::patch('/conta/{conta}/bloquear', [ContaController::class, 'bloquear'])->name('conta.bloquear');
    Route::patch('/conta/{conta}/desbloquear', [ContaController::class, 'desbloquear'])->name('conta.desbloquear');
    Route::get('/conta/{conta}/extrato', [ContaController::class, 'extrato'])->name('conta.extrato');

    // Solicitações de aumento de limite
    Route::resource('/solicitacao', SolicitacaoController::class)->only(['index', 'create', 'store']);
    Route::patch('/solicitacao/{solicitacao}/aprovar', [SolicitacaoController::class, 'aprovar'])->name('solicitacao.aprovar');
    Route::patch('/solicitacao/{solicitacao}/recusar', [SolicitacaoController::class, 'recusar'])->name('solicitacao.recusar');
});
