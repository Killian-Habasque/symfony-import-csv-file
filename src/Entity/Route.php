<?php

namespace App\Entity;

use App\Repository\RouteRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: RouteRepository::class)]
class Route
{
    #[ORM\Id]
    #[ORM\Column]
    private ?string $id = null;

    #[ORM\Column]
    private ?string $short_name = null;

    #[ORM\Column]
    private ?string $long_name = null;

    #[ORM\Column]
    private ?string $description = null;

    #[ORM\Column]
    private ?string $type = null;

    /**
     * @var Collection<int, Trip>
     */
    #[ORM\OneToMany(targetEntity: Trip::class, mappedBy: 'route')]
    private Collection $trips;

    public function __construct()
    {
        $this->trips = new ArrayCollection();
    }


    public function setId($route_id): void
    {
        $this->id = $route_id;
    }
    public function setShortName($short_name): void
    {
        $this->short_name = $short_name;
    }
    public function setLongName($long_name): void
    {
        $this->long_name = $long_name;
    }
    public function setDescription($description): void
    {
        $this->description = $description;
    }
    public function setType($type): void
    {
        $this->type = $type;
    }

    /**
     * @return Collection<int, Trip>
     */
    public function getTrips(): Collection
    {
        return $this->trips;
    }

    public function addTrip(Trip $trip): static
    {
        if (!$this->trips->contains($trip)) {
            $this->trips->add($trip);
            $trip->setRoute($this);
        }

        return $this;
    }

    public function removeTrip(Trip $trip): static
    {
        if ($this->trips->removeElement($trip)) {
            // set the owning side to null (unless already changed)
            if ($trip->getRoute() === $this) {
                $trip->setRoute(null);
            }
        }

        return $this;
    }

}
