<?php
declare(strict_types=1);

namespace App\Console\Commands;

use Cache;
use Exception;
use GuzzleHttp\Client;
use Illuminate\Console\Command;
use LengthException;

/**
 * Class GrabMap
 *
 * @package App\Console\Commands
 */
class GrabMap extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'grab:map';
    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'HELP: Grab info from traffic.krashnz.com';

    private $client;

    /**
     * Create a new command instance.
     */
    public function __construct()
    {
        parent::__construct();

        $this->client = new Client([
            'timeout' => 10
        ]);
    }

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle()
    {
        $this->info('Grab traffic.krashnz.com...');

        $json = null;
        try {
            $data = json_decode($this->client->get(config('truckersmp.map_top'))->getBody()->getContents(), false, 512, JSON_THROW_ON_ERROR)->response->top;
            if (!isset($data)) {
                throw new LengthException("Traffic isn't populated");
            }
        } catch (Exception $e) {
            $this->comment('Issue');
            Cache::forever('map:traffic', null);

            return;
        }

        $top = [];
        foreach ($data as $server) {
            foreach ($server->traffic as $place) {
                $top[] = [
                    'server' => $server->short,
                    'name' => str_replace([' (City)', ' (Road)', ' (Intersection)'], '', $place->name),
                    'country' => $place->country,
                    'severity' => strtolower($place->severity),
                    'players' => $place->players
                ];
            }
        }
        $top = collect($top)->sortByDesc('players')->take(8);

        dump($top);
        Cache::forever('map:traffic', $top);
    }
}
