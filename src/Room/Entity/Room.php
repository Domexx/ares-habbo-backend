<?php declare(strict_types=1);
/**
 * Ares (https://ares.to)
 *
 * @license https://gitlab.com/arescms/ares-backend/LICENSE (MIT License)
 */

namespace Ares\Room\Entity;

use Ares\Framework\Entity\Entity;
use Ares\Guild\Entity\Guild;
use Ares\User\Entity\User;
use Doctrine\ORM\Mapping as ORM;
use Doctrine\ORM\Mapping\JoinColumn;
use Doctrine\ORM\Mapping\OneToOne;

/**
 * Class Room
 *
 * @package Ares\Room\Entity
 *
 * @ORM\Entity
 * @ORM\Table(name="rooms")
 * @ORM\Cache(usage="NONSTRICT_READ_WRITE")
 * @ORM\HasLifecycleCallbacks
 */
class Room extends Entity
{
    /**
     * @ORM\Id
     * @ORM\Column(name="id", type="integer")
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    private int $id;

    /**
     * @OneToOne(targetEntity="\Ares\User\Entity\User", fetch="EAGER")
     * @JoinColumn(name="owner_id", referencedColumnName="id")
     */
    private ?User $owner;

    /**
     * @ORM\Column(type="string", length=50)
     */
    private string $name;

    /**
     * @ORM\Column(type="string", length=512)
     */
    private string $description;

    /**
     * @ORM\Column(type="string", columnDefinition="ENUM('open','locked','password', 'invisible')")
     */
    private string $state;

    /**
     * @ORM\Column(type="integer", length=11)
     */
    private int $users;

    /**
     * @ORM\Column(type="integer", length=11)
     */
    private int $users_max;

    /**
     * @OneToOne(targetEntity="\Ares\Guild\Entity\Guild", fetch="EAGER")
     * @JoinColumn(name="guild_id", referencedColumnName="id", nullable=true)
     */
    private ?Guild $guild;

    /**
     * @ORM\Column(type="integer", length=11)
     */
    private int $score;

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
     * @return Room
     */
    public function setId(int $id): self
    {
        $this->id = $id;

        return $this;
    }

    /**
     * @return User|null
     */
    public function getOwner(): ?User
    {
        return $this->owner;
    }

    /**
     * @param User $owner
     *
     * @return Room
     */
    public function setOwner(User $owner): self
    {
        $this->owner = $owner;

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
     * @return Room
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
     * @return Room
     */
    public function setDescription(string $description): self
    {
        $this->description = $description;

        return $this;
    }

    /**
     * @return string
     */
    public function getState(): string
    {
        return $this->state;
    }

    /**
     * @param string $state
     *
     * @return Room
     */
    public function setState(string $state): self
    {
        $this->state = $state;

        return $this;
    }

    /**
     * @return int
     */
    public function getUsers(): int
    {
        return $this->users;
    }

    /**
     * @param int $users
     *
     * @return Room
     */
    public function setUsers(int $users): self
    {
        $this->users = $users;

        return $this;
    }

    /**
     * @return int
     */
    public function getUsersMax(): int
    {
        return $this->users_max;
    }

    /**
     * @param int $users_max
     *
     * @return Room
     */
    public function setUsersMax(int $users_max): self
    {
        $this->users_max = $users_max;

        return $this;
    }

    /**
     * @return Guild|null
     */
    public function getGuild(): ?Guild
    {
        return $this->guild;
    }

    /**
     * @param Guild $guild
     *
     * @return Room
     */
    public function setGuild(Guild $guild): self
    {
        $this->guild = $guild;

        return $this;
    }

    /**
     * @return int
     */
    public function getScore(): int
    {
        return $this->score;
    }

    /**
     * @param int $score
     *
     * @return Room
     */
    public function setScore(int $score): self
    {
        $this->score = $score;

        return $this;
    }

    /**
     * @ORM\PostLoad
     */
    public function loadNullGuild()
    {
        if ($this->guild && $this->guild->getId() == 0) {
            $this->guild = null;
        }
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
            'owner' => $this->getOwner()->toArray(),
            'name' => $this->getName(),
            'description' => $this->getDescription(),
            'state' => $this->getState(),
            'users' => $this->getUsers(),
            'users_max' => $this->getUsersMax(),
            'guild' => (is_null($this->getGuild()) ? $this->getGuild() : $this->getGuild()->toArray()),
            'score' => $this->getScore()
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
     * @param string $data
     */
    public function unserialize($data)
    {
        $values = unserialize($data);

        foreach ($values as $key => $value) {
            $this->$key = $value;
        }
    }
}
