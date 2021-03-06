<?php

namespace App\Entity;

use DateTime;
use Doctrine\ORM\Mapping as ORM;

trait Timestamps
{
    /**
     * @ORM\Column(type="datetime")
     */
    private $createdAt;

    /**
     * @ORM\Column(type="datetime")
     */
    private $updatedAt;

    /**
     * @ORM\PrePersist()
     */
    public function createdAt()
    {
        $this->createdAt = new DateTime();
        $this->updatedAt = new DateTime();
    }

    /**
     * @ORM\PreUpdate()
     */
    public function updatedAt()
    {
        $this->updatedAt = new DateTime();
    }

    public function getUpdatedAt(): ?string
    {
        return $this->updatedAt->format('Y-m-d H:i:s');
    }

    public function getCreatedAt(): ?string
    {
        return $this->createdAt->format('Y-m-d H:i:s');
    }
}
