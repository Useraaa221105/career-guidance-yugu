<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class UserOnlyMiddleware
{
    public function handle(Request $request, Closure $next): Response
    {
        if ($request->user()?->isAdmin()) {
            return redirect()->route('admin.index')
                ->with('info', 'Диагностика предназначена для пользователей. Вы перенаправлены в панель администратора.');
        }

        return $next($request);
    }
}
