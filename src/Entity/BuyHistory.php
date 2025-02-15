<?php

namespace App\Entity;

use App\Repository\BuyHistoryRepository;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: BuyHistoryRepository::class)]
class BuyHistory
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column]
    private ?float $amount_paid = null;

    #[ORM\Column]
    private ?string $paypal_transaction_id = null;

    #[ORM\Column(type: Types::DATETIME_MUTABLE)]
    private ?\DateTimeInterface $Datetime_paid = null;

    #[ORM\ManyToOne(targetEntity: CarModels::class, inversedBy: 'buyHistories')]
    #[ORM\JoinColumn(name: 'model_id', referencedColumnName: 'id')]
    private ?CarModels $model_id = null;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getAmountPaid(): ?float
    {
        return $this->amount_paid;
    }

    public function setAmountPaid(float $amount_paid): static
    {
        $this->amount_paid = $amount_paid;

        return $this;
    }

    public function getPaypalTransactionId(): ?string
    {
        return $this->paypal_transaction_id;
    }

    public function setPaypalTransactionId(string $paypal_transaction_id): static
    {
        $this->paypal_transaction_id = $paypal_transaction_id;

        return $this;
    }

    public function getDatetimePaid(): ?\DateTimeInterface
    {
        return $this->Datetime_paid;
    }

    public function setDatetimePaid(\DateTimeInterface $Datetime_paid): static
    {
        $this->Datetime_paid = $Datetime_paid;

        return $this;
    }

    public function getModelId(): ?CarModels
    {
        return $this->model_id;
    }

    public function setModelId(?CarModels $model_id): static
    {
        $this->model_id = $model_id;

        return $this;
    }
}
