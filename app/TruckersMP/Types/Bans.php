<?php
declare(strict_types=1);

namespace App\TruckersMP\Types;

use App\TruckersMP\Exceptions\APIErrorException;
use App\TruckersMP\Exceptions\PlayerNotFoundException;
use ArrayAccess;
use Exception;
use Iterator;

/**
 * Class Bans
 *
 * @package Api\TruckersMP\Types
 */
class Bans implements Iterator, ArrayAccess
{
    /**
     * Array of bans.
     *
     * @var array
     */
    public $bans;
    /**
     * Iterator position.
     *
     * @var int
     */
    private $position = 0;

    /**
     * Bans constructor.
     *
     * @param array $response
     *
     * @throws PlayerNotFoundException
     * @throws APIErrorException
     * @throws Exception
     */
    public function __construct(array $response)
    {
        if ($response['error'] &&
            ($response['descriptor'] === 'No player ID submitted' ||
                $response['descriptor'] === 'Invalid user ID')
        ) {
            throw new PlayerNotFoundException($response['descriptor']);
        }

        if ($response['error'] &&
            $response['descriptor'] === 'Something when wrong') {
            throw new APIErrorException($response['descriptor']);
        }

        foreach ($response['response'] as $k => $ban) {
            $this->bans[$k] = new Ban($ban);
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
        return $this->bans[$this->position];
    }

    /**
     * @return int
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
        return isset($this->bans[$this->position]);
    }

    /**
     * @param mixed $offset
     * @param mixed $value
     *
     * @return Exception
     */
    public function offsetSet($offset, $value)
    {
        // TODO: custom class that gives a better description of the error
        return new Exception('Can not change bans');
    }

    /**
     * @param mixed $offset
     *
     * @return bool
     */
    public function offsetExists($offset)
    {
        return isset($this->bans[$offset]);
    }

    /**
     * @param mixed $offset
     *
     * @return Exception
     */
    public function offsetUnset($offset)
    {
        // TODO: custom class that gives a better description of the error
        return new Exception('Can not change bans');
    }

    /**
     * @param mixed $offset
     *
     * @return mixed|null
     */
    public function offsetGet($offset)
    {
        return $this->bans[$offset] ?? null;
    }
}
