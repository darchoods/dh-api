<?php

namespace App\Http\Middleware;

use App\Models\V1\PortalSiteInfo;
use App\Models\V1\User;
use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Arr;
use JWTAuth as TYJWT;
use Symfony\Component\HttpKernel\Exception\UnauthorizedHttpException;

class AuthDarchoods
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
        if ($token !== false) {
            return $next($request);
        }


        if (!($token = auth('api')->attempt($creds))) {
            throw new UnauthorizedHttpException('Basic');
        }
        $user = auth('api')->user();

        // make sure the user can access core
        if (!$user->core_access) {
            return response()->json([
                'status_code' => 403,
                'message' => 'User doesnt have correct privileges to access Core.',
            ], 403);
        }

        // check if users site has access to core
        $siteInfo = app(PortalSiteInfo::class);
        if (!in_array('CORE', Arr::get($siteInfo->getProducts($user->site_code()), 'products', []))) {
            return response()->json([
                'status_code' => 403,
                'message' => 'Site doesnt have Core enabled.',
            ], 403);
        }

        auth('api')->setUser($user);

        return $next($request);
    }
}
