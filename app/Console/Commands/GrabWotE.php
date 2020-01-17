<?php
declare(strict_types=1);

namespace App\Console\Commands;

use Cache;
use Goutte\Client;
use Illuminate\Console\Command;
use Illuminate\Contracts\Filesystem\FileNotFoundException;
use Image;
use RuntimeException;
use Storage;
use Symfony\Component\DomCrawler\Crawler;

/**
 * Class GrabWotE
 *
 * @property string gallery
 * @property string page
 * @package App\Console\Commands
 */
class GrabWotE extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'grab:wote {--force : Forcefully update the cache}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'HELPER: Download WoT Editor\'s Pick images and cache storage';

    /**
     * @var Client
     */
    private $client;

    private $screen = 'https://scs-wotr-gallery.s3.amazonaws.com/full/';
    private $storage_link = 'public/wotE/';

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();

        $this->client = new Client();

        $this->gallery = config('truckersmp.worldoftrucks.endpoint') . 'gallery';
        $this->page = config('truckersmp.worldoftrucks.endpoint') . 'image/';
    }

    /**
     * Execute the console command.
     *
     * @return mixed
     * @throws RuntimeException
     * @throws FileNotFoundException
     */
    public function handle()
    {
        if (Storage::exists($this->storage_link . 'last.txt')) {
            $last_img = Storage::get($this->storage_link . 'last.txt');

            if ($this->option('force')) {
                $this->info('Forcefully updating the cache...');
                Storage::deleteDirectory($this->storage_link . $last_img);
                Cache::forget('wot');
                $last_img = '0';
            }
        } else {
            $last_img = '0';
        }

        $links = [];
        $links = array_merge($links, $this->getLinks(0), $this->getLinks(6), $this->getLinks(12));

        $checkout = md5(json_encode($links, JSON_THROW_ON_ERROR, 512));

        if ($last_img === $checkout) {
            $this->info('WoT Images doesn\'t changed yet');
            $is_updated = false;
        } else {
            $this->info('WoT Images has changed!!!');
            $is_updated = true;
            Storage::put($this->storage_link . 'last.txt', $checkout);
        }

        $bar = $this->output->createProgressBar(count($links));

        // Save images on disk
        foreach ($links as $index => $value) {
            if ($value === null) {
                continue;
            }

            $int_link = "{$index}.jpg";
            $links[$index]['storage'] = $int_link;

            if ($is_updated) {

                $image = Image::make($links[$index]['screen']);

                $image_original = $image->fit(1920, 1080)->interlace()->stream('jpg');
                Storage::put($this->storage_link . "{$checkout}/{$int_link}", $image_original);

                $image_blurred = $image->blur(10)->gamma(0.5)->interlace()->stream('jpg');
                Storage::put($this->storage_link . "{$checkout}/blurred_{$int_link}", $image_blurred);
            }

            $links[$index]['storage'] = Storage::url($this->storage_link . "{$checkout}/{$int_link}");
            $links[$index]['storage_blurred'] = Storage::url($this->storage_link . "{$checkout}/blurred_{$int_link}");

            $bar->advance($index);
        }

        $bar->finish();

        if ($is_updated && $last_img !== '0') {
            Storage::deleteDirectory($this->storage_link . $last_img);
            $this->comment("Old directory {$this->storage_link}/{$last_img} has been deleted!");
        }

        Cache::forever('wote:links', $links);
        return null;
    }

    /**
     * @param int $page
     *
     * @return array
     * @throws RuntimeException
     */
    private function getLinks(int $page): array
    {
        $crawler = $this->client->request('GET', $this->gallery . '?p=' . $page . '&s=e');

        $links = $crawler->filter('div.gallery-item')->each(function ($node) {
            /** @var Crawler $node */
            $link = $node->filter('img')->each(
                static function ($node_a) {
                    /** @noinspection PhpUndefinedMethodInspection */
                    return $node_a->parents()->attr('href');
                });

            $nickname = $node->filter('p.author > a')->each(static function ($node_p) {
                /** @noinspection PhpUndefinedMethodInspection */
                return $node_p->text();
            });

            if ($link[0] === null) {
                return null;
            }

            $re = '/(image\/)(.+)(\?back)/m';
            preg_match_all($re, $link[0], $id, PREG_SET_ORDER);
            $id = $id[0][2];

            $value_hex = '0000000000000000' . $id;
            $value_hex = strtoupper(substr($value_hex, strlen($value_hex) - 16));

            return [
                'id' => $id,
                'nickname' => $nickname[0],
                'screen' => $this->screen . substr(strrev($id), 0, 3) . '/' . $value_hex . '.jpg',
                'page' => $this->page . $id . '#gallery'
            ];
        });

        return $links;
    }
}
