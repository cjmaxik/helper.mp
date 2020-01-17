<?php
declare(strict_types=1);

namespace App\Console\Commands;

use Exception;
use GuzzleHttp\Client;
use GuzzleHttp\Exception\GuzzleException;
use GuzzleHttp\HandlerStack;
use GuzzleHttp\Middleware;
use GuzzleHttp\Psr7\Uri;
use Illuminate\Console\Command;
use Psr\Http\Message\RequestInterface;
use function count;

/**
 * Class GrabTranslations
 *
 * @package App\Console\Commands
 */
class GrabTranslations extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'grab:translations';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Grab translations from OneSky';

    /**
     * @var Client
     */
    private $oneskyClient;

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct()
    {
        $handler = HandlerStack::create();
        $handler->unshift(Middleware::mapRequest(static function (RequestInterface $request) {
            $timestamp = time();

            return $request->withUri(Uri::withQueryValues($request->getUri(), [
                'api_key' => config('services.onesky.key'),
                'timestamp' => $timestamp,
                'dev_hash' => md5($timestamp . config('services.onesky.secret'))
            ]));
        }));

        $this->oneskyClient = new Client([
            'base_uri' => 'https://platform.api.onesky.io/1/projects/',
            'handler' => $handler
        ]);

        parent::__construct();
    }

    /**
     * Execute the console command.
     *
     * @return mixed
     * @throws Exception
     * @throws GuzzleException
     */
    public function handle()
    {
        if (!app()->isLocal()) {
            $this->error('This command works in Development environment only!');

            return;
        }

        $langs = array_keys(config('locales.prod') + config('locales.dev'));

        $bar = $this->output->createProgressBar(count($langs));

        foreach ($langs as $index => $locale) {
            $realLocale = $locale;
            if ($locale === 'tw') {
                $realLocale = 'zh-Hant-TW';
            }

            $response = (string)$this->oneskyClient->request('GET', '999999999999/translations', [
                'query' => [
                    'locale' => $realLocale,
                    'source_file_name' => 'en.json'
                ]
            ])->getBody();

            file_put_contents(__DIR__ . '/../../../resources/lang/' . $locale . '.json', $response);
            $bar->setProgress($index);
        }

        $bar->finish();

        $this->info('Translations are downloaded!');
        return null;
    }
}
