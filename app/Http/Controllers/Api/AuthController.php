<?php

namespace App\Http\Controllers\Api;

use App\Enums\NomeRole;
use App\Http\Controllers\Controller;
use App\Models\User;
use App\Services\PermissionService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\ValidationException;

class AuthController extends Controller
{
    public function __construct(
        protected PermissionService $permissionService
    ) {
    }

    public function login(Request $request): JsonResponse
    {
        $dados = $request->validate([
            'email' => ['required', 'string', 'email'],
            'password' => ['required', 'string'],
            'device_name' => ['required', 'string', 'max:100'], // nome do dispositivo (identifica o token)
        ]);

        if (! Auth::validate(['email' => $dados['email'], 'password' => $dados['password']])) {
            throw ValidationException::withMessages([
                'email' => ['As credenciais fornecidas estão incorretas.'],
            ]);
        }

        $user = User::with('role')->where('email', $dados['email'])->firstOrFail();

        if (! $user->temRole(NomeRole::CLIENTE)) {
            return response()->json([
                'message' => 'Este acesso é exclusivo para clientes. Gerentes devem usar o sistema web.',
            ], 403);
        }

        $user->tokens()->where('name', $dados['device_name'])->delete();
        $token = $user->createToken($dados['device_name'])->plainTextToken;

        return response()->json([
            'token' => $token,
            'user' => [
                'id' => $user->id,
                'name' => $user->name,
                'email' => $user->email,
                'role' => $user->role?->name,
            ],
            'permissions' => $this->permissionService->getPermissions($user->role_id),
        ]);
    }

    public function logout(Request $request): JsonResponse
    {
        $request->user()->currentAccessToken()->delete();

        return response()->json(['message' => 'Token revogado com sucesso.']);
    }
}