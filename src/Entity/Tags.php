<?php

namespace App\Entity;

use App\Repository\TagsRepository;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: TagsRepository::class)]
class Tags
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 30)]
    private ?string $tags_libelle = null;

    public function __toString(){
        return $this->tags_libelle;
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getTagsLibelle(): ?string
    {
        return $this->tags_libelle;
    }

    public function setTagsLibelle(string $tags_libelle): static
    {
        $this->tags_libelle = $tags_libelle;

        return $this;
    }
}
