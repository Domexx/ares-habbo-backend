<?php
/**
 * Ares (https://ares.to)
 *
 * @license https://gitlab.com/arescms/ares-backend/LICENSE (MIT License)
 */

namespace Ares\Rcon\Service;

use Ares\Framework\Exception\DataObjectManagerException;
use Ares\Framework\Interfaces\CustomResponseInterface;
use Ares\Rcon\Entity\Rcon;
use Ares\Rcon\Exception\RconException;
use Ares\Rcon\Repository\RconRepository;

/**
 * Class DeleteRconCommandService
 *
 * @package Ares\Rcon\Service
 */
class DeleteRconCommandService
{
    /**
     * @var RconRepository
     */
    private RconRepository $rconRepository;

    /**
     * DeleteRconCommandService constructor.
     *
     * @param RconRepository $rconRepository
     */
    public function __construct(
        RconRepository $rconRepository
    ) {
        $this->rconRepository = $rconRepository;
    }

    /**
     * @param array $data
     *
     * @return CustomResponseInterface
     * @throws RconException
     * @throws DataObjectManagerException
     */
    public function execute(array $data): CustomResponseInterface
    {
        /** @var Rcon $command */
        $command = $this->rconRepository->get($data['command'], 'command');

        if (!$command) {
            throw new RconException(__('Command could not be found'));
        }

        $deleted = $this->rconRepository->delete($command->getId());

        return response()
            ->setData($deleted);
    }
}
