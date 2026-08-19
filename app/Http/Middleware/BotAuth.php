<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;

class BotAuth
{
    public function handle(Request $request, Closure $next)
    {
        $key = $request->header('X-Bot-Key');

        if (!$key || $key !== config('services.bot.key')) {
            return response()->json(['message' => 'Unauthorized'], 401);
        }

        return $next($request);
    }
}