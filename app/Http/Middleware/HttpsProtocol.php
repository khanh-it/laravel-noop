<?php

namespace App\Http\Middleware;

use Closure;

class HttpsProtocol
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle($request, Closure $next)
    {
        // Force helpers functions like url(), asset() return https.
        \Illuminate\Support\Facades\URL::forceScheme('https');

        // Redirect http --> https?!
        // $appEnv = config('app.env');
        // if ($appEnv === 'production' || $appEnv === 'staging' || $appEnv === 'develop') {
			if (!$request->isSecure()/* case: direct server */
				&& (($_SERVER["HTTP_X_FORWARDED_PROTO"] ?? null) != 'https')
			) {
				return redirect()->secure($request->getRequestUri(), 301);
			}
        // }

        return $next($request);
    }
}
