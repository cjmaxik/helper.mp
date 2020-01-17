<?php
declare(strict_types=1);

namespace App\Console\Commands;

use Cache;
use Exception;
use GuzzleHttp\Client;
use Illuminate\Console\Command;
use function in_array;

/**
 * Class GrabStatus
 *
 * @package App\Console\Commands
 */
class GrabStatus extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'grab:status';
    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'HELP: Grab info from TruckersMP Status Page';
    private $client;

    /**
     * Create a new command instance.
     *
     * @return void
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
        $this->info('Grab MP status info...');
        try {
            $cachet = json_decode($this->client->get(config('truckersmp.status_url'))->getBody()->getContents(), false, 512, JSON_THROW_ON_ERROR);

            $status = 1;

            /** @noinspection ForeachSourceInspection */
            $except = [];
            foreach ($cachet->data as $component) {
                if (in_array($component->id, $except, true)) {
                    continue;
                }

                if ($component->status > $status) {
                    $status = $component->status;
                }
            }

            $text_status = collect($cachet->data)->where('status', '>', 1)->whereNotIn('id', $except)->except('name')->implode('name', ', ');
        } catch (Exception $e) {
            $status = 'red';
            $text_status = null;
        }

        switch ($status) {
            case 1:
                $status = 'green';
                break;
            case 2:
                $status = 'blue';
                break;
            case 3:
                $status = 'yellow';
                break;
            default:
                $status = 'red';
        }

        if (!$this->option('quiet')) {
            dump($status);
            dump($text_status);
        }
        Cache::forever('status:status', $status);
        Cache::forever('status:text_status', $text_status);

        return null;
    }
}
