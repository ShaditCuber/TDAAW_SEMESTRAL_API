<?php

namespace App\Http\Controllers;

use App\Http\Requests\LoginRequest;
use App\Http\Requests\SignUpRequest;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Tymon\JWTAuth\Facades\JWTAuth;

class AuthController extends Controller
{
    //
    public function signUp(SignUpRequest $request)
    {
        User::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => bcrypt($request->password)
        ]);
        return response()->json([
            'message' => 'Usuario registrado!'
        ], 201);
    }
    
   
   public function login(Request $request)
    {
        $credentials = $request->only('email', 'password');
        try {
            if (! $token = JWTAuth::attempt($credentials)) {
                return response()->json(['error' => 'Credenciales Incorrectas'], 200);
            }
        } catch (JWTException $e) {
            return response()->json(['error' => 'could_not_create_token'], 500);
        }
        return response()->json(compact('token'));
    }
  
    /**
     * Cierre de sesión (anular el token)
     */
    public function logout(Request $request)
    {
        if (Auth::check()) {
        $request->user()->token()->revoke();
        return response()->json(['message' => 'sesion finalizada']);
    } else {
        return response()->json(['error' => 'Usuario no autenticado'], 401);
    }
    }
  
    /**
     * Obtener el objeto User como json
     */
    public function user(Request $request)
    {   
        $user = $request->user()->only(['name', 'email', 'perro_id']);

        return response()->json($user);
    }

    public function actualizarUsuario(Request $request)
    {
        $user = $request->user();
        // $user->name = $request->name;
        $user->perro_id = $request->perro_id;
        $user->save();
        return response()->json([
            'message' => 'Usuario actualizado!'
        ], 201);
    }

}