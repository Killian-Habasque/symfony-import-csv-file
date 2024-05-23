<?php

namespace App\Entity;

use App\Repository\TripRepository;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: TripRepository::class)]
class Trip
{
    #[ORM\Id]
    #[ORM\Column]
    private ?string $id = null;

    #[ORM\ManyToOne(inversedBy: 'trips')]
    private ?Route $route = null;

    #[ORM\Column]
    private ?string $trip_headsign = null;
    
    #[ORM\Column]
    private ?string $direction_id = null;

    #[ORM\Column]
    private ?string $block_id = null;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getRoute(): ?Route
    {
        return $this->route;
    }
    public function setId($trip_id): void
    {
        $this->id = $trip_id;
    }
    public function setHeadsign($trip_headsign): void
    {
        $this->trip_headsign = $trip_headsign;
    }
    public function setDescriptionId($direction_id): void
    {
        $this->direction_id = $direction_id;
    }
    public function setBlockId($block_id): void
    {
        $this->block_id = $block_id;
    }
    public function setRoute(?Route $route): static
    {
        $this->route = $route;

        return $this;
    }
}
