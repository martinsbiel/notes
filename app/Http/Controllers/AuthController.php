<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class AuthController extends Controller
{
    public function login(Request $request){
        $credentials = $request->all(['email', 'password']);

        // autenticação (email e senha)
        $token = auth('api')->attempt($credentials);
        
        if($token){ // usuario autenticado com sucesso
            return response()->json(['token' => $token]);
        } else { // erro de usuario ou senha
            return response()->json(['error' => 'Usuário ou senha inválido'], 403);

            // 401 = unauthorized -> não autorizado. é possivel que esteja logado mas nao autorizado
            // 403 = forbidden -> proibido (login inválido)
        }

        // retornar um Json Web Token
        return 'login';
    }

    public function logout(){
        auth('api')->logout();

        return response()->json(['msg' => 'Logout foi realizado com sucesso']);
    }

    public function refresh(){
        $token = auth('api')->refresh(); // cliente encaminhe um jwt válido. só renovar se o cliente tiver autorização valida para fazer isso

        return response()->json(['token' => $token]);
    }

    public function me(){
        return response()->json(auth()->user());
    }
}
