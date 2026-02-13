<?php

namespace App\Http\Controllers;

use App\Http\Requests\LoginStoreRequest;
use App\Interfaces\AuthRepositoryInterface;
use Illuminate\Http\Request;

class AuthController extends Controller
{
    private AuthRepositoryInterface $authRepository;

    public function __construct(AuthRepositoryInterface $authRepository)
    {
        $this->authRepository = $authRepository;
    }

    function login(LoginStoreRequest $request)
    {
        $request=$request->validated();
        return $this->authRepository->login($request);
    }

    function logout(Request $request)
    {
        return $this->authRepository->logout();
    }

    function me(Request $request)
    {
        return $this->authRepository->me();
    }
}
