<?php
declare(strict_types=1);

namespace App\TruckersMP\Types;

use App\TruckersMP\Exceptions\PlayerNotFoundException;
use DateTime;

/**
 * Class Player
 *
 * @package Api\TruckersMP\Types
 */
class Player
{
    /**
     * User ID.
     *
     * @var int
     */
    public $id;

    /**
     * Username.
     *
     * @var string
     */
    public $name;

    /**
     * Avatar URL (260x260px).
     *
     * @var string
     */
    public $avatar;

    /**
     * Small avatar URL (32x32px).
     *
     * @var string
     */
    public $smallAvatar;

    /**
     * Date and time user joined.
     *
     * @var DateTime
     */
    public $joinDate;

    /**
     * User's associated SteamID.
     *
     * @var string
     */
    public $steamID64;
    public $steamID;

    /**
     * Human readable group name.
     *
     * @var string
     */
    public $groupName;

    /**
     * Group ID of user.
     *
     * @var int
     */
    public $groupID;

    /**
     * Is user banned.
     *
     * @var bool
     */
    public $banned;

    /**
     * Time and Date when the last ban expires.
     *
     * @var DateTime
     */
    public $bannedUntil;

    /**
     * Is user ban history public.
     *
     * @var bool
     */
    public $displayBans;

    /**
     * If user is an in-game admin.
     *
     * @var bool
     */
    public $inGameAdmin;

    /**
     * Player VTC information (or null)
     *
     * @var array|null
     */
    public $vtc;

    /**
     * Player constructor.
     *
     * @param array $response
     *
     * @throws PlayerNotFoundException
     */
    public function __construct(array $response)
    {
        if ($response['error']) {
            throw new PlayerNotFoundException($response['response']);
        }

        $this->id = $response['response']['id'] ?? null;
        $this->name = $response['response']['name'] ?? null;
        $this->avatar = $response['response']['avatar'] ?? null;
        $this->smallAvatar = $response['response']['smallAvatar'] ?? null;
        $this->joinDate = $response['response']['joinDate'] ?? null;
        $this->steamID = $response['response']['steamID'] ?? null;
        $this->steamID64 = $response['response']['steamID64'] ?? null;
        $this->groupName = $response['response']['groupName'] ?? null;
        $this->groupID = $response['response']['groupID'] ?? null;
        $this->banned = $response['response']['banned'] ?? null;
        $this->bannedUntil = $response['response']['bannedUntil'] ?? null;
        $this->displayBans = $response['response']['displayBans'] ?? null;
        $this->inGameAdmin = $response['response']['permissions']['isGameAdmin'] ?? null;
        $this->vtc = $response['response']['vtc']['id'] ? [
            'id' => $response['response']['vtc']['id'],
            'name' => $response['response']['vtc']['name'],
            'tag' => $response['response']['vtc']['tag'],
            'inVTC' => $response['response']['vtc']['inVTC'],
        ] : null;
    }
}
