<?php
declare(strict_types=1);

namespace App\Console\Commands;

use App\TruckersMP\API\APIClient;
use Cache;
use Exception;
use GuzzleHttp\Client;
use Http\Client\Exception as HttplugException;
use Illuminate\Console\Command;
use Illuminate\Support\Collection;
use stdClass;

/**
 * Class GrabInfo
 *
 * @package App\Console\Commands
 */
class GrabInfo extends Command
{
    public $tmpClient;
    public $httpClient;

    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'grab:info';
    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'HELP: Grab info from TruckersMP API';

    /**
     * Create a new command instance.
     */
    public function __construct()
    {
        parent::__construct();

        $this->tmpClient = new APIClient();
        $this->httpClient = new Client([
            'timeout' => 10
        ]);
    }

    /**
     * Execute the console command.
     *
     * @return mixed
     * @throws Exception
     * @throws HttplugException
     */
    public function handle()
    {
        $is_quiet = $this->option('quiet');

        // Grab MP version and last news from launcher...
        $this->info('Grab MP version and last news from launcher...');
        $mp = $this->grabMpVersion();
        if (!$is_quiet) {
            dump($mp);
        }
        Cache::forever('info:mp', $mp);

        // Grab MP Servers info...
        $this->info('Grab MP Servers info...');
        $servers_data = $this->grabMpServers();
        if (is_array($servers_data)) {
            if (!$is_quiet) {
                dump($servers_data['servers']);
                dump($servers_data['servers_stats']);
            }
            Cache::forever('info:servers', collect($servers_data['servers']));
            Cache::forever('info:servers_stats', collect($servers_data['servers_stats']));
        } else {
            $this->error('Something wrong...');
            if (!$is_quiet) {
                dump($servers_data);
            }
        }

        // Grab MP gametime...
        $this->info('Grab MP gametime...');
        $time = $this->grabGameTime();
        if (!$is_quiet) {
            dump($time);
        }
        Cache::forever('info:time', $time);

        $processing_datetime = time();
        Cache::forever('info:processing_datetime', $processing_datetime);
        $this->info('Grab processing datetime - ' . $processing_datetime);
    }

    /**
     * @return stdClass|Collection
     * @throws HttplugException
     */
    private function grabMpVersion()
    {
        try {
            $launcher = json_decode($this->httpClient->get(config('truckersmp.launcher_url'))->getBody()->getContents(), false, 512, JSON_THROW_ON_ERROR);

            $mp = new stdClass;
            $mp->version = trim($launcher->CurrentVersion);
            $mp->ets2 = trim($launcher->SupportedEts2);
            $mp->ats = trim($launcher->SupportedAts);
            $mp->launcher = trim($launcher->UpdaterVersion);

            $mp->registered = $this->tmpClient->players()->players;

            $this->comment('New info out there.');
        } catch (Exception $e) {
            $this->error('update.ets2mp.com isn\'t available, working with TruckersMP API...');
            $mp = new stdClass;
            $mp->error = 'true';
        }

        if (isset($mp->error)) {
            try {
                $mp = $this->tmpClient->version();

                $this->comment('New info out there.');
            } catch (Exception $e) {
                $mp = new stdClass;
                $mp->error = 'true';
            }
        }

        return $mp;
    }

    /**
     * @return array|bool
     * @throws Exception
     */
    private function grabMpServers()
    {
        try {
            $servers = collect($this->tmpClient->servers());
            $this->comment('New info out there.');
        } catch (HttplugException $e) {
            return false;
        }

        $servers_stats = [
            'overall' => [
                'players_count' => $servers->sum('players'),
                'online_count' => $servers->where('online', true)->count()
            ],
            'ETS2' => [
                'empty' => $servers->where('game', 'ETS2')->where('players', 0)->count(),
                'offline' => $servers->where('game', 'ETS2')->where('online', false)->count(),
                'count' => $servers->where('game', 'ETS2')->where('online', true)->where('players', '>', 0)->count()
            ],
            'ATS' => [
                'empty' => $servers->where('game', 'ATS')->where('players', 0)->count(),
                'offline' => $servers->where('game', 'ATS')->where('online', false)->count(),
                'count' => $servers->where('game', 'ATS')->where('online', true)->where('players', '>', 0)->count()
            ]
        ];

        return compact('servers', 'servers_stats');
    }

    /**
     * @return array|int
     * @throws HttplugException
     */
    private function grabGameTime()
    {
        $time = [];
        try {
            $result = $this->tmpClient->gameTime()->timeRaw;
            $time['game'] = date('H:i', mktime(0, $result));
            $time['update'] = time();
            $this->comment('New info out there.');
        } catch (Exception $e) {
            $time = 0;
        }

        return $time;
    }
}
