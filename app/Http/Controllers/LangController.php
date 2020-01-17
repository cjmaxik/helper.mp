<?php
declare(strict_types=1);

namespace App\Http\Controllers;

use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;

/**
 * Class LangController
 *
 * @package App\Http\Controllers
 */
class LangController extends Controller
{
    /**
     * @param $locale
     *
     * @return RedirectResponse
     */
    public function change($locale): RedirectResponse
    {
        $lang = array_key_exists($locale, config('locales.prod') + config('locales.dev'));

        if ($lang) {
            session(['locale' => $locale]);
        } else {
            session(['locale' => 'en']);
        }

        return redirect()->route('index');
    }

    /**
     * @param Request $request
     *
     * @return string
     */
    public function checkLocale(Request $request): string
    {
        return strtolower($request->server('HTTP_CF_IPCOUNTRY'));
    }
}
