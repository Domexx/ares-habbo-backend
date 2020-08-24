<?php declare(strict_types=1);
/**
 * Ares (https://ares.to)
 *
 * @license https://gitlab.com/arescms/ares-backend/LICENSE (MIT License)
 */

namespace Ares\Messenger\Entity;

use Ares\Framework\Entity\Entity;
use Ares\User\Entity\User;
use Doctrine\ORM\Mapping as ORM;
use Doctrine\ORM\Mapping\JoinColumn;
use Doctrine\ORM\Mapping\OneToOne;

/**
 * Class MessengerFriendship
 *
 * @package Ares\Messenger\Entity
 *
 * @ORM\Table(name="messenger_friendships")
 * @ORM\Entity(repositoryClass="Ares\Messenger\Repository\MessengerRepository")
 * @ORM\Cache(usage="NONSTRICT_READ_WRITE")
 */
class MessengerFriendship extends Entity
{
    /**
     * @ORM\Id
     * @ORM\Column(name="id", type="integer")
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    private int $id;

    /**
     * @OneToOne(targetEntity="\Ares\User\Entity\User", fetch="EAGER")
     * @JoinColumn(name="user_one_id", referencedColumnName="id")
     */
    private ?User $user;

    /**
     * @OneToOne(targetEntity="\Ares\User\Entity\User", fetch="EAGER")
     * @JoinColumn(name="user_two_id", referencedColumnName="id")
     */
    private ?User $friend;

    /**
     * @ORM\Column(type="integer", length=1)
     */
    private int $relation;

    /**
     * @ORM\Column(type="integer", length=11)
     */
    private int $friends_since;

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
     * @return MessengerFriendship
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
     * @return MessengerFriendship
     */
    public function setUser(?User $user): self
    {
        $this->user = $user;

        return $this;
    }

    /**
     * @return User|null
     */
    public function getFriend(): ?User
    {
        return $this->friend;
    }

    /**
     * @param User|null $friend
     *
     * @return MessengerFriendship
     */
    public function setFriend(?User $friend): self
    {
        $this->friend = $friend;

        return $this;
    }

    /**
     * @return int
     */
    public function getRelation(): int
    {
        return $this->relation;
    }

    /**
     * @param int $relation
     *
     * @return MessengerFriendship
     */
    public function setRelation(int $relation): self
    {
        $this->relation = $relation;

        return $this;
    }

    /**
     * @return int
     */
    public function getFriendsSince(): int
    {
        return $this->friends_since;
    }

    /**
     * @param int $friends_since
     *
     * @return MessengerFriendship
     */
    public function setFriendsSince(int $friends_since): self
    {
        $this->friends_since = $friends_since;

        return $this;
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
