<?php
declare(strict_types=1);

namespace App\Http\Controllers;

use App\TruckersMP\API\APIClient as TMPClient;
use App\TruckersMP\Exceptions\APIErrorException;
use App\TruckersMP\Types\Ban;
use App\TruckersMP\Types\Bans;
use App\TruckersMP\Types\Player;
use Cache;
use Exception;
use GuzzleHttp\Client;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use RuntimeException;
use Throwable;

/**
 * Class SearchController
 *
 * @package App\Http\Controllers
 */
class SearchController extends Controller
{

    public $truckersmpClient;
    public $steamClient;

    /**
     * SearchController constructor.
     */
    public function __construct()
    {
        $this->truckersmpClient = new TMPClient();
        $this->steamClient = new Client([
            'base_uri' => 'https://api.steampowered.com/ISteamUser/',
        ]);
    }

    /**
     * @param Request $request
     *
     * @return JsonResponse
     */
    public function search(Request $request): JsonResponse
    {
        $client = $this->truckersmpClient;

        $needle = $request->input('needle');
        if (empty($needle)) {
            $data = [
                'error' => true,
                'response' => [
                    'type' => 'empty_form'
                ]
            ];

            return response()->json($data);
        }

        $mode = $request->input('mode');
        if (empty($mode)) {
            $mode = 'auto';
        }

        try {
            $desiredMode = 'auto';
            switch ($mode) {
                case 'steam_id':
                    $steam = $this->steam_check($needle);
                    $player = $this->truckersmp_check($client, $steam->steamid);
                    $desiredMode = 'steam_id';
                    break;

                case 'steam_url':
                    $steam = $this->steam_check($needle, true);
                    $player = $this->truckersmp_check($client, $steam->steamid);
                    $desiredMode = 'steam_url';
                    break;

                case 'truckersmp_id':
                    $player = $this->truckersmp_check($client, $needle);
                    $steam = $this->steam_check($player->steamID64);
                    $desiredMode = 'truckersmp_id';
                    break;

                default:
                    if (filter_var($needle, FILTER_VALIDATE_INT)) {
                        if (starts_with($needle, '765611') && strlen($needle) === 17) {
                            // Steam ID
                            $steam = $this->steam_check($needle);
                            $player = $this->truckersmp_check($client, $steam->steamid);
                        } else {
                            // TruckersMP ID
                            $player = $this->truckersmp_check($client, $needle);
                            $steam = $this->steam_check($player->steamID64);
                        }
                    } else {
                        // custom URLs
                        $steam = $this->steam_check($needle, true);
                        $player = $this->truckersmp_check($client, $steam->steamid);
                    }
                    break;
            }
        } catch (Throwable $e) {
            $data = [
                'error' => true,
                'response' => [
                    'type' => 'user_not_found',
                    'errorCode' => $e->getCode(),
                    'needle' => $e->getMessage()
                ]
            ];

            return response()->json($data);
        }

        // dump($steam);
        // dump($player);

        if (app()->isLocal()) {
            Cache::forget('users:' . $player->id . ':bans');
        }

        $bans = Cache::remember('users:' . $player->id . ':bans', now()->addMinutes(10), static function () use ($player, $client) {
            try {
                return $client->bans($player->id)->bans;
            } catch (APIErrorException $e) {
                return null;
            }
        });

        $map = $this->map($player->id);
        $discord = $this->discord($player->id);

        /** @var Player $player */
        /** @var Bans|Ban|null $bans */
        $data = [
            'error' => false,
            'response' => [
                'id' => $player->id ?? null,
                'name' => $player->name ?? null,
                'avatar' => $player->avatar ?? null,
                'groupName' => $player->groupName ?? null,
                'groupColor' => config('groups.' . $player->groupID) ?? 'black',
                'joinDate' => isset($player->joinDate) ? date('d.m.Y', strtotime($player->joinDate)) : null,
                'banned' => $player->banned ?? null,
                'bannedUntil' => isset($player->bannedUntil) ? date('d.m.Y H:i:s', strtotime($player->bannedUntil)) : null,
                'displayBans' => $player->displayBans ?? null,
                'bans' => $bans ?? null,
                'vtc' => $player->vtc ?? null,
                'map' => $map ?? null,
                'discord' => $discord ?? null,
                'steam' => [
                    'id' => $steam->steamid ?? null,
                    'locCountryCode' => isset($steam->loccountrycode) ? strtolower($steam->loccountrycode) : null,
                    'timeCreated' => isset($steam->timecreated) ? date('d.m.Y', $steam->timecreated) : null,
                    'profileUrl' => isset($steam->steamid) ? ('https://steamcommunity.com/profiles/' . $steam->steamid) : null
                ],
                'desiredMode' => $desiredMode
            ]
        ];

        return response()->json($data);
    }

    /**
     * @param      $needle
     * @param bool $resolve
     *
     * @return mixed
     * @throws Exception
     */
    private function steam_check($needle, $resolve = false)
    {
        $oldNeedle = $needle;
        if ($resolve) {
            if (app()->isLocal()) {
                Cache::forget('users:steamNeedle:' . $needle);
            }

            $needle = Cache::remember('users:steamNeedle:' . $needle, now()->addMinutes(10), function () use ($needle) {
                try {
                    $response = (string)$this->steamClient->request('GET', 'ResolveVanityURL/v1/', [
                        'query' => [
                            'key' => config('steam-api.steamApiKey'),
                            'vanityurl' => $needle,
                            'url_type' => 1
                        ]
                    ])->getBody();

                    $response = json_decode($response, false, 512, JSON_THROW_ON_ERROR)->response;

                    if ($response->success === 42) {
                        return null;
                    }

                    return $response->steamid;
                } catch (Throwable $e) {
                    return null;
                }
            });
        }

        if (app()->isLocal()) {
            Cache::forget('users:' . $oldNeedle . ':steam');
        }

        if ($needle === null) {
            $this->not_found('148801', $oldNeedle);
        }

        return Cache::remember('users:' . $needle . ':steam', now()->addMinutes(10), function () use ($needle) {
            $info = null;
            try {
                $response = (string)$this->steamClient->request('GET', 'GetPlayerSummaries/v2/', [
                    'query' => [
                        'key' => config('steam-api.steamApiKey'),
                        'steamids' => $needle
                    ]
                ])->getBody();

                $info = json_decode($response, false, 512, JSON_THROW_ON_ERROR)->response->players;

                if (!count($info)) {
                    $this->not_found('148802', $needle);
                }

            } catch (Throwable $e) {
                $this->not_found('148802', $needle);
            }

            return $info[0];
        });
    }

    /**
     * @param int $code
     * @param string $message
     *
     * @throws RuntimeException
     */
    private function not_found($code = 404, $message = 'User Not Found By this needle'): void
    {
        throw new RuntimeException($message, $code);
    }

    /**
     * @param $client
     * @param $needle
     *
     * @return mixed
     * @throws RuntimeException
     * @throws Exception
     */
    private function truckersmp_check($client, $needle)
    {
        /** @var Player $tmp */
        /** @var TMPClient $client */
        try {
            if (app()->isLocal()) {
                Cache::forget('users:' . $needle . ':player');
            }
            $tmp = Cache::remember('users:' . $needle . ':player', now()->addMinutes(10), static function () use ($needle, $client) {
                $player = $client->player($needle);

                if (str_contains($player->avatar, 'avatars/defaultavatar.png')) {
                    $player->avatar = asset('/images/defaultavatar.png');
                }

                return $player;
            });
        } catch (Throwable $e) {
            $this->not_found('148810', $needle);
        }

        return $tmp;
    }

    /**
     * @param mixed $needle
     *
     * @return mixed
     */
    private function map($needle)
    {
        $url = 'http://tracker.ets2map.com/v3/doko/' . $needle;

        if (app()->isLocal()) {
            Cache::forget('users:' . $needle . ':map');
        }

        return Cache::remember('users:' . $needle . ':map', now()->addMinute(), static function () use ($url, $needle) {
            try {
                $data = collect(json_decode(file_get_contents($url), false, 512, JSON_THROW_ON_ERROR)->Data);
                $map = $data->where('MpId', $needle)->first();

                if ($data) {
                    $servers = cache('info:servers');

                    $map_servers = config('truckersmp.map_servers');

                    if ($map) {
                        $server = $servers->where('id', $map_servers[$map->ServerId])->first();
                        $map->ServerName = "{$server->game} - {$server->name}";
                    } else {
                        $map = false;
                    }
                }

                return $map;
            } catch (Exception $e) {
                return null;
            }
        });
    }

    /**
     * @param mixed $needle
     *
     * @return mixed
     */
    private function discord($needle)
    {
        $url = 'https://truckersmp.com/api/user/discord/' . $needle;

        if (app()->isLocal()) {
            Cache::forget('users:' . $needle . ':discord');
        }

        return Cache::remember('users:' . $needle . ':discord', now()->addDay(), static function () use ($url) {
            try {
                $data = json_decode(file_get_contents($url), false, 512, JSON_THROW_ON_ERROR);

                if ($data->name === '') {
                    return null;
                }

                return $data;
            } catch (Exception $e) {
                return null;
            }
        });
    }

}
