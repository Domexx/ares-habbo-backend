<?php declare(strict_types=1);
/**
 * Ares (https://ares.to)
 *
 * @license https://gitlab.com/arescms/ares-backend/LICENSE (MIT License)
 */

namespace Ares\Article\Entity;

use Ares\Framework\Entity\Entity;
use Ares\User\Entity\User;
use Doctrine\ORM\Mapping as ORM;
use Doctrine\ORM\Mapping\OneToOne;

/**
 * Class Article
 *
 * @package Ares\Article\Entity
 *
 * @ORM\Entity
 * @ORM\Table(name="ares_articles", uniqueConstraints={@ORM\UniqueConstraint(name="title", columns={"title"})}))
 * @ORM\Cache(usage="NONSTRICT_READ_WRITE")
 * @ORM\HasLifecycleCallbacks
 */
class Article extends Entity
{
    /**
     * @ORM\Id
     * @ORM\Column(name="id", type="integer")
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    private int $id;

    /**
     * @ORM\Column(type="string", length=150)
     */
    private string $title;

    /**
     * @ORM\Column(type="string", length=100)
     */
    private string $slug;

    /**
     * @ORM\Column(type="string", length=150)
     */
    private string $description;

    /**
     * @ORM\Column(type="string", length=200)
     */
    private string $content;

    /**
     * @ORM\Column(type="string", length=35)
     */
    private ?string $image;

    /**
     * @OneToOne(targetEntity="\Ares\User\Entity\User")
     */
    private ?User $author;

    /**
     * @ORM\Column(type="integer", columnDefinition="ENUM('0',1')")
     */
    private int $hidden;

    /**
     * @ORM\Column(type="integer", columnDefinition="ENUM('0',1')")
     */
    private int $pinned;

    /**
     * @ORM\Column(type="datetime")
     */
    protected \DateTime $created_at;

    /**
     * @ORM\Column(type="datetime", nullable = true)
     */
    protected \DateTime $updated_at;

    /**
     * Get Article id
     *
     * @return integer
     */
    public function getId(): ?int
    {
        return $this->id;
    }

    /**
     * Gets Title of News
     *
     * @return string
     */
    public function getTitle(): ?string
    {
        return $this->title;
    }

    /**
     * @param string $title
     *
     * @return Article
     */
    public function setTitle(string $title): self
    {
        $this->title = $title;

        return $this;
    }

    /**
     * @return string
     */
    public function getSlug(): string
    {
        return $this->slug;
    }

    /**
     * @param string $slug
     *
     * @return Article
     */
    public function setSlug(string $slug): self
    {
        $this->slug = $slug;

        return $this;
    }

    /**
     * Gets Description of News
     *
     * @return string
     */
    public function getDescription(): ?string
    {
        return $this->description;
    }

    /**
     * @param string $description
     *
     * @return Article
     */
    public function setDescription(string $description): self
    {
        $this->description = $description;

        return $this;
    }

    /**
     * @return string
     */
    public function getContent(): ?string
    {
        return $this->content;
    }

    /**
     * @param string $content
     *
     * @return Article
     */
    public function setContent(string $content): self
    {
        $this->content = $content;

        return $this;
    }

    /**
     * @return string
     */
    public function getImage(): ?string
    {
        return $this->image;
    }

    /**
     * @param string $image
     *
     * @return Article
     */
    public function setImage(string $image): self
    {
        $this->image = $image;

        return $this;
    }

    /**
     * @return User|null
     */
    public function getAuthor(): ?User
    {
        return $this->author;
    }

    /**
     * @param User $author
     *
     * @return Article
     */
    public function setAuthor(User $author): self
    {
        $this->author = $author;

        return $this;
    }

    /**
     * @return int
     */
    public function getHidden(): ?int
    {
        return $this->hidden;
    }

    /**
     * @param int $hidden
     *
     * @return Article
     */
    public function setHidden(int $hidden): self
    {
        $this->hidden = $hidden;

        return $this;
    }

    /**
     * @return int
     */
    public function getPinned(): int
    {
        return $this->pinned;
    }

    /**
     * @param int $pinned
     *
     * @return Article
     */
    public function setPinned(int $pinned): self
    {
        $this->pinned = $pinned;

        return $this;
    }

    /**
     * @return \DateTime
     */
    public function getCreatedAt(): \DateTime
    {
        return $this->created_at;
    }

    /**
     * @return \DateTime
     */
    public function getUpdatedAt(): \DateTime
    {
        return $this->updated_at;
    }

    /**
     * Gets triggered only on insert
     *
     * @ORM\PrePersist
     */
    public function onPrePersist()
    {
        $this->created_at = new \DateTime("now");
    }

    /**
     * Gets triggered every time on update
     *
     * @ORM\PreUpdate
     */
    public function onPreUpdate()
    {
        $this->updated_at = new \DateTime("now");
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
            'title' => $this->getTitle(),
            'slug' => $this->getSlug(),
            'description' => $this->getDescription(),
            'content' => $this->getContent(),
            'image' => $this->getImage(),
            'author' => $this->getAuthor()->toArray(),
            'hidden' => $this->getHidden(),
            'pinned' => $this->getPinned(),
            'created_at' => $this->getCreatedAt(),
            'updated_at' => $this->getUpdatedAt()
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
