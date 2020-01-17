<?php
declare(strict_types=1);

namespace App\TruckersMP\Types;

use App\TruckersMP\Exceptions\APIErrorException;
use ArrayAccess;
use Exception;
use Iterator;

/**
 * Class Servers
 *
 * @package Api\TruckersMP\Types
 */
class Servers implements Iterator, ArrayAccess
{
    /**
     * Array of servers.
     *
     * @var array
     */
    public $servers;

    /**
     * Iterator position.
     *
     * @var int
     */
    private $position = 0;

    /**
     * Servers constructor.
     *
     * @param array $response
     *
     * @throws APIErrorException
     */
    public function __construct(array $response)
    {
        if ($response['error'] === 'true' && $response['descriptor'] === 'Unable to fetch servers') {
            throw new APIErrorException($response['descriptor']);
        }

        foreach ($response['response'] as $k => $server) {
            $this->servers[$k] = new Server($server);
        }
    }

    public function rewind()
    {
        $this->position = 0;
    }

    /**
     * @return mixed
     */
    public function current()
    {
        return $this->servers[$this->position];
    }

    /**
     * @return int|mixed
     */
    public function key()
    {
        return $this->position;
    }

    public function next()
    {
        ++$this->position;
    }

    /**
     * @return bool
     */
    public function valid()
    {
        return isset($this->servers[$this->position]);
    }

    /**
     * @param mixed $offset
     * @param mixed $value
     *
     * @return Exception|void
     */
    public function offsetSet($offset, $value)
    {
        // TODO: custom class that gives a better description of the error
        return new Exception('Can not change servers');
    }

    /**
     * @param mixed $offset
     *
     * @return bool
     */
    public function offsetExists($offset)
    {
        return isset($this->servers[$offset]);
    }

    /**
     * @param mixed $offset
     *
     * @return Exception|void
     */
    public function offsetUnset($offset)
    {
        // TODO: custom class that gives a better description of the error
        return new Exception('Can not change servers');
    }

    /**
     * @param mixed $offset
     *
     * @return mixed|null
     */
    public function offsetGet($offset)
    {
        return $this->servers[$offset] ?? null;
    }
}
