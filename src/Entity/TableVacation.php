<?php

namespace App\Entity;

use App\Repository\TableVacationRepository;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass=TableVacationRepository::class)
 * @ORM\Table(name="table_vacation", options={"comment":"График отпусков"});
 * @ORM\HasLifecycleCallbacks()
 */
class TableVacation
{
    use Timestamps;

    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $name;

    /**
     * @ORM\Column(type="smallint")
     */
    private $year;

    /**
     * @ORM\ManyToOne(targetEntity=User::class, inversedBy="tableVacations")
     * @ORM\JoinColumn(nullable=false)
     */
    private $user;

    /**
     * @ORM\Column(type="boolean", options={"default":false})
     */
    private $deleted = false;

    /**
     * @ORM\Column(type="datetime", nullable=true, options={"comment":"Дата утверждения графика"})
     */
    private $approvedAt;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getName(): ?string
    {
        return $this->name;
    }

    public function setName(string $name): self
    {
        $this->name = $name;

        return $this;
    }

    public function getYear(): ?int
    {
        return $this->year;
    }

    public function setYear(int $year): self
    {
        $this->year = $year;

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

    public function getDeleted(): ?bool
    {
        return $this->deleted;
    }

    public function setDeleted(bool $deleted): self
    {
        $this->deleted = $deleted;

        return $this;
    }

    public function getApprovedAt(): ?\DateTimeInterface
    {
        return $this->approvedAt;
    }

    public function setApprovedAt(?\DateTimeInterface $approvedAt): self
    {
        $this->approvedAt = $approvedAt;

        return $this;
    }
}
