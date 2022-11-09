<?php

namespace App\Http\Middleware;

use App\Http\Resources\User\UserResource;
use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

/**
 * Добавляем данные авторизованного пользователя в ответ на запрос
 */
class ResponseAuthData
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure(\Illuminate\Http\Request): (\Illuminate\Http\Response|\Illuminate\Http\RedirectResponse)  $next
     * @return \Illuminate\Http\Response|\Illuminate\Http\RedirectResponse
     */
    public function handle(Request $request, Closure $next)
    {
        $response = $next($request);

        if($response instanceof \Illuminate\Http\JsonResponse && !$request->route()->named('auth.refresh')){
            $current_data = $response->getData();
            $current_data->auth_data = Auth::check() ? new UserResource(Auth::user()) : null;
            $response->setData($current_data);
        }

        return $response;
    }
}
