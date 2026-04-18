<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpFoundation\Response;

// Blocks non-admin users from admin API routes and similar protected areas.
class EnsureUserIsAdmin
{
    // Allow request only when a logged-in user has is_admin = true.
    public function handle(Request $request, Closure $next): Response
    {
        $user = Auth::user();
        if ($user === null || ! $user->is_admin) {
            abort(403, 'Prístup len pre administrátora.');
        }

        return $next($request);
    }
}
