<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Http\Requests\Auth\LoginRequest;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Auth;

class AuthenticatedSessionController extends Controller
{
    public function store(Request $request)
{
    $request->validate([
        'email' => 'required|email',
        'password' => 'required',
    ]);

    if (!Auth::attempt($request->only('email', 'password'))) {
        return response()->json(['message' => 'Credenciais inválidas'], 401);
    }

    $user = Auth::user();
    $token = $user->createToken('api-token',  ['*'], now()->addWeek())->plainTextToken;

    return response()->json([
        'status' => 'success',
         'message' => 'Usuario logado com sucesso',
         'data' => [
             'user' => $user,
             'token' => $token
         ]
    ]);
}
    public function destroy(Request $request)
{
    $user = $request->user();

    if (!$user) {
        return response()->json([
            'status' => 'error',
            'message' => 'Usuário não autenticado.',
            'data' => null
        ], 401);
    }

    $token = $user->currentAccessToken();

    if (!$token) {
        return response()->json([
            'status' => 'error',
            'message' => 'Token de acesso não encontrado ou já revogado.',
            'data' => null
        ], 400);
    }

    return response()->json([
        'status' => 'success',
        'message' => 'Logout realizado com sucesso',
        'data' => null
    ], 200);
}
}
