<?php

namespace App\Entity;

use App\Repository\WalletRepository;
use App\Entity\DispatchRecipient;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;
use App\Validator as CustomAssert;

/**
 * @ORM\Entity(repositoryClass=WalletRepository::class)
 */
class Wallet
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=50)
     * 
     * @Assert\NotBlank
     * @Assert\Type("string")
     * @Assert\Length(
     *      min = 1,
     *      max = 50,
     *      minMessage = "Wallet title must be at least {{ limit }} characters long",
     *      maxMessage = "Wallet title cannot be longer than {{ limit }} characters"
     * )
     */
    private $title;

    /**
     * @ORM\Column(type="string", length=48, nullable=true)
     * 
     * // can be blank because we create the wallet before deriving the pubkey.
     * @CustomAssert\PublicKey
     * 
     */
    private $address;

    /**
     * @ORM\Column(type="datetime", nullable=true)
     */
    private $last_done;

    /**
     * @ORM\Column(type="dateinterval", nullable=true)
     */
    private $time_interval;

    /**
     * @ORM\Column(type="smallint")
     * 
     * @Assert\NotBlank
     * @Assert\PositiveOrZero
     */
    private $type;

    /**
     * @ORM\ManyToOne(targetEntity=User::class, inversedBy="wallets")
     * 
     * @Assert\NotBlank
     * @Assert\Type("App\Entity\User")
     */
    private $user;

    /**
     * @ORM\OneToMany(targetEntity=DispatchRecipient::class, mappedBy="wallet", orphanRemoval=true)
     * 
     * @Assert\All({
     *     @Assert\NotBlank,
     *     @Assert\Type("App\Entity\DispatchRecipient")
     * })
     * @CustomAssert\PercentSum
     */
    private $dispatchRecipients;

    public function __construct()
    {
        $this->dispatchRecipients = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getTitle(): ?string
    {
        return $this->title;
    }

    public function setTitle(string $title): self
    {
        $this->title = $title;

        return $this;
    }

    public function getAddress(): ?string
    {
        return $this->address;
    }

    public function setAddress(?string $address): self
    {
        $this->address = $address;

        return $this;
    }

    public function getLastDone(): ?\DateTimeInterface
    {
        return $this->last_done;
    }

    public function setLastDone(?\DateTimeInterface $last_done): self
    {
        $this->last_done = $last_done;

        return $this;
    }

    public function getTimeInterval(): ?\DateInterval
    {
        return $this->time_interval;
    }

    public function setTimeInterval(?\DateInterval $time_interval): self
    {
        $this->time_interval = $time_interval;

        return $this;
    }

    public function getType(): ?int
    {
        return $this->type;
    }

    public function setType(int $type): self
    {
        $this->type = $type;

        return $this;
    }

    public function getUser(): ?User
    {
        return $this->user;
    }

    public function setUser(?User $user): self
    {
        $this->user = $user;

        return $this;
    }

    /**
     * @return Collection|DispatchRecipient[]
     */
    public function getDispatchRecipients(): Collection
    {
        return $this->dispatchRecipients;
    }

    public function addDispatchRecipient(DispatchRecipient $dispatchRecipient): self
    {
        if (!$this->dispatchRecipients->contains($dispatchRecipient)) {
            $this->dispatchRecipients[] = $dispatchRecipient;
            $dispatchRecipient->setWallet($this);
        }

        return $this;
    }

    public function removeDispatchRecipient(DispatchRecipient $dispatchRecipient): self
    {
        if ($this->dispatchRecipients->removeElement($dispatchRecipient)) {
            // set the owning side to null (unless already changed)
            if ($dispatchRecipient->getWallet() === $this) {
                $dispatchRecipient->setWallet(null);
            }
        }

        return $this;
    }
}
