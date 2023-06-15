<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use App\Models\AccessToken;
class AccessTokenMiddleware
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        $authorization = $request->header('Authorization');
        $accessToken = AccessToken::where('access_token', $authorization)->first();
        if (!$accessToken) {
            return response()->json([ 'message' => 'Unauthorized' ], Response::HTTP_UNAUTHORIZED); 
        }
        return $next($request);
    }
}
