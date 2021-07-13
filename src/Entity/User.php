<?php

namespace App\Entity;

use Cocur\Slugify\Slugify;
use Doctrine\ORM\Mapping as ORM;
use App\Repository\UserRepository;
use Doctrine\Common\Collections\Collection;
use Doctrine\Common\Collections\ArrayCollection;
use Symfony\Component\Security\Core\User\UserInterface;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;


/**
 * @ORM\Entity(repositoryClass=UserRepository::class)
 * @ORM\HasLifecycleCallbacks()
 * @UniqueEntity(fields={"email"}, message="There is already an account with this email")
 */
class User implements UserInterface
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=180, unique=true)
     */
    private $email;

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
     * @ORM\Column(type="string", length=255)
     */
    private $firstName;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $lastName;

    /**
     * @ORM\Column(type="text")
     */
    private $description;

    /**
     * @ORM\OneToMany(targetEntity=Article::class, mappedBy="author")
     */
    private $articles;

    /**
     * @ORM\Column(type="boolean")
     */
    private $isVerified = true;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $slug;

    /**
     * @ORM\OneToMany(targetEntity=CommentArticle::class, mappedBy="author")
     */
    private $commentArticles;

    /**
     * @ORM\OneToMany(targetEntity=Une::class, mappedBy="author")
     */
    private $unes;

    /**
     * @ORM\OneToMany(targetEntity=Recette::class, mappedBy="author")
     */
    private $recettes;

    /**
     * @ORM\OneToMany(targetEntity=CommentRecette::class, mappedBy="author")
     */
    private $commentRecettes;

    public function getFullName() {
        return $this->getFirstName().' '.$this->getLastName();
    }

    public function __toString(): string
    {
        return (string) $this->getFullName();
    }

    /**
     * Permet d'initialiser le slug ! Utilisation de slugify pour transformer une chaine de caractères en slug
     *
     * @ORM\PrePersist
     * @ORM\PreUpdate
     * 
     * @return void
     */
    public function initializeSlug() {
        if(empty($this->slug)) {
            $slugify = new Slugify();
            $this->slug = $slugify->slugify($this->firstName . ' ' . $this->lastName);
        }
    }

    public function __construct()
    {
        $this->articles = new ArrayCollection();
        $this->commentArticles = new ArrayCollection();
        $this->unes = new ArrayCollection();
        $this->recettes = new ArrayCollection();
        $this->commentRecettes = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getEmail(): ?string
    {
        return $this->email;
    }

    public function setEmail(string $email): self
    {
        $this->email = $email;

        return $this;
    }

    /**
     * A visual identifier that represents this user.
     *
     * @see UserInterface
     */
    public function getUsername(): string
    {
        return (string) $this->email;
    }

    /**
     * @see UserInterface
     */
    public function getRoles(): array
    {
        $roles = $this->roles;
        // guarantee every user at least has ROLE_USER
        $roles[] = 'ROLE_USER';

        return array_unique($roles);
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
        return $this->password;
    }

    public function setPassword(string $password): self
    {
        $this->password = $password;

        return $this;
    }

    /**
     * Returning a salt is only needed, if you are not using a modern
     * hashing algorithm (e.g. bcrypt or sodium) in your security.yaml.
     *
     * @see UserInterface
     */
    public function getSalt(): ?string
    {
        return null;
    }

    /**
     * @see UserInterface
     */
    public function eraseCredentials()
    {
        // If you store any temporary, sensitive data on the user, clear it here
        // $this->plainPassword = null;
    }

    public function getFirstName(): ?string
    {
        return $this->firstName;
    }

    public function setFirstName(string $firstName): self
    {
        $this->firstName = $firstName;

        return $this;
    }

    public function getLastName(): ?string
    {
        return $this->lastName;
    }

    public function setLastName(string $lastName): self
    {
        $this->lastName = $lastName;

        return $this;
    }

    public function getDescription(): ?string
    {
        return $this->description;
    }

    public function setDescription(string $description): self
    {
        $this->description = $description;

        return $this;
    }

    /**
     * @return Collection|Article[]
     */
    public function getArticles(): Collection
    {
        return $this->articles;
    }

    public function addArticle(Article $article): self
    {
        if (!$this->articles->contains($article)) {
            $this->articles[] = $article;
            $article->setAuthor($this);
        }

        return $this;
    }

    public function removeArticle(Article $article): self
    {
        if ($this->articles->removeElement($article)) {
            // set the owning side to null (unless already changed)
            if ($article->getAuthor() === $this) {
                $article->setAuthor(null);
            }
        }

        return $this;
    }

    public function isVerified(): bool
    {
        return $this->isVerified;
    }

    public function setIsVerified(bool $isVerified): self
    {
        $this->isVerified = $isVerified;

        return $this;
    }

    public function getSlug(): ?string
    {
        return $this->slug;
    }

    public function setSlug(string $slug): self
    {
        $this->slug = $slug;

        return $this;
    }

    /**
     * @return Collection|CommentArticle[]
     */
    public function getCommentArticles(): Collection
    {
        return $this->commentArticles;
    }

    public function addCommentArticle(CommentArticle $commentArticle): self
    {
        if (!$this->commentArticles->contains($commentArticle)) {
            $this->commentArticles[] = $commentArticle;
            $commentArticle->setAuthor($this);
        }

        return $this;
    }

    public function removeCommentArticle(CommentArticle $commentArticle): self
    {
        if ($this->commentArticles->removeElement($commentArticle)) {
            // set the owning side to null (unless already changed)
            if ($commentArticle->getAuthor() === $this) {
                $commentArticle->setAuthor(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection|Une[]
     */
    public function getUnes(): Collection
    {
        return $this->unes;
    }

    public function addUne(Une $une): self
    {
        if (!$this->unes->contains($une)) {
            $this->unes[] = $une;
            $une->setAuthor($this);
        }

        return $this;
    }

    public function removeUne(Une $une): self
    {
        if ($this->unes->removeElement($une)) {
            // set the owning side to null (unless already changed)
            if ($une->getAuthor() === $this) {
                $une->setAuthor(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection|Recette[]
     */
    public function getRecettes(): Collection
    {
        return $this->recettes;
    }

    public function addRecette(Recette $recette): self
    {
        if (!$this->recettes->contains($recette)) {
            $this->recettes[] = $recette;
            $recette->setAuthor($this);
        }

        return $this;
    }

    public function removeRecette(Recette $recette): self
    {
        if ($this->recettes->removeElement($recette)) {
            // set the owning side to null (unless already changed)
            if ($recette->getAuthor() === $this) {
                $recette->setAuthor(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection|CommentRecette[]
     */
    public function getCommentRecettes(): Collection
    {
        return $this->commentRecettes;
    }

    public function addCommentRecette(CommentRecette $commentRecette): self
    {
        if (!$this->commentRecettes->contains($commentRecette)) {
            $this->commentRecettes[] = $commentRecette;
            $commentRecette->setAuthor($this);
        }

        return $this;
    }

    public function removeCommentRecette(CommentRecette $commentRecette): self
    {
        if ($this->commentRecettes->removeElement($commentRecette)) {
            // set the owning side to null (unless already changed)
            if ($commentRecette->getAuthor() === $this) {
                $commentRecette->setAuthor(null);
            }
        }

        return $this;
    }
}
