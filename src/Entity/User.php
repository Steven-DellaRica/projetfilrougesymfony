<?php

namespace App\Entity;

use App\Repository\UserRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Doctrine\ORM\Mapping\InverseJoinColumn;
use Doctrine\ORM\Mapping\JoinColumn;
use Doctrine\ORM\Mapping\JoinTable;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;
use Symfony\Component\Security\Core\User\PasswordAuthenticatedUserInterface;
use Symfony\Component\Security\Core\User\UserInterface;
use Symfony\Component\Validator\Constraints\Unique;

#[ORM\Entity(repositoryClass: UserRepository::class)]
#[UniqueEntity(fields: ['username'], message: 'There is already an account with this username')]
class User implements UserInterface, PasswordAuthenticatedUserInterface
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 180, unique: true)]
    private ?string $username = null;

    #[ORM\Column]
    private array $roles = [];

    /**
     * @var string The hashed password
     */
    #[ORM\Column]
    private ?string $password = null;

    #[ORM\Column(length: 100, unique: true)]
    private ?string $email = null;

    #[ORM\Column(length: 255, nullable: true)]
    private ?string $user_profile_picture = null;

    #[ORM\Column(type: 'boolean')]
    private $isVerified = false;
    /**
     * Many Users have many videos likes
     * @var Collection<int,Videos>
     */
    #[JoinTable(name: 'user_likes')]
    #[JoinColumn(name: 'user_id', referencedColumnName: 'id')]
    #[InverseJoinColumn(name: 'videos_id', referencedColumnName: 'id')]
    #[ORM\ManyToMany(targetEntity: Videos::class, inversedBy: 'user')]
    private Collection $video_likes;

    #[JoinTable(name: 'user_favorites')]
    #[JoinColumn(name: 'user_id', referencedColumnName: 'id')]
    #[InverseJoinColumn(name: 'videos_id', referencedColumnName: 'id')]
    #[ORM\ManyToMany(targetEntity: Videos::class, inversedBy: 'user')]
    private Collection $video_favorites;

    #[ORM\OneToMany(mappedBy: 'video_user_poster', targetEntity: Videos::class)]
    private Collection $videos;

    public function __construct()
    {
        $this->video_likes = new ArrayCollection();
        $this->video_favorites = new ArrayCollection();
        $this->videos = new ArrayCollection();
    }

    public function __toString(){
        return $this->username;
    }
    
    public function getId(): ?int
    {
        return $this->id;
    }

    public function getUsername(): ?string
    {
        return $this->username;
    }

    public function setUsername(string $username): static
    {
        $this->username = $username;

        return $this;
    }

    /**
     * A visual identifier that represents this user.
     *
     * @see UserInterface
     */
    public function getUserIdentifier(): string
    {
        return (string) $this->username;
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

    public function setRoles(array $roles): static
    {
        $this->roles = $roles;

        return $this;
    }

    /**
     * @see PasswordAuthenticatedUserInterface
     */
    public function getPassword(): string
    {
        return $this->password;
    }

    public function setPassword(string $password): static
    {
        $this->password = $password;

        return $this;
    }

    /**
     * @see UserInterface
     */
    public function eraseCredentials(): void
    {
        // If you store any temporary, sensitive data on the user, clear it here
        // $this->plainPassword = null;
    }

    public function getEmail(): ?string
    {
        return $this->email;
    }

    public function setEmail(string $email): static
    {
        $this->email = $email;

        return $this;
    }

    public function getUserProfilePicture(): ?string
    {
        return $this->user_profile_picture;
    }

    public function setUserProfilePicture(?string $user_profile_picture): static
    {
        $this->user_profile_picture = $user_profile_picture;

        return $this;
    }

    public function isVerified(): bool
    {
        return $this->isVerified;
    }

    public function setIsVerified(bool $isVerified): static
    {
        $this->isVerified = $isVerified;

        return $this;
    }

    /**
     * @return Collection<int, Videos>
     */
    public function getVideoLikes(): Collection
    {
        return $this->video_likes;
    }

    public function addVideoLike(Videos $videoLike): static
    {
        if (!$this->video_likes->contains($videoLike)) {
            $this->video_likes->add($videoLike);
        }

        return $this;
    }

    public function removeVideoLike(Videos $videoLike): static
    {
        $this->video_likes->removeElement($videoLike);

        return $this;
    }

    /**
     * @return Collection<int, Videos>
     */
    public function getVideoFavorites(): Collection
    {
        return $this->video_favorites;
    }

    public function addVideoFavorite(Videos $videoFavorite): static
    {
        if (!$this->video_favorites->contains($videoFavorite)) {
            $this->video_favorites->add($videoFavorite);
        }

        return $this;
    }

    public function removeVideoFavorite(Videos $videoFavorite): static
    {
        $this->video_favorites->removeElement($videoFavorite);

        return $this;
    }

    /**
     * @return Collection<int, Videos>
     */
    public function getVideos(): Collection
    {
        return $this->videos;
    }

    public function addVideo(Videos $video): static
    {
        if (!$this->videos->contains($video)) {
            $this->videos->add($video);
            $video->setVideoUserPoster($this);
        }

        return $this;
    }

    public function removeVideo(Videos $video): static
    {
        if ($this->videos->removeElement($video)) {
            // set the owning side to null (unless already changed)
            if ($video->getVideoUserPoster() === $this) {
                $video->setVideoUserPoster(null);
            }
        }

        return $this;
    }
}
