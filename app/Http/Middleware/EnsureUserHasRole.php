<?php

namespace App\Http\Middleware;

use App\Enums\Role;
use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

/**
 * Bloqueia a rota para quem não tem um dos perfis informados.
 *
 * Uso: Route::middleware('role:admin,medico')
 */
class EnsureUserHasRole
{
    public function handle(Request $request, Closure $next, string ...$roles): Response
    {
        $allowed = array_map(fn (string $role) => Role::from($role), $roles);

        if (! $request->user()?->hasRole(...$allowed)) {
            abort(Response::HTTP_FORBIDDEN, 'Você não tem permissão para acessar esta página.');
        }

        return $next($request);
    }
}
