<?php

namespace App\Entity;

use App\Repository\CarModelsRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: CarModelsRepository::class)]
class CarModels
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 255)]
    private ?string $model = null;

    #[ORM\Column(type: Types::TEXT)]
    private ?string $description = null;

    #[ORM\Column(type: 'float')]
    private ?float $modelPrice;

    #[ORM\Column(type: 'string', length: 255, nullable: true)]
    private ?string $photo = null;

    #[ORM\ManyToOne(targetEntity: Cars::class, inversedBy: 'carModels')]
    #[ORM\JoinColumn(name: 'car_id', referencedColumnName: 'id')]
    private ?Cars $car = null;

    /**
     * @var Collection<int, BuyHistory>
     */
    #[ORM\OneToMany(targetEntity: BuyHistory::class, mappedBy: 'model_id')]
    private Collection $buyHistories;

    public function __construct()
    {
        $this->buyHistories = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getModel(): ?string
    {
        return $this->model;
    }

    public function setModel(string $model): static
    {
        $this->model = $model;

        return $this;
    }

    public function getDescription(): ?string
    {
        return $this->description;
    }

    public function setDescription(string $description): static
    {
        $this->description = $description;

        return $this;
    }

    public function getCar(): ?Cars
    {
        return $this->car;
    }

    public function setCar(?Cars $car): static
    {
        $this->car = $car;

        return $this;
    }

    public function getPhoto(): ?string
    {
        return $this->photo;
    }

    public function setPhoto(?string $photo): self
    {
        $this->photo = $photo;
        return $this;
    }

    public function getModelPrice(): ?float
    {
        return $this->modelPrice;
    }

    public function setModelPrice(float $modelPrice): self
    {
        $this->modelPrice = $modelPrice;
        return $this;
    }

    /**
     * @return Collection<int, BuyHistory>
     */
    public function getBuyHistories(): Collection
    {
        return $this->buyHistories;
    }

    public function addBuyHistory(BuyHistory $buyHistory): static
    {
        if (!$this->buyHistories->contains($buyHistory)) {
            $this->buyHistories->add($buyHistory);
            $buyHistory->setModelId($this);
        }

        return $this;
    }

    public function removeBuyHistory(BuyHistory $buyHistory): static
    {
        if ($this->buyHistories->removeElement($buyHistory)) {
            // set the owning side to null (unless already changed)
            if ($buyHistory->getModelId() === $this) {
                $buyHistory->setModelId(null);
            }
        }

        return $this;
    }
}
