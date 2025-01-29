<?php

namespace App\Entity;

use App\Repository\CarsRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: CarsRepository::class)]
class Cars
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 255)]
    private ?string $brand = null;

    /**
     * @var Collection<int, CarModels>
     */
    #[ORM\OneToMany(targetEntity: CarModels::class, mappedBy: 'car', cascade: ['persist', 'remove'])]
    private Collection $carModels;

    public function __construct()
    {
        $this->carModels = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getBrand(): ?string
    {
        return $this->brand;
    }

    public function setBrand(string $brand): static
    {
        $this->brand = $brand;

        return $this;
    }

    /**
     * @return Collection<int, CarModels>
     */
    public function getCarModels(): Collection
    {
        return $this->carModels;
    }

    public function addCarModel(CarModels $carModel): static
    {
        if (!$this->carModels->contains($carModel)) {
            $this->carModels->add($carModel);
            $carModel->setCar($this);
        }

        return $this;
    }

    public function removeCarModel(CarModels $carModel): static
    {
        if ($this->carModels->removeElement($carModel)) {
            // устанавливаем владельца на null (если не было изменено)
            if ($carModel->getCar() === $this) {
                $carModel->setCar(null);
            }
        }

        return $this;
    }
}
