<?php declare(strict_types=1);
/**
 * Ares (https://ares.to)
 *
 * @license https://gitlab.com/arescms/ares-backend/LICENSE (MIT License)
 */

namespace Ares\Rcon\Entity;

use Ares\Framework\Exception\CacheException;
use Ares\Framework\Model\DataObject;
use Ares\Rcon\Entity\Contract\RconInterface;
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
     * @return int
     */
    public function getPermissionId(): int
    {
        return $this->getData(RconInterface::COLUMN_PERMISSION_ID);
    }

    /**
     * @param int $permission_id
     *
     * @return Rcon
     */
    public function setPermissionId(int $permission_id): Rcon
    {
        return $this->setData(RconInterface::COLUMN_PERMISSION_ID, $permission_id);
    }

    /**
     * @return Permission|null
     */
    public function getPermission(): ?Permission
    {
        $permission = $this->getData('permission');

        if ($permission) {
            return $permission;
        }

        $permissionRepository = repository(PermissionRepository::class);
        $searchCriteria = $permissionRepository->getDataObjectManager()
            ->where('id', $this->getPermissionId());

        try {
            $permission = $permissionRepository->getList($searchCriteria)->first();
        } catch (CacheException $e) {
            return null;
        }

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
