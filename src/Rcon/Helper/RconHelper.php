<?php
/**
 * @copyright Copyright (c) Ares (https://www.ares.to)
 *
 * @see LICENSE (MIT)
 */

namespace Ares\Rcon\Helper;

use Ares\Rcon\Exception\RconException;
use PHLAK\Config\Config;

/**
 * Class RconHelper
 *
 * @package Ares\Rcon\Helper
 */
class RconHelper
{
    /**
     * RconHelper constructor.
     *
     * @param Config $config
     */
    public function __construct(
        private Config $config
    ) {}

    /**
     * @param   resource    $socket
     * @param   string      $command
     * @param   array|null  $parameter
     *
     * @return array|null
     * @throws RconException
     * @throws \JsonException
     */
    public function sendCommand($socket, string $command, array $parameter = null): ?array
    {
        /** @var string $encodedData */
        $encodedData = json_encode([
            'key'  => $command,
            'data' => $parameter
        ], JSON_THROW_ON_ERROR);

        $executor = socket_write($socket, $encodedData, strlen($encodedData));

        if (!$executor) {
            throw new RconException(__('Could not send the provided Command'));
        }

        return json_decode(
            socket_read($socket, 2048), true, 512, JSON_THROW_ON_ERROR
        );
    }

    /**
     * Builds the Socket connection
     *
     * @param   resource  $socket
     *
     * @return RconHelper
     * @throws RconException
     */
    public function buildConnection($socket): self
    {
        /** @var string $host */
        $host = $this->config->get('hotel_settings.client.rcon_host');

        /** @var int $port */
        $port = $this->config->get('hotel_settings.client.rcon_port');

        $isConnectionEstablished = $this->connectToSocket($socket, $host, $port);

        if (!$isConnectionEstablished) {
            throw new RconException(__('Could not establish a connection to the rcon server'));
        }

        return $this;
    }

    /**
     * Connects to our Socket
     *
     * @param $socket
     * @param $host
     * @param $port
     *
     * @return bool
     */
    public function connectToSocket($socket, $host, $port): bool
    {
        return socket_connect($socket, $host, $port);
    }

    /**
     * Creates a Socket
     *
     * @return \Socket
     * @throws RconException
     */
    public function createSocket(): \Socket
    {
        $socket = socket_create(
            AF_INET,
            SOCK_STREAM,
            SOL_TCP
        );

        if (!$socket) {
            throw new RconException(__('Could not create the socket'), 409);
        }

        return $socket;
    }
}
