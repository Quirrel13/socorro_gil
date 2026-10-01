<?php

use App\Http\Controllers\Api\AuthController;
use App\Http\Controllers\Api\ContaController;
use App\Http\Controllers\Api\ExtratoController;
use App\Http\Controllers\Api\InvestimentoController;
use App\Http\Controllers\Api\MovimentacaoInvestimentoController;
use App\Http\Controllers\Api\PixController;
use App\Http\Controllers\CursoController;
use App\Http\Controllers\DisciplinaController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

Route::post('/login', [AuthController::class, 'login']);
Route::post('/logout', [AuthController::class, 'logout'])->middleware('auth:sanctum');

Route::middleware('auth:sanctum')->group(function () {
    Route::get('/user', function (Request $request) {
        return $request->user();
    });

    // Cursos
    Route::apiResource('cursos', CursoController::class);
    // Disciplinas
    Route::apiResource('disciplinas', DisciplinaController::class);

    // ---- Cliente (SPA Svelte) ----
    Route::get('/conta', [ContaController::class, 'show']);                 // saldo + investimentos
    Route::get('/extrato', [ExtratoController::class, 'index']);            // ?data_inicial=&data_final=

    Route::post('/pix', [PixController::class, 'store']);
    Route::get('/pix/{pix}', [PixController::class, 'show']);

    Route::get('/investimentos', [InvestimentoController::class, 'index']);
    Route::get('/investimentos/{investimento}', [InvestimentoController::class, 'show']);
    Route::post('/investimentos/{investimento}/aplicar', [InvestimentoController::class, 'aplicar']);
    Route::post('/investimentos/{investimento}/resgatar', [InvestimentoController::class, 'resgatar']);

    Route::get('/movimentacoes/{movimentacao}', [MovimentacaoInvestimentoController::class, 'show']);
});