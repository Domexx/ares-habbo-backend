<?php
/**
 * @copyright Copyright (c) Ares (https://www.ares.to)
 *
 * @see LICENSE (MIT)
 */

namespace Ares\Rcon\Service;

use Ares\Framework\Exception\NoSuchEntityException;
use Ares\Framework\Interfaces\CustomResponseInterface;
use Ares\Framework\Interfaces\HttpResponseCodeInterface;
use Ares\Rcon\Exception\RconException;
use Ares\Rcon\Interfaces\Response\RconResponseCodeInterface;
use Ares\Rcon\Model\Rcon;
use Ares\Rcon\Repository\RconRepository;
use Ares\Role\Exception\RoleException;
use Ares\Role\Service\CheckAccessService;

/**
 * Class ExecuteRconCommandService
 *
 * @package Ares\Rcon\Service
 */
class ExecuteRconCommandService
{
    /**
     * ExecuteRconCommandService constructor.
     *
     * @param RconRepository     $rconRepository
     * @param Rcon               $rcon
     * @param CheckAccessService $checkAccessService
     */
    public function __construct(
        private RconRepository $rconRepository,
        private Rcon $rcon,
        private CheckAccessService $checkAccessService
    ) {}

    /**
     * @param int   $userId
     * @param array $data
     *
     * @param bool  $fromSystem
     *
     * @return CustomResponseInterface
     * @throws RconException
     * @throws NoSuchEntityException
     */
    public function execute(int $userId, array $data, bool $fromSystem = false): CustomResponseInterface
    {
        /** @var \Ares\Rcon\Entity\Rcon $existingCommand */
        $existingCommand = $this->rconRepository->get($data['command'], 'command');

        try {
            if (!$fromSystem && $existingCommand->getPermission() !== null) {
                $permissionName = $existingCommand
                    ->getPermission()
                    ->getName();
            }

            $hasAccess = $this->checkAccessService
                ->execute(
                    $userId,
                    $permissionName ?? null
                );

            if (!$hasAccess) {
                throw new RoleException(
                    __('You dont have the special rights to execute that action'),
                    RconResponseCodeInterface::RESPONSE_RCON_NO_RIGHTS_TO_EXECUTE,
                    HttpResponseCodeInterface::HTTP_RESPONSE_FORBIDDEN
                );
            }

            $executeCommand = $this->rcon
                ->buildConnection()
                ->sendCommand(
                    $this->rcon->getSocket(),
                    $data['command'],
                    $data['params'] ?? null
                );
        } catch(\Exception $e) {
            throw new RconException(
              $e->getMessage(),
              $e->getCode()
            );
        }

        return response()
            ->setData($executeCommand);
    }
}
