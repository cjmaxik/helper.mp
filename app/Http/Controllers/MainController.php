<?php
declare(strict_types=1);

namespace App\Http\Controllers;

use Exception;
use Illuminate\Contracts\View\Factory;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Arr;
use Illuminate\Support\Collection;
use Illuminate\View\View;
use Session;

/**
 * Class MainController
 *
 * @package App\Http\Controllers
 */
class MainController extends Controller
{
    /**
     * @return Factory|View
     * @throws Exception
     */
    public function index()
    {
        $mp = cache('info:mp');
        $servers = cache('info:servers');
        $servers_stats = cache('info:servers_stats');
        $time = cache('info:time');

        $news = cache('news:news');

        $wot = cache('wote:links');

        $status = cache('status:status');
        $text_status = cache('status:text_status');

        $darkMode = session('darkMode', 'dark') === 'dark';

        /** @var Collection $traffic */
        $traffic = cache('map:traffic');
//        $traffic = $traffic->sortByDesc('players');

        if ($mp === null || $traffic === null || $status === null) {
            abort(503, 'Oh shoot! There caches are the worst!');
        }

        if ($wot === null) {
            $wot = [
                [
                    'id' => 'CJMAXiK',
                    'nickname' => 'CJMAXiK',
                    'screen' => asset('/images/fallback_wot/image.jpg'),
                    'page' => 'https://helper.mp/',
                    'storage' => asset('/images/fallback_wot/image.jpg'),
                    'storage_blurred' => asset('/images/fallback_wot/image_blurred.jpg')
                ]
            ];
        }

        $processing_datetime = cache('info:processing_datetime');
        $wot = Arr::random($wot);

        $justmonika = Session::has('justmonika') ?? !random_int(0, 100);

        if ($justmonika) {
            $wot = [
                'id' => 'JUSTMONIKA',
                'nickname' => 'Just Monika',
                'screen' => asset('/images/ddlc/justmonika.jpg'),
                'page' => 'https://ddlc.moe/',
                'storage' => asset('/images/ddlc/justmonika.jpg'),
                'storage_blurred' => asset('/images/ddlc/justmonika.jpg')
            ];
        }

        $dismissTranslation = Session::has('dismissTranslation');

        $lang = config('locales.prod.' . app()->getLocale()) ?? config('locales.dev.' . app()->getLocale());

        return view('layout.app', compact(
            'mp', 'servers', 'servers_stats', 'news',
            'time', 'wot',
            'status', 'text_status', 'processing_datetime',
            'dismissTranslation', 'lang', 'darkMode', 'traffic'
        ));
    }

    /**
     * @return RedirectResponse
     */
    public function orderBy(): RedirectResponse
    {
        $orderBy = session('orderBy') ? false : true;
        session(['orderBy' => $orderBy]);

        return redirect()->route('index');
    }

    /**
     * @return RedirectResponse
     */
    public function redirectFromBigMP(): RedirectResponse
    {
        return redirect()->action('MainController@index')->with('bigmp', 'true');
    }

    /**
     * @return string
     */
    public function dismissTranslation(): string
    {
        session(['dismissTranslation' => true]);

        return 'OK';
    }

    /**
     * @param Request $request
     *
     * @return string
     */
    public function darkMode(Request $request): string
    {
        $darkMode = $request->session()->get('darkMode', 'dark');
        switch ($darkMode) {
            case 'dark':
                $request->session()->put('darkMode', 'white');
                break;

            case 'white':
                $request->session()->put('darkMode', 'dark');
                break;

            default:
                $request->session()->put('darkMode', 'dark');
        }

        return 'OK';
    }

    /**
     * @return RedirectResponse
     */
    public function justMonika(): RedirectResponse
    {
        return redirect()->action('MainController@index')->with('justmonika', 'true');
    }
}
