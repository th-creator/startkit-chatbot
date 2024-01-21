<?php

namespace App\Http\Middleware;

use Closure;
use App\Models\Role;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class isAdmin
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        if (auth('sanctum')->check()) {
            $user = auth('sanctum')->user();

            if ($user) {
                $role = Role::whereHas('users', function ($query) use ($user) {
                    $query->where('user_id', $user->id);
                })->where('name', 'admin')->first();

                if ($role) {
                    return $next($request);
                }
            }
        }

       return abort(403);
    }
}
