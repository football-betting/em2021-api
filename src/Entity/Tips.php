<?php

namespace App\Entity;

use App\Repository\TipsRepository;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass=TipsRepository::class)
 */
class Tips
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $username;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $matchId;

    /**
     * @ORM\Column(type="integer")
     */
    private $tipTeam1;

    /**
     * @ORM\Column(type="integer")
     */
    private $tipTeam2;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getUsername(): ?string
    {
        return $this->username;
    }

    public function setUsername(string $username): self
    {
        $this->username = $username;

        return $this;
    }

    public function getMatchId(): ?string
    {
        return $this->matchId;
    }

    public function setMatchId(string $matchId): self
    {
        $this->matchId = $matchId;

        return $this;
    }

    public function getTipTeam1(): ?int
    {
        return $this->tipTeam1;
    }

    public function setTipTeam1(int $tipTeam1): self
    {
        $this->tipTeam1 = $tipTeam1;

        return $this;
    }

    public function getTipTeam2(): ?int
    {
        return $this->tipTeam2;
    }

    public function setTipTeam2(int $tipTeam2): self
    {
        $this->tipTeam2 = $tipTeam2;

        return $this;
    }
}
