<?php declare(strict_types=1);
/**
 * @copyright Copyright (c) Ares (https://www.ares.to)
 *  
 * @see LICENSE (MIT)
 */

namespace Ares\Settings\Entity;

use Ares\Framework\Model\DataObject;
use Ares\Settings\Entity\Contract\SettingInterface;

/**
 * Class Settings
 *
 * @package Ares\Settings\Entity
 */
class Setting extends DataObject implements SettingInterface
{
    /** @var string */
    public const TABLE = 'ares_settings';

    /**
     * @return int
     */
    public function getId(): int
    {
        return $this->getData(SettingInterface::COLUMN_ID);
    }

    /**
     * @param int $id
     *
     * @return Setting
     */
    public function setId(int $id): Setting
    {
        return $this->setData(SettingInterface::COLUMN_ID, $id);
    }

    /**
     * @return string
     */
    public function getKey(): string
    {
        return $this->getData(SettingInterface::COLUMN_KEY);
    }

    /**
     * @param string $key
     *
     * @return Setting
     */
    public function setKey(string $key): Setting
    {
        return $this->setData(SettingInterface::COLUMN_KEY, $key);
    }

    /**
     * @return string
     */
    public function getValue(): string
    {
        return $this->getData(SettingInterface::COLUMN_VALUE);
    }

    /**
     * @param string $value
     *
     * @return Setting
     */
    public function setValue(string $value): Setting
    {
        return $this->setData(SettingInterface::COLUMN_VALUE, $value);
    }
}
