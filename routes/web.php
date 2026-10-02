<?php

use App\Enums\NomeRole;
use App\Http\Controllers\ProfileController;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return redirect()->route('dashboard');
});

Route::get('/dashboard', function () {
    $user = auth()->user();

    if ($user->temRole(NomeRole::GERENTE_GERAL)) {
        return redirect()->route('users.index');
    }

    if ($user->temRole(NomeRole::GERENTE_CONTA)) {
        return redirect()->route('conta.index');
    }

    return view('dashboard');
})->middleware(['auth', 'verified'])->name('dashboard');

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
});

require __DIR__.'/app.php';
require __DIR__.'/auth.php';