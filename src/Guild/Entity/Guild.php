<?php declare(strict_types=1);
/**
 * Ares (https://ares.to)
 *
 * @license https://gitlab.com/arescms/ares-backend/LICENSE (MIT License)
 */

namespace Ares\Guild\Entity;

use Ares\Framework\Entity\Entity;
use Ares\Room\Entity\Room;
use Ares\User\Entity\User;
use Doctrine\ORM\Mapping as ORM;
use Doctrine\ORM\Mapping\JoinColumn;
use Doctrine\ORM\Mapping\OneToOne;

/**
 * Class Guild
 *
 * @package Ares\Guild\Entity
 *
 * @ORM\Entity
 * @ORM\Table(name="guilds")
 * @ORM\Cache(usage="NONSTRICT_READ_WRITE")
 * @ORM\HasLifecycleCallbacks
 */
class Guild extends Entity
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
    private User $creator;

    /**
     * @ORM\Column(type="string", length=50)
     */
    private string $name;

    /**
     * @ORM\Column(type="string", length=512)
     */
    private string $description;

    /**
     * @OneToOne(targetEntity="\Ares\Room\Entity\Room", fetch="EAGER")
     * @JoinColumn(name="room_id", referencedColumnName="id")
     */
    private Room $room;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private string $badge;

    /**
     * @ORM\Column(type="integer", length=11)
     */
    private int $date_created;

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
     * @return Guild
     */
    public function setId(int $id): self
    {
        $this->id = $id;

        return $this;
    }

    /**
     * @return User|null
     */
    public function getCreator(): ?User
    {
        return $this->creator;
    }

    /**
     * @param User $creator
     *
     * @return Guild
     */
    public function setCreator(User $creator): self
    {
        $this->creator = $creator;

        return $this;
    }

    /**
     * @return string
     */
    public function getName(): string
    {
        return $this->name;
    }

    /**
     * @param string $name
     *
     * @return Guild
     */
    public function setName(string $name): self
    {
        $this->name = $name;

        return $this;
    }

    /**
     * @return string
     */
    public function getDescription(): string
    {
        return $this->description;
    }

    /**
     * @param string $description
     *
     * @return Guild
     */
    public function setDescription(string $description): self
    {
        $this->description = $description;

        return $this;
    }

    /**
     * @return Room|null
     */
    public function getRoom(): ?Room
    {
        return $this->room;
    }

    /**
     * @param Room $room
     *
     * @return Guild
     */
    public function setRoom(Room $room): self
    {
        $this->room = $room;

        return $this;
    }

    /**
     * @return string
     */
    public function getBadge(): string
    {
        return $this->badge;
    }

    /**
     * @param string $badge
     *
     * @return Guild
     */
    public function setBadge(string $badge): self
    {
        $this->badge = $badge;

        return $this;
    }

    /**
     * @return int
     */
    public function getDateCreated(): int
    {
        return $this->date_created;
    }

    /**
     * @param int $date_created
     *
     * @return Guild
     */
    public function setDateCreated(int $date_created): self
    {
        $this->date_created = $date_created;

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
            'creator' => $this->getCreator()->toArray(),
            'name' => $this->getName(),
            'description' => $this->getDescription(),
            'badge' => $this->getBadge(),
            'date_created' => $this->getDateCreated()
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
     * @param   string  $data
     */
    public function unserialize($data)
    {
        $values = unserialize($data);

        foreach ($values as $key => $value) {
            $this->$key = $value;
        }
    }
}
