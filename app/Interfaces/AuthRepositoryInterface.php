<?php

namespace App\Interfaces;

interface AuthRepositoryInterface 
{
    public function register(array $user);
    public function login(array $user);
    public function logout();
    public function refresh();
    public function me();
}