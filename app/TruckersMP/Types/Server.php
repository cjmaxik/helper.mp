<?php
declare(strict_types=1);

namespace App\TruckersMP\Types;

/**
 * Class Server
 *
 * @package Api\TruckersMP\Types
 */
class Server
{
    /**
     * Game server ID.
     *
     * @var int
     */
    public $id;

    /**
     * Game.
     *
     * @var string
     */
    public $game;

    /**
     * IP or Hostname of server.
     *
     * @var string
     */
    public $ip;

    /**
     * Port.
     *
     * @var int
     */
    public $port;

    /**
     * Game server name.
     *
     * @var string
     */
    public $name;

    /**
     * Game server short name.
     *
     * @var string
     */
    public $shortName;

    /**
     * Online status.
     *
     * @var bool
     */
    public $online;

    /**
     * Current player count.
     *
     * @var int
     */
    public $players;

    /**
     * Current queue count.
     *
     * @var int
     */
    public $queue;

    /**
     * Max player count.
     *
     * @var int
     */
    public $maxPlayers;

    /**
     * Speed limiter.
     *
     * @var bool
     */
    public $speedLimiter;

    /**
     * Collisions.
     *
     * @var bool
     */
    public $collisions;

    /**
     * Will cars be available for all players.
     *
     * @var bool
     */
    public $carsForPlayers;

    /**
     * Will police cars be available for all players.
     *
     * @var bool
     */
    public $policeCarsForPlayers;

    /**
     * "Away from keyboard" status.
     *
     * @var bool
     */
    public $afkEnabled;

    /**
     * Sync delay (tick rate).
     *
     * @var bool
     */
    public $syncDelay;

    /**
     * Is it an event server
     *
     * @var bool
     */
    public $event;

    /**
     * Server population in percents
     *
     * @var float|int
     */
    public $percents;

    /**
     * @var int
     */
    public $displayorder;

    /**
     * @var string
     */
    public $idPrefix;

    /**
     * Server constructor.
     *
     * @param array $server
     */
    public function __construct(array $server)
    {
        $this->id = (int)$server['id'];
        $this->game = $server['game'];
        $this->ip = $server['ip'];
        $this->port = (int)$server['port'];
        $this->name = $server['name'];
        $this->shortName = $server['shortname'];
        $this->idPrefix = $server['idprefix'];
        $this->online = (bool)$server['online'];
        $this->players = (int)$server['players'];
        $this->queue = (int)$server['queue'];
        $this->maxPlayers = (int)$server['maxplayers'];
        $this->displayorder = (int)$server['displayorder'];
        $this->speedLimiter = (bool)$server['speedlimiter'];
        $this->collisions = (bool)$server['collisions'];
        $this->carsForPlayers = (bool)$server['carsforplayers'];
        $this->policeCarsForPlayers = (bool)$server['policecarsforplayers'];
        $this->afkEnabled = (bool)$server['afkenabled'];
        $this->event = (bool)$server['event'];
        $this->promods = ($this->game === 'ETS2' && (bool)$server['promods']);
        $this->syncDelay = (int)$server['syncdelay'];

        $percents = $this->players * 100 / $this->maxPlayers;
        if ($percents <= 1 && $percents >= 0) {
            $percents = 1;
        }

        if ($this->maxPlayers < $this->players) {
            $this->maxPlayers = "{$this->maxPlayers}?";
        }

        $this->percents = $percents;
    }
}
