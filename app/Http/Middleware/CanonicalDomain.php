<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class CanonicalDomain
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function handle(Request $request, Closure $next): Response
    {
        $appUrl = config('app.url');

        if (empty($appUrl)) {
            return $next($request);
        }

        $parsedAppUrl = parse_url($appUrl);
        $canonicalHost = $parsedAppUrl['host'] ?? null;

        if (!$canonicalHost) {
            return $next($request);
        }

        $currentHost = $request->getHost();

        // If the current host is not the canonical host, redirect
        if ($currentHost !== $canonicalHost) {
            // Build the canonical URL
            $scheme = $parsedAppUrl['scheme'] ?? 'https';
            $port = isset($parsedAppUrl['port']) ? ':' . $parsedAppUrl['port'] : '';
            $path = $request->getRequestUri();

            return redirect()->to($scheme . '://' . $canonicalHost . $port . $path, 301);
        }

        return $next($request);
    }
}
