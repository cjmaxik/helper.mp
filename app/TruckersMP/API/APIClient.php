<?php
declare(strict_types=1);

/*
* TruckersMP REST API Library
* Website: truckersmp.com
*/

namespace App\TruckersMP\API;

use App\TruckersMP\Exceptions\APIErrorException;
use App\TruckersMP\Exceptions\PlayerNotFoundException;
use App\TruckersMP\Types\Bans;
use App\TruckersMP\Types\GameTime;
use App\TruckersMP\Types\Player;
use App\TruckersMP\Types\Players;
use App\TruckersMP\Types\Servers;
use App\TruckersMP\Types\Version;
use Http\Client\Exception;

/**
 * Class APIClient
 *
 * @package TruckersMP\API
 */
class APIClient
{
    public const API_ENDPOINT = 'api.truckersmp.com';
    public const API_VERSION = 'v2';

    /**
     * @var Request
     */
    private $request;

    /**
     * APIClient constructor.
     *
     * @param array $config
     * @param bool $secure
     */

    public function __construct(array $config = [], $secure = true)
    {
        $scheme = $secure ? 'https' : 'http';
        $url = $scheme . '://' . self::API_ENDPOINT . '/' . self::API_VERSION . '/';

        $this->request = new Request($url, $config);
    }

    /**
     * Fetch player information.
     *
     * @param int $id
     *
     * @return Player
     * @throws \Exception
     * @throws Exception
     * @throws PlayerNotFoundException
     */
    public function player($id): Player
    {
        $result = $this->request->execute('player/' . $id);

        return new Player($result);
    }

    /**
     * @param $id
     *
     * @return Bans
     * @throws \Exception
     * @throws Exception
     * @throws PlayerNotFoundException
     */
    public function bans($id): Bans
    {
        $result = $this->request->execute('bans/' . $id);

        return new Bans($result);
    }

    /**
     * @return Servers
     * @throws \Exception
     * @throws Exception
     * @throws APIErrorException
     */
    public function servers(): Servers
    {
        $result = $this->request->execute('servers');

        return new Servers($result);
    }

    /**
     * @return GameTime
     * @throws Exception
     * @throws \Exception
     */
    public function gameTime(): GameTime
    {
        $result = $this->request->execute('game_time');

        return new GameTime($result);
    }

    /**
     * @return Version
     * @throws Exception
     * @throws \Exception
     */
    public function version(): Version
    {
        $result = $this->request->execute('version');

        return new Version($result);
    }

    /**
     * @return Players
     * @throws Exception
     * @throws \Exception
     */
    public function players(): Players
    {
        $result = $this->request->execute('players');

        return new Players($result);
    }
}
