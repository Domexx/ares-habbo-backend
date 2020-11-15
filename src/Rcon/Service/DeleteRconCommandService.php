<?php
/**
 * @copyright Copyright (c) Ares (https://www.ares.to)
 *  
 * @see LICENSE (MIT)
 */

namespace Ares\Rcon\Service;

use Ares\Framework\Exception\DataObjectManagerException;
use Ares\Framework\Exception\NoSuchEntityException;
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
     * DeleteRconCommandService constructor.
     *
     * @param RconRepository $rconRepository
     */
    public function __construct(
        private RconRepository $rconRepository
    ) {}

    /**
     * @param array $data
     *
     * @return CustomResponseInterface
     * @throws DataObjectManagerException
     * @throws NoSuchEntityException
     * @throws RconException
     */
    public function execute(array $data): CustomResponseInterface
    {
        /** @var Rcon $command */
        $command = $this->rconRepository->get($data['command'], 'command');
        $deleted = $this->rconRepository->delete($command->getId());

        if (!$deleted) {
            throw new RconException(__('Command could not be deleted'));
        }

        return response()
            ->setData(true);
    }
}
