<?php

namespace App\Entity;

use App\Repository\TournamentsRepository;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: TournamentsRepository::class)]
class Tournaments
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 30)]
    private ?string $tournament_title = null;

    #[ORM\Column(type: Types::DATE_MUTABLE)]
    private ?\DateTimeInterface $tournament_date_start = null;

    #[ORM\Column(type: Types::DATE_MUTABLE)]
    private ?\DateTimeInterface $tournament_date_end = null;

    #[ORM\Column]
    private ?bool $tournament_online = null;

    #[ORM\Column(length: 255, nullable: true)]
    private ?string $tournament_adress = null;

    #[ORM\Column(length: 100)]
    private ?string $tournament_prize = null;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getTournamentTitle(): ?string
    {
        return $this->tournament_title;
    }

    public function setTournamentTitle(string $tournament_title): static
    {
        $this->tournament_title = $tournament_title;

        return $this;
    }

    public function getTournamentDateStart(): ?\DateTimeInterface
    {
        return $this->tournament_date_start;
    }

    public function setTournamentDateStart(\DateTimeInterface $tournament_date_start): static
    {
        $this->tournament_date_start = $tournament_date_start;

        return $this;
    }

    public function getTournamentDateEnd(): ?\DateTimeInterface
    {
        return $this->tournament_date_end;
    }

    public function setTournamentDateEnd(\DateTimeInterface $tournament_date_end): static
    {
        $this->tournament_date_end = $tournament_date_end;

        return $this;
    }

    public function isTournamentOnline(): ?bool
    {
        return $this->tournament_online;
    }

    public function setTournamentOnline(bool $tournament_online): static
    {
        $this->tournament_online = $tournament_online;

        return $this;
    }

    public function getTournamentAdress(): ?string
    {
        return $this->tournament_adress;
    }

    public function setTournamentAdress(?string $tournament_adress): static
    {
        $this->tournament_adress = $tournament_adress;

        return $this;
    }

    public function getTournamentPrize(): ?string
    {
        return $this->tournament_prize;
    }

    public function setTournamentPrize(string $tournament_prize): static
    {
        $this->tournament_prize = $tournament_prize;

        return $this;
    }
}
