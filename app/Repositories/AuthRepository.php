<?php

namespace App\Repositories;

use App\Interfaces\AuthRepositoryInterface;
use App\Models\User;
use Illuminate\Support\Facades\Hash;

class AuthRepository implements AuthRepositoryInterface 
{
    // model injection
    public function __construct(User $user){
        $this->user = $user;
    }

    public function register(array $user){
        $user = $this->user->create([
            'name' => $user['name'],
            'email' => $user['email'],
            'password' => Hash::make($user['password'])
        ]);

        return $user;
    }

    public function login(array $user){
        // autenticação (email e senha)
        $token = auth('api')->attempt($user);
                
        if($token){ // usuario autenticado com sucesso
            return $token;
        } else { // erro de usuario ou senha
            throw new \Exception('Usuário ou senha inválido');

            // 401 = unauthorized -> não autorizado. é possivel que esteja logado mas nao autorizado
            // 403 = forbidden -> proibido (login inválido)
        }

        // retornar um Json Web Token
        return 'login';
    }

    public function logout(){
        return auth('api')->logout();
    }

    public function refresh(){
        $token = auth('api')->refresh(); // cliente encaminhe um jwt válido. só renovar se o cliente tiver autorização valida para fazer isso

        return $token;
    }

    public function me(){
        return auth()->user();
    }
}