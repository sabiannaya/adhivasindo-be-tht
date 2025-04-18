<?php

namespace App\Http\Controllers\Api\User;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Http\Requests\User\RegisterRequest;
use App\Http\Requests\User\LoginRequest;
use App\Services\User\UserService;

class UserController extends Controller
{
    protected $userService;
    public function __construct(UserService $userService)
    {
        $this->userService = $userService;
    }

    public function register(RegisterRequest $request)
    {
        $serviceResponse = $this->userService->register($request);

        return $serviceResponse;
    }

    public function update(Request $request)
    {
        $serviceResponse = $this->userService->update($request);

        return $serviceResponse;
    }

    public function login(LoginRequest $request)
    {
        $serviceResponse = $this->userService->login($request);

        return $serviceResponse;
    }

    public function logout()
    {
        $serviceResponse = $this->userService->logout();

        return $serviceResponse;
    }

    public function refresh()
    {
        $serviceResponse = $this->userService->refresh();

        return $serviceResponse;
    }

    public function me()
    {
        $serviceResponse = $this->userService->me();

        return $serviceResponse;
    }
}
