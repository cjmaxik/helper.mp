<?php
declare(strict_types=1);

namespace App\Console\Commands;

use Cache;
use Feeds;
use Illuminate\Console\Command;
use SimplePie;

class GrabNews extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'grab:news';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'HELP: Grab news from TruckersMP Blog (hourly)';

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle()
    {
        /** @var SimplePie|null $feed */
        $feed = Feeds::make('https://truckersmp.com/blog/rss', 1);

        $item = $feed->get_item(0);
        $news = null;

        if ($item) {
            $news = [
                'title' => $item->get_title(),
                'link' => $item->get_permalink()
            ];
        }
        dump($news);

        Cache::forever('news:news', $news);
        return null;
    }
}
