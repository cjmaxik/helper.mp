<?php
declare(strict_types=1);

namespace App\TruckersMP\Types;

use Carbon\Carbon;
use Exception;

/**
 * Class Ban
 *
 * @package Api\TruckersMP\Types
 */
class Ban
{
    /**
     * Time and Date when the ban expires.
     *
     * @var Carbon|null
     */
    public $expires;

    /**
     * Plain Time and Date for $expires
     *
     * @var string
     */
    public $expiresPlain;

    /**
     * Time and Date when the ban was created.
     *
     * @var Carbon
     */
    public $created;

    /**
     * Plain Time and Date for $created
     *
     * @var string
     */
    public $createdPlain;

    /**
     * True if ban is currently active.
     *
     * @var bool
     */
    public $active;

    /**
     * Reason for the ban.
     *
     * @var string
     */
    public $reason;

    /**
     * Admin's name.
     *
     * @var string
     */
    public $adminName;

    /**
     * Admin's ID.
     *
     * @var int
     */
    public $adminID;

    /**
     * Ban constructor.
     *
     * @param array $ban
     * @throws Exception
     */
    public function __construct($ban)
    {
        // Expiration
        if ($ban['expiration'] === null) {
            $this->expires = null;
            $this->expiresPlain = null;
        } else {
            $this->expires = new Carbon($ban['expiration'], 'UTC');
            $this->expiresPlain = date('d.m.Y h:i:s', strtotime($ban['expiration'])) . ' UTC';
        }

        // Time Added
        $this->created = new Carbon($ban['timeAdded'], 'UTC');
        $this->createdPlain = date('d.m.Y h:i:s', strtotime($ban['timeAdded'])) . ' UTC';

        // Active
        $this->active = $ban['active'];
        if (null !== $this->expires && $this->active && !$this->expires->greaterThan(Carbon::now('UTC'))) {
            $this->active = false;
        }

        $this->reason = $ban['reason'];
        $this->adminName = $ban['adminName'];
        $this->adminID = $ban['adminID'];
    }
}
