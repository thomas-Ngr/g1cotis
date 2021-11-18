<?php

namespace App\Entity;

use App\Repository\DispatchRecipientRepository;
use Doctrine\ORM\Mapping as ORM;

use Symfony\Component\Validator\Constraints as Assert;
use App\Validator as CustomAssert;

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
     * 
     * @Assert\NotBlank
     * @CustomAssert\PublicKey
     */
    private $address;

    /**
     * @ORM\Column(type="float", nullable=true)
     * 
     * @Assert\NotBlank
     * @CustomAssert\Percent
     */
    private $percent;

    /**
     * @ORM\ManyToOne(targetEntity=Wallet::class, inversedBy="dispatchRecipients")
     * @ORM\JoinColumn(nullable=false)
     * 
     * @Assert\NotBlank
     * @Assert\Type("App\Entity\Wallet")
     * 
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

    public function getPercent(): ?float
    {
        return $this->percent;
    }

    //public function setPercent(?int $percent): self
    public function setPercent(float $percent): self
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
