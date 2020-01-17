<?php
declare(strict_types=1);
/**
 * Created by PhpStorm.
 * User: thor
 * Date: 12.07.16
 * Time: 09:55.
 */

namespace App\TruckersMP\Types;

use Carbon\Carbon;
use Exception;
use RuntimeException;

/**
 * Class GameTime
 *
 * @package Api\TruckersMP\Types
 */
class GameTime
{
    public $time;

    public $timeRaw;

    /**
     * GameTime constructor.
     *
     * @param array $response
     *
     * @throws Exception
     */
    public function __construct(array $response)
    {
        if ($response['error']) {
            // TODO: actually throw a usable error
            throw new RuntimeException('API Error');
        }
        $this->timeRaw = $response['game_time'];

        $load['minutes'] = $response['game_time'];

        $load['hours'] = (int)($load['minutes'] / 60);
        $load['minutes'] %= 60;

        $load['days'] = (int)($load['hours'] / 24);
        $load['hours'] %= 24;

        $load['months'] = (int)($load['days'] / 30);
        $load['days'] %= 30;

        $load['years'] = (int)($load['months'] / 12);
        $load['months'] %= 12;

        $this->time = Carbon::create($load['years'], $load['months'], $load['days'], $load['hours'], $load['minutes']);
    }
}
