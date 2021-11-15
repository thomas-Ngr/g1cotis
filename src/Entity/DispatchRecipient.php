<?php

namespace App\Entity;

use App\Repository\DispatchRecipientRepository;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass=DispatchRecipientRepository::class)
 */
class DispatchRecipient
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=48)
     */
    private $address;

    /**
     * @ORM\Column(type="smallint", nullable=true)
     */
    private $percent;

    /**
     * @ORM\ManyToOne(targetEntity=Wallet::class, inversedBy="dispatchRecipients")
     * @ORM\JoinColumn(nullable=false)
     */
    private $wallet;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getAddress(): ?string
    {
        return $this->address;
    }

    public function setAddress(string $address): self
    {
        $this->address = $address;

        return $this;
    }

    public function getPercent(): ?int
    {
        return $this->percent;
    }

    public function setPercent(?int $percent): self
    {
        $this->percent = $percent;

        return $this;
    }

    public function getWallet(): ?Wallet
    {
        return $this->wallet;
    }

    public function setWallet(?Wallet $wallet): self
    {
        $this->wallet = $wallet;

        return $this;
    }
}
