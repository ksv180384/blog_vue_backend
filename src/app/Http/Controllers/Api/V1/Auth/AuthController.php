<?php

namespace App\Http\Controllers\Api\V1\Auth;

use App\Http\Controllers\Controller;
use App\Http\Requests\Auth\LoginRequest;
use App\Http\Requests\Auth\RegistrationRequest;
use App\Http\Resources\User\UserResource;
use App\Models\User\User;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\ValidationException;

class AuthController extends Controller
{
    public function login(LoginRequest $request)
    {
        if ($token = Auth::attempt($request->validated())) {
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
        if ($token = Auth::attempt($credentials)) {
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
        return $this->respondWithToken(auth()->refresh());
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
            'expires_in' => auth()->factory()->getTTL() * 60
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

        return response()->json(['message' => 'Successfully logged out']);
    }


    protected function userResource(string $jwtToken)
    {
        return [
            'user' => new UserResource(Auth::user()),
            'token' => $jwtToken,
        ];
    }
}
