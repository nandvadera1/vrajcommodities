<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Http\RedirectResponse;

class XssSanitization
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure(\Illuminate\Http\Request): (\Illuminate\Http\Response|\Illuminate\Http\RedirectResponse)  $next
     * @return \Illuminate\Http\Response|\Illuminate\Http\RedirectResponse
     */
    public function handle(Request $request, Closure $next)
    {
        $input = $request->all();
        foreach ($input as $key => $value) {

            if ($key == 'message') {
                $value = preg_replace('/<script\b[^>]*>(.*?)<\/script>|<figure\b[^>]*>(.*?)<\/figure>/is', '', $value);
                unset($input[$key]);
            }
        }

        return $next($request);
    }
}
