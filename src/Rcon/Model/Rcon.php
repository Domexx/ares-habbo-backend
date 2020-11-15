<?php
/**
 * @copyright Copyright (c) Ares (https://www.ares.to)
 *  
 * @see LICENSE (MIT)
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
     * Rcon constructor.
     *
     * @param RconHelper $rconHelper
     */
    public function __construct(
        private RconHelper $rconHelper
    ) {}

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
