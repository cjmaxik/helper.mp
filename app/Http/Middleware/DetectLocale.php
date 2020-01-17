<?php
declare(strict_types=1);

namespace App\Http\Middleware;

use App;
use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Arr;
use Session;

/**
 * Class DetectLocale
 *
 * @package App\Http\Middleware
 */
class DetectLocale
{
    /**
     * Handle an incoming request.
     *
     * @param Request $request
     * @param Closure $next
     *
     * @return mixed
     */
    public function handle($request, Closure $next)
    {
        if (Session::has('locale')) {
            $locale = Session::get('locale', config('langs.locale'));
        } else {
            $locale = strtolower(substr($request->server('HTTP_ACCEPT_LANGUAGE') ?? $request->server('HTTP_CF_IPCOUNTRY') ?? 'en-US,en;q=0.5', 0, 2));

            if (!Arr::exists(config('locales.prod') + config('locales.dev'), $locale)) {
                $locale = 'en';
            }

            Session::flash('auto_lang');
        }

        App::setLocale($locale);
        Session::put('locale', $locale);

        return $next($request);
    }
}
