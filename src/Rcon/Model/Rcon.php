<?php
/**
 * Ares (https://ares.to)
 *
 * @license https://gitlab.com/arescms/ares-backend/LICENSE (MIT License)
 */

namespace Ares\Rcon\Model;

use Ares\Rcon\Exception\RconException;
use Ares\Rcon\Helper\RconHelper;

/**
 * Class Rcon
 *
 * @package Ares\Rcon\Model
 */
class Rcon
{
    /**
     * @var resource
     */
    private $socket;

    /**
     * @var RconHelper
     */
    private RconHelper $rconHelper;

    /**
     * Rcon constructor.
     *
     * @param RconHelper $rconHelper
     */
    public function __construct(
        RconHelper $rconHelper
    ) {
        $this->rconHelper = $rconHelper;
    }

    /**
     * @return RconHelper
     * @throws RconException
     */
    public function buildConnection(): RconHelper
    {
        $this->socket = $this->rconHelper->createSocket();

        return $this->rconHelper->buildConnection($this->socket);
    }

    /**
     * @return resource
     */
    public function getSocket()
    {
        return $this->socket;
    }
}
