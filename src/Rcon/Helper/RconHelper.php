<?php
/**
 * Ares (https://ares.to)
 *
 * @license https://gitlab.com/arescms/ares-backend/LICENSE (MIT License)
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
     * @var Config
     */
    private Config $config;

    /**
     * RconHelper constructor.
     *
     * @param Config $config
     */
    public function __construct(
        Config $config
    ) {
        $this->config = $config;
    }

    /**
     * @param resource    $socket
     *
     * @param string      $command
     *
     * @param string|null $parameter
     *
     * @param string|null $value
     *
     * @return object
     * @throws RconException
     */
    public function sendCommand($socket, string $command, string $parameter = null, string $value = null): object
    {
        /** @var string $encodedData */
        $encodedData = json_encode([
            'key' => $command,
            'data' => [
                $parameter => $value
            ]
        ]);

        $executor = socket_write($socket, $encodedData, strlen($encodedData));

        if (!$executor) {
            throw new RconException(__('Could not send the Command'));
        }

        return json_decode(
            socket_read($socket, 2048)
        );
    }

    /**
     * Builds the Socket connection
     *
     * @param resource $socket
     *
     * @return RconHelper
     */
    public function buildConnection($socket): self
    {
        /** @var string $host */
        $host = $this->config->get('hotel_settings.client.rcon_host');

        /** @var int $port */
        $port = $this->config->get('hotel_settings.client.rcon_port');

        $this->connectToSocket($socket, $host, $port);

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
     * @return resource
     * @throws RconException
     */
    public function createSocket()
    {
        $socket = socket_create(
            AF_INET,
            SOCK_STREAM,
            SOL_TCP
        );

        if (!$socket) {
            throw new RconException(__('Could not create the Socket'), 409);
        }

        return $socket;
    }
}
