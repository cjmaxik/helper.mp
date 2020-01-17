<?php
declare(strict_types=1);

namespace App\TruckersMP\Types;

use Exception;

/**
 * Players Ban
 *
 * @package Api\TruckersMP\Types
 */
class Players
{
    public $players;

    /**
     * Players constructor.
     * @param array $response
     * @throws Exception
     */
    public function __construct(array $response)
    {
        $this->players = $response['response'];
    }
}
