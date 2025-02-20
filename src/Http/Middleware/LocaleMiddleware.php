<?php

declare(strict_types=1);

namespace IlyaSapunkov\Translatable\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class LocaleMiddleware
{
    /**
     * @param Request $request
     * @param Closure $next
     *
     * @return Response
     */
    public function handle(Request $request, Closure $next): Response
    {
        $locale = $request->input('locale', $request->cookie('locale', config('app.locale')));

        app()->setLocale($locale);

        return $next($request);
    }
}
