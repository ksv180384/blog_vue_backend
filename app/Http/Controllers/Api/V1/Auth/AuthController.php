<?php

namespace App\Http\Controllers\Api\V1\Auth;

use App\Http\Controllers\Api\V1\BaseController;
use App\Http\Requests\Auth\LoginRequest;
use App\Http\Requests\Auth\RegistrationRequest;
use App\Http\Resources\User\UserResource;
use App\Models\User\User;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\ValidationException;
use Tymon\JWTAuth\Exceptions\JWTException;
use Tymon\JWTAuth\Facades\JWTAuth;

class AuthController extends BaseController
{
    public function __construct()
    {
        parent::__construct();
        $this->middleware('auth')->only(['logout']);
    }

    public function login(LoginRequest $request)
    {
        if ($token = JWTAuth::attempt($request->validated())) {
            return $this->userResource($token);
        }

        throw ValidationException::withMessages(['password' => ['Неверный логин или пароль.']]);
    }

    public function registration(RegistrationRequest $request)
    {
        User::create(array_merge(
            $request->validated(),
            ['password' => Hash::make($request->password)]
        ));

        $credentials = $request->only(['email', 'password']);
        if ($token = JWTAuth::attempt($credentials)) {
            return $this->userResource($token);
        }

        throw ValidationException::withMessages(['password' => ['Ошибка.']]);
    }

    /**
     * Refresh a token.
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function refresh()
    {
        try {
            return $this->respondWithToken(auth()->refresh());
        }catch (JWTException $exception){
            return response()->json([
                //'message' => $exception->getMessage(),
                'message' => 'Error token refresh.',
            ], 401);
        }
    }

    /**
     * Get the token array structure.
     *
     * @param  string $token
     *
     * @return \Illuminate\Http\JsonResponse
     */
    protected function respondWithToken($token)
    {
        return response()->json([
            'access_token' => $token,
            'token_type' => 'bearer',
            'expires_in' => (auth()->factory()->getTTL() * 60),
        ]);
    }

    /**
     * Log the user out (Invalidate the token).
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function logout()
    {
        auth()->logout();

        return response()->json(['message' => 'Успешно вышел из системы.']);
    }


    protected function userResource(string $jwtToken)
    {
        return [
            'user' => new UserResource(Auth::user()),
            'token' => $jwtToken,
        ];
    }
}
