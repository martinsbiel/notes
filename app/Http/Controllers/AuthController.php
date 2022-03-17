<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Interfaces\AuthRepositoryInterface;

class AuthController extends Controller
{
    private AuthRepositoryInterface $authRepository;

    public function __construct(AuthRepositoryInterface $authRepository)
    {
        $this->authRepository = $authRepository;
    }

    public function register(Request $request){
        $credentials = $request->all(['name', 'email', 'password']);

        return response()->json($this->authRepository->register($credentials), 201);
    }

    public function login(Request $request){
        $credentials = $request->all(['email', 'password']);

        try {
            return response()->json(['token' => $this->authRepository->login($credentials)]);
        } catch(\Exception $e){
            return response()->json(['error' => $e->getMessage()], 403);
        }
    }

    public function logout(){
        $this->authRepository->logout();

        return response()->json(['msg' => 'Logout foi realizado com sucesso']);
    }

    public function refresh(){
        return response()->json(['token' => $this->authRepository->refresh()]);
    }

    public function me(){
        return response()->json($this->authRepository->me());
    }
}
