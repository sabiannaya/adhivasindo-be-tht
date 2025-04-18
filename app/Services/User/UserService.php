<?php

namespace App\Services\User;

use App\Models\User;
use Illuminate\Http\Request;
use App\Http\Requests\User\LoginRequest;
use App\Http\Requests\User\RegisterRequest;
use App\Services\BaseService;
use Illuminate\Support\Facades\DB;
use Exception;

class UserService extends BaseService
{
    protected $user;
    public function __construct(User $user)
    {
        Parent::__construct();
        $this->user = $user;
    }

    public function register(RegisterRequest $request)
    {
        try {
            $data = $request->validated();

            DB::beginTransaction();
            $user = $this->user->create($data);
            DB::commit();
            
            http_response_code(201); // 201 CREATED
            return $this->sendSuccess($user, __('messages.messages.create.success', ['text' => 'User']));
        } catch (Exception $e) {
            DB::rollBack();

            http_response_code(500); // 500 INTERNAL SERVER ERROR
            $message = $this->onDebug ? $e->getMessage() : __('messages.messages.create.error', ['text' => 'User']);
            return $this->SendError([], $message);
        }
    }
    
    public function update(Request $request)
    {
        try {
            $data = $request->validate([
                'username' => 'required|string|min:8|alpha_dash|unique:users,username,' . $request->user()->id,
                'email' => 'required|string|email|unique:users,email,' . $request->user()->id,
                'password' => 'required',
            ]);
            $user = auth('api')->user();

            DB::beginTransaction();
            $user->update($data);
            DB::commit();

            http_response_code(200); // 200 OK
            return $this->sendSuccess($user, __('messages.messages.update.success', ['text' => 'User']));
        } catch (Exception $e) {
            DB::rollBack();

            http_response_code(500); // 500 INTERNAL SERVER ERROR
            $message = $this->onDebug ? $e->getMessage() : __('messages.messages.update.error', ['text' => 'User']);
            return $this->SendError([], $message);
        }
    }

    public function login(LoginRequest $request)
    {
        try {
            $credentials = $request->validated();
            $token = auth('api')->attempt($credentials);

            /* Invalid credentials handler */
            if (!$token) {
                http_response_code(401); // 401 UNAUTHORIZED
                return $this->SendError([], __('auth.failed'));
            }

            $user = auth('api')->user();
            $data = [
                'user' => $user,
                'authorization' => [
                    'token' => $token,
                    'type' => 'bearer',
                    'expires_in' => auth('api')->factory()->getTTL() * 60 . ' minutes'
                ],
            ];

            http_response_code(200); // 200 OK
            return $this->sendSuccess($data, __('auth.login'));
        } catch (Exception $e) {
            DB::rollBack();

            http_response_code(500); // 500 INTERNAL SERVER ERROR
            $message = $this->onDebug ? $e->getMessage() : __('messages.messages.request.error');
            return $this->SendError([], $message);
        }
    }

    public function logout()
    {
        try {
            $user = auth('api')->user();
                auth('api')->logout();

            http_response_code(200); // 200 OK
            return $this->sendSuccess([], __('auth.logout'));
        } catch (Exception $e) {
            DB::rollBack();
            http_response_code(500); // 500 INTERNAL SERVER ERROR
            $message = $this->onDebug ? $e->getMessage() : __('messages.messages.request.error');
            return $this->SendError([], $message);
        }
    }

    public function me()
    {
        try {
            $user = auth('api')->user();

            $data = [
                'user' => $user,
            ];

            http_response_code(200); // 200 OK
            return $this->sendSuccess($data, __('messages.messages.request.success'));
        } catch (Exception $e) {
            DB::rollBack();

            http_response_code(500); // 500 INTERNAL SERVER ERROR
            $message = $this->onDebug ? $e->getMessage() : __('messages.messages.request.error');
            return $this->SendError([], $message);
        }
    }

    public function refresh()
    {
        try {
            $user = auth('api')->user();
            $token = auth('api')->refresh();

            $data = [
                'user' => $user,
                'authorization' => [
                    'token' => $token,
                    'type' => 'bearer',
                    'expires_in' => auth('api')->factory()->getTTL() . ' minutes'
                ],
            ];

            http_response_code(200); // 200 OK
            return $this->sendSuccess($data, __('messages.messages.request.success'));
        } catch (Exception $e) {
            DB::rollBack();

            http_response_code(500); // 500 INTERNAL SERVER ERROR
            $message = $this->onDebug ? $e->getMessage() : __('messages.messages.request.error');
            return $this->SendError([], $message);
        }
    }
}
