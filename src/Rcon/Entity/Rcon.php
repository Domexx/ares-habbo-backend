<?php declare(strict_types=1);
/**
 * @copyright Copyright (c) Ares (https://www.ares.to)
 *  
 * @see LICENSE (MIT)
 */

namespace Ares\Rcon\Entity;

use Ares\Framework\Exception\DataObjectManagerException;
use Ares\Framework\Model\DataObject;
use Ares\Rcon\Entity\Contract\RconInterface;
use Ares\Rcon\Repository\RconRepository;
use Ares\Role\Entity\Permission;
use Ares\Role\Repository\PermissionRepository;

/**
 * Class Rcon
 *
 * @package Ares\Rcon\Entity
 */
class Rcon extends DataObject implements RconInterface
{
    /** @var string */
    public const TABLE = 'ares_rcon_commands';

    /**
     * @return int
     */
    public function getId(): int
    {
        return $this->getData(RconInterface::COLUMN_ID);
    }

    /**
     * @param int $id
     *
     * @return Rcon
     */
    public function setId(int $id): Rcon
    {
        return $this->setData(RconInterface::COLUMN_ID, $id);
    }

    /**
     * @return string
     */
    public function getCommand(): string
    {
        return $this->getData(RconInterface::COLUMN_COMMAND);
    }

    /**
     * @param string $command
     *
     * @return Rcon
     */
    public function setCommand(string $command): Rcon
    {
        return $this->setData(RconInterface::COLUMN_COMMAND, $command);
    }

    /**
     * @return string
     */
    public function getTitle(): string
    {
        return $this->getData(RconInterface::COLUMN_TITLE);
    }

    /**
     * @param string $title
     *
     * @return Rcon
     */
    public function setTitle(string $title): Rcon
    {
        return $this->setData(RconInterface::COLUMN_TITLE, $title);
    }

    /**
     * @return string
     */
    public function getDescription(): string
    {
        return $this->getData(RconInterface::COLUMN_DESCRIPTION);
    }

    /**
     * @param string $description
     *
     * @return Rcon
     */
    public function setDescription(string $description): Rcon
    {
        return $this->setData(RconInterface::COLUMN_DESCRIPTION, $description);
    }

    /**
     * @return int|null
     */
    public function getPermissionId(): ?int
    {
        return $this->getData(RconInterface::COLUMN_PERMISSION_ID);
    }

    /**
     * @param int $permissionId
     *
     * @return Rcon
     */
    public function setPermissionId(int $permissionId): Rcon
    {
        return $this->setData(RconInterface::COLUMN_PERMISSION_ID, $permissionId);
    }

    /**
     * @return Permission|null
     * @throws DataObjectManagerException
     */
    public function getPermission(): ?Permission
    {
        /** @var Permission $permission */
        $permission = $this->getData('permission');

        if ($permission) {
            return $permission;
        }

        if (!isset($this)) {
            return null;
        }

        if(!$this->getPermissionId()) {
            return null;
        }

        /** @var RconRepository $rconRepository */
        $rconRepository = repository(RconRepository::class);

        /** @var PermissionRepository $permissionRepository */
        $permissionRepository = repository(PermissionRepository::class);

        /** @var Permission $permission */
        $permission = $rconRepository->getOneToOne(
            $permissionRepository,
            $this->getPermissionId(),
            'permission_id'
        );

        if (!$permission) {
            return null;
        }

        $this->setPermission($permission);

        return $permission;
    }

    /**
     * @param Permission $permission
     *
     * @return Rcon
     */
    public function setPermission(Permission $permission): Rcon
    {
        return $this->setData('permission', $permission);
    }
}
