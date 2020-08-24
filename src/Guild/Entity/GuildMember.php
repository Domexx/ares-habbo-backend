<?php declare(strict_types=1);
/**
 * Ares (https://ares.to)
 *
 * @license https://gitlab.com/arescms/ares-backend/LICENSE (MIT License)
 */

namespace Ares\Guild\Entity;

use Ares\Framework\Entity\Entity;
use Ares\User\Entity\User;
use Doctrine\ORM\Mapping as ORM;
use Doctrine\ORM\Mapping\JoinColumn;
use Doctrine\ORM\Mapping\OneToOne;

/**
 * Class GuildMember
 *
 * @package Ares\Guild\Entity
 *
 * @ORM\Entity
 * @ORM\Table(name="guilds_members")
 * @ORM\Cache(usage="NONSTRICT_READ_WRITE")
 */
class GuildMember extends Entity
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
    private ?User $member;

    /**
     * @OneToOne(targetEntity="\Ares\Guild\Entity\Guild", fetch="EAGER")
     * @JoinColumn(name="guild_id", referencedColumnName="id")
     */
    private ?Guild $guild;

    /**
     * @ORM\Column(type="integer", length=11)
     */
    private int $level_id;

    /**
     * @ORM\Column(type="integer", length=11)
     */
    private int $member_since;

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
     * @return GuildMember
     */
    public function setId(int $id): self
    {
        $this->id = $id;

        return $this;
    }

    /**
     * @return User|null
     */
    public function getMember(): ?User
    {
        return $this->member;
    }

    /**
     * @param User|null $member
     *
     * @return GuildMember
     */
    public function setMember(?User $member): self
    {
        $this->member = $member;

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
     * @param Guild|null $guild
     *
     * @return GuildMember
     */
    public function setGuild(?Guild $guild): self
    {
        $this->guild = $guild;

        return $this;
    }

    /**
     * @return int
     */
    public function getLevelId(): int
    {
        return $this->level_id;
    }

    /**
     * @param int $level_id
     *
     * @return GuildMember
     */
    public function setLevelId(int $level_id): self
    {
        $this->level_id = $level_id;

        return $this;
    }

    /**
     * @return int
     */
    public function getMemberSince(): int
    {
        return $this->member_since;
    }

    /**
     * @param int $member_since
     *
     * @return GuildMember
     */
    public function setMemberSince(int $member_since): self
    {
        $this->member_since = $member_since;

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
            'member' => $this->getMember()->toArray(),
            'level_id' => $this->getLevelId(),
            'member_since' => $this->getMemberSince()
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
