<?php

namespace App\Entity;

use App\Repository\VideosRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;
use Doctrine\ORM\Mapping\InverseJoinColumn;
use Doctrine\ORM\Mapping\JoinColumn;
use Doctrine\ORM\Mapping\JoinTable;

#[ORM\Entity(repositoryClass: VideosRepository::class)]
class Videos
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 50)]
    private ?string $video_id = null;

    #[ORM\Column(length: 100)]
    private ?string $video_title = null;

    #[ORM\Column(length: 30)]
    private ?string $video_author = null;

    #[ORM\Column]
    private ?int $video_likes = null;

    #[ORM\Column(type: Types::DATETIME_MUTABLE)]
    private ?\DateTimeInterface $video_date = null;

    #[ORM\Column(length: 255)]
    private ?string $video_thumbnail = null;

    #[ORM\Column(nullable: true)]
    private ?int $video_timecode = null;

    #[JoinTable(name: 'videos_tags')]
    #[JoinColumn(name:'video_id', referencedColumnName: 'id')]
    #[InverseJoinColumn(name:'tag_id', referencedColumnName: 'id')]
    #[ORM\ManyToMany(targetEntity: Tags::class, inversedBy: 'videos')]
    private Collection $tags;

    public function __construct()
    {
        $this->tags = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getVideoId(): ?string
    {
        return $this->video_id;
    }

    public function setVideoId(string $video_id): static
    {
        $this->video_id = $video_id;

        return $this;
    }

    public function getVideoTitle(): ?string
    {
        return $this->video_title;
    }

    public function setVideoTitle(string $video_title): static
    {
        $this->video_title = $video_title;

        return $this;
    }

    public function getVideoAuthor(): ?string
    {
        return $this->video_author;
    }

    public function setVideoAuthor(string $video_author): static
    {
        $this->video_author = $video_author;

        return $this;
    }

    public function getVideoLikes(): ?int
    {
        return $this->video_likes;
    }

    public function setVideoLikes(int $video_likes): static
    {
        $this->video_likes = $video_likes;

        return $this;
    }

    public function getVideoDate(): ?\DateTimeInterface
    {
        return $this->video_date;
    }

    public function setVideoDate(\DateTimeInterface $video_date): static
    {
        $this->video_date = $video_date;

        return $this;
    }

    public function getVideoThumbnail(): ?string
    {
        return $this->video_thumbnail;
    }

    public function setVideoThumbnail(string $video_thumbnail): static
    {
        $this->video_thumbnail = $video_thumbnail;

        return $this;
    }

    public function getVideoTimecode(): ?int
    {
        return $this->video_timecode;
    }

    public function setVideoTimecode(?int $video_timecode): static
    {
        $this->video_timecode = $video_timecode;

        return $this;
    }

    /**
     * @return Collection<int, Tags>
     */
    public function getTags(): Collection
    {
        return $this->tags;
    }

    public function addTag(Tags $tag): static
    {
        if (!$this->tags->contains($tag)) {
            $this->tags->add($tag);
        }

        return $this;
    }

    public function removeTag(Tags $tag): static
    {
        $this->tags->removeElement($tag);

        return $this;
    }
}
