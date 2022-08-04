<?php declare(strict_types=1);
/**
 * @copyright Copyright (c) Ares (https://www.ares.to)
 *  
 * @see LICENSE (MIT)
 */

namespace Ares\Polaris\Entity;

use Ares\Framework\Model\DataObject;
use Ares\Polaris\Entity\Contract\PeakInterface;

/**
 * Class Peak
 *
 * @package Ares\Polaris\Entity
 */
class Peak extends DataObject implements PeakInterface
{
    /** @var string */
    public const TABLE = 'ares_polaris_peaks';

    /**
     * @return int
     */
    public function getId(): int
    {
        return $this->getData(PeakInterface::COLUMN_ID);
    }

    /**
     * @param int $id
     *
     * @return Peak
    */
    public function setId(int $id): Peak
    {
        return $this->setData(PeakInterface::COLUMN_ID, $id);
    }

    /**
     * @return int
     */
    public function getCount(): int
    {
        return $this->getData(PeakInterface::COLUMN_COUNT);
    }

    /**
     * @param int $count
     *
     * @return Peak
     */
    public function setTitle(int $count): Peak
    {
        return $this->setData(PeakInterface::COLUMN_COUNT, $count);
    }

    /**
     * @return \DateTime
    */
    public function getDate(): \DateTime
    {
        return $this->getData(PeakInterface::COLUMN_DATE);
    }

    /**
     * @param \DateTime $date
     *
     * @return Peal
    */
    public function setDate(\DateTime $date): Peak
    {
        return $this->setData(PeakInterface::COLUMN_DATE, $date);
    }
}