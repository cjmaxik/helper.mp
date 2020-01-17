<?php /** @noinspection ALL */

namespace App\Http\Controllers;

use GuzzleHttp\Client;
use GuzzleHttp\Exception\GuzzleException;
use Illuminate\Http\Request;

class LocalTestController extends Controller
{
    public $steamClient;

    /**
     * SearchController constructor.
     */
    public function __construct()
    {
        $this->steamClient = new Client([
            'base_uri' => 'https://api.steampowered.com/ISteamUser/',
        ]);
    }

    public function steam(Request $request): void
    {
        $needle = $request->get('needle');

        try {
            $response = json_decode($this->steamClient->request('GET', 'ResolveVanityURL/v1/', [
                'query' => [
                    'key' => config('steam-api.steamApiKey'),
                    'vanityurl' => $needle,
                    'url_type' => 1
                ]
            ])->getBody(), false)->response;
        } catch (GuzzleException $e) {
        }

        if ($response->success === 42) {
            /** @noinspection ForgottenDebugOutputInspection */
            dd(null);
        }

        dd($response->steamid);
    }
}
