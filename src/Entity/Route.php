<?php

namespace App\Entity;

use App\Repository\RouteRepository;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: RouteRepository::class)]
class Route
{
    #[ORM\Id]
    #[ORM\Column]
    private ?string $route_id = null;

    #[ORM\Column]
    private ?string $short_name = null;

    #[ORM\Column]
    private ?string $long_name = null;

    #[ORM\Column]
    private ?string $description = null;

    #[ORM\Column]
    private ?string $type = null;


    public function setId($route_id): void
    {
        $this->route_id = $route_id;
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
    // #[ORM\Column(mappedBy: 'route', targetEntity: Trip::class)]
    // private Collection $trips = null;
}
