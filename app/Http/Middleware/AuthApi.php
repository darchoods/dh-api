<?php

namespace App\Http\Middleware;

use App\Models\V1\ApiKey;
use App\Models\V1\PortalSiteInfo;
use App\Models\V1\User;
use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Arr;
use JWTAuth as TYJWT;
use Symfony\Component\HttpKernel\Exception\UnauthorizedHttpException;

class AuthApi
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @param  string|null  $guard
     * @return mixed
     */
    public function handle(Request $request, Closure $next, $guard = null)
    {
        $token = false;
        if ($request->header('X-Auth-Token', false) !== false) {
            $token = $request->header('X-Auth-Token');
        }
        if ($request->get('auth_token', false) !== false) {
            $token = $request->get('auth_token');
        }
        if ($token === false) {
            return response()->json([
                'status_code' => 403,
                'message' => 'This API requires authentication.',
            ], 403);
        }

        // check if the token is in the db
        $token = ApiKey::where('key', $token)->first();
        // error if not
        if ($token === null) {
            return response()->json([
                'status_code' => 403,
                'message' => 'This API requires authentication.',
            ], 403);
        }
        // grab the user associated with it
        $user = User::where('id', $token->user_id)->first();
        if ($user === null) {
            return response()->json([
                'status_code' => 403,
                'message' => 'This API requires authentication.',
            ], 403);
        }
        // and set the user
        auth('api')->setUser($user);

        return $next($request);
    }
}
