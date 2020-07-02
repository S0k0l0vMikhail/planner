<?php

namespace App\Entity;

use App\Repository\DepartmentRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

/**
 * Сущность отдела организации
 *
 * @ORM\Entity(repositoryClass=DepartmentRepository::class)
 * @ORM\HasLifecycleCallbacks()
 */
class Department
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
     * @ORM\ManyToOne(targetEntity=Organization::class, cascade={"persist"})
     * @ORM\JoinColumn(nullable=false)
     */
    private $organization;

    /**
     * @ORM\Column(type="integer")
     */
    private $organizationId;

    /**
     * @ORM\ManyToMany(targetEntity=User::class, mappedBy="departments")
     */
    private $users;

    /**
     * @ORM\ManyToOne(targetEntity=User::class)
     */
    private $masterUser;

    public function __construct()
    {
        $this->users = new ArrayCollection();
    }

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

    public function getOrganization(): ?Organization
    {
        return $this->organization;
    }

    public function setOrganization(?Organization $organization): self
    {
        $this->organization = $organization;

        return $this;
    }

    public function getOrganizationId(): ?int
    {
        return $this->organizationId;
    }

    /**
     * @return Collection|User[]
     */
    public function getUsers(): Collection
    {
        return $this->users;
    }

    public function addUser(User $user): self
    {
        if (!$this->users->contains($user)) {
            $this->users[] = $user;
            $user->addDepartment($this);
        }

        return $this;
    }

    public function removeUser(User $user): self
    {
        if ($this->users->contains($user)) {
            $this->users->removeElement($user);
            $user->removeDepartment($this);
        }

        return $this;
    }

    public function getMasterUser(): ?User
    {
        return $this->masterUser;
    }

    public function setMasterUser(?User $masterUser): self
    {
        $this->masterUser = $masterUser;

        return $this;
    }
}
