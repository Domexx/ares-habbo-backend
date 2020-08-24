<?php declare(strict_types=1);
/**
 * Ares (https://ares.to)
 *
 * @license https://gitlab.com/arescms/ares-backend/LICENSE (MIT License)
 */

namespace Ares\Ban\Entity;

use Ares\Framework\Entity\Entity;
use Ares\User\Entity\User;
use Doctrine\ORM\Mapping as ORM;
use Doctrine\ORM\Mapping\JoinColumn;
use Doctrine\ORM\Mapping\OneToOne;

/**
 * Class Ban
 *
 * @package Ares\Ban\Entity
 *
 * @ORM\Entity
 * @ORM\Table(name="bans")
 * @ORM\Cache(usage="NONSTRICT_READ_WRITE")
 */
class Ban extends Entity
{
    /**
     * @ORM\Id
     * @ORM\Column(name="id", type="integer")
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    private int $id;

    /**
     * @OneToOne(targetEntity="\Ares\User\Entity\User", fetch="EAGER")
     * @JoinColumn(name="user_id", referencedColumnName="id")
     */
    private ?User $user;

    /**
     * @OneToOne(targetEntity="\Ares\User\Entity\User", fetch="EAGER")
     * @JoinColumn(name="user_staff_id", referencedColumnName="id")
     */
    private ?User $staff;

    /**
     * @ORM\Column(type="integer", length=11)
     */
    private int $timestamp;

    /**
     * @ORM\Column(type="integer", length=11)
     */
    private int $ban_expire;

    /**
     * @ORM\Column(type="string", length=200)
     */
    private string $ban_reason;

    /**
     * @return int
     */
    public function getId(): int
    {
        return $this->id;
    }

    /**
     * @param int $id
     *
     * @return Ban
     */
    public function setId(int $id): self
    {
        $this->id = $id;

        return $this;
    }

    /**
     * @return User|null
     */
    public function getUser(): ?User
    {
        return $this->user;
    }

    /**
     * @param User|null $user
     *
     * @return Ban
     */
    public function setUser(?User $user): self
    {
        $this->user = $user;

        return $this;
    }

    /**
     * @return User|null
     */
    public function getStaff(): ?User
    {
        return $this->staff;
    }

    /**
     * @param User|null $staff
     *
     * @return Ban
     */
    public function setStaff(?User $staff): self
    {
        $this->staff = $staff;

        return $this;
    }

    /**
     * @return int
     */
    public function getTimestamp(): int
    {
        return $this->timestamp;
    }

    /**
     * @param int $timestamp
     *
     * @return Ban
     */
    public function setTimestamp(int $timestamp): self
    {
        $this->timestamp = $timestamp;

        return $this;
    }

    /**
     * @return int
     */
    public function getBanExpire(): int
    {
        return $this->ban_expire;
    }

    /**
     * @param int $ban_expire
     *
     * @return Ban
     */
    public function setBanExpire(int $ban_expire): self
    {
        $this->ban_expire = $ban_expire;

        return $this;
    }

    /**
     * @return string
     */
    public function getBanReason(): string
    {
        return $this->ban_reason;
    }

    /**
     * @param string $ban_reason
     *
     * @return Ban
     */
    public function setBanReason(string $ban_reason): self
    {
        $this->ban_reason = $ban_reason;

        return $this;
    }

    /**
     * Returns a copy of the current Entity safely
     *
     * @return array
     */
    public function toArray(): array
    {
        return [
            'id' => $this->getId(),
            'user' => $this->getUser()->toArray(),
            'staff' => $this->getStaff()->getUsername(),
            'timestamp' => $this->getTimestamp(),
            'ban_expire' => $this->getBanExpire(),
            'ban_reason' => $this->getBanReason()
        ];
    }

    /**
     * @return string
     */
    public function serialize()
    {
        return serialize(get_object_vars($this));
    }

    /**
     * @param $data
     */
    public function unserialize($data)
    {
        $values = unserialize($data);

        foreach ($values as $key => $value) {
            $this->$key = $value;
        }
    }
}
