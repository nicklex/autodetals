<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpFoundation\Response;

class AdminMiddleware
{
    public function handle(Request $request, Closure $next): Response
    {
        // Проверка авторизации
        if (!Auth::check()) {
            return redirect()->route('login');
        }

        // Проверка роли администратора
        $user = Auth::user();
        
        // Вариант 1: если есть поле is_admin (рекомендуется)
        if (isset($user->admin) && $user->admin) {
            return $next($request);
        }
        
        
        // Если не администратор
        abort(403, 'Доступ запрещен. Требуются права администратора.');
    }
}