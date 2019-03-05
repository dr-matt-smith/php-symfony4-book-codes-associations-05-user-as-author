<?php

namespace App\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Security\Core\User\UserInterface;

/**
 * @ORM\Entity(repositoryClass="App\Repository\UserRepository")
 */
class User implements UserInterface
{
    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=180, unique=true)
     */
    private $username;

    /**
     * @ORM\Column(type="json")
     */
    private $roles = [];

    /**
     * @var string The hashed password
     * @ORM\Column(type="string")
     */
    private $password;

    /**
     * @ORM\OneToMany(targetEntity="App\Entity\NewsItem", mappedBy="author")
     */
    private $newsItems;

    public function __construct()
    {
        $this->newsItems = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    /**
     * A visual identifier that represents this user.
     *
     * @see UserInterface
     */
    public function getUsername(): string
    {
        return (string) $this->username;
    }

    public function setUsername(string $username): self
    {
        $this->username = $username;

        return $this;
    }

    /**
     * @see UserInterface
     */
    public function getRoles(): array
    {
        return $this->roles;
    }

    public function setRoles(array $roles): self
    {
        $this->roles = $roles;

        return $this;
    }

    /**
     * @see UserInterface
     */
    public function getPassword(): string
    {
        return (string) $this->password;
    }

    public function setPassword(string $password): self
    {
        $this->password = $password;

        return $this;
    }

    /**
     * @see UserInterface
     */
    public function getSalt()
    {
        // not needed when using the "bcrypt" algorithm in security.yaml
    }

    /**
     * @see UserInterface
     */
    public function eraseCredentials()
    {
        // If you store any temporary, sensitive data on the user, clear it here
        // $this->plainPassword = null;
    }

    /**
     * @return Collection|NewsItem[]
     */
    public function getNewsItems(): Collection
    {
        return $this->newsItems;
    }

    public function addNewsItem(NewsItem $newsItem): self
    {
        if (!$this->newsItems->contains($newsItem)) {
            $this->newsItems[] = $newsItem;
            $newsItem->setAuthor($this);
        }

        return $this;
    }

    public function removeNewsItem(NewsItem $newsItem): self
    {
        if ($this->newsItems->contains($newsItem)) {
            $this->newsItems->removeElement($newsItem);
            // set the owning side to null (unless already changed)
            if ($newsItem->getAuthor() === $this) {
                $newsItem->setAuthor(null);
            }
        }

        return $this;
    }

    public function __toString()
    {
        return (string)$this->username;
    }
}
