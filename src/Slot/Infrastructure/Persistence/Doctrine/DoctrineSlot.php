<?php

namespace App\Slot\Infrastructure\Persistence\Doctrine;

use DateTime;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity]
#[ORM\Table(name: 'slot')]
class DoctrineSlot
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column(type: 'integer')]
    private ?int $id = null;

    #[ORM\Column(type: 'integer')]
    private int $doctorId;

    #[ORM\Column(type: 'datetime')]
    private \DateTimeImmutable $start;

    #[ORM\Column(type: 'datetime')]
    private \DateTimeImmutable $end;

    #[ORM\Column(type: 'datetime')]
    private \DateTimeImmutable $createdAt;

    public function __construct(int $doctorId, \DateTimeImmutable $start, \DateTimeImmutable $end)
    {
        $this->doctorId = $doctorId;
        $this->start = $start;
        $this->end = $end;
        $this->createdAt = new \DateTimeImmutable();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getDoctorId(): int
    {
        return $this->doctorId;
    }

    public function getStart(): \DateTimeImmutable
    {
        return $this->start;
    }

    public function getEnd(): \DateTimeImmutable
    {
        return $this->end;
    }

    public function getCreatedAt(): \DateTimeImmutable
    {
        return $this->createdAt;
    }

    public function setEnd(\DateTimeImmutable $end): void
    {
        $this->end = $end;
    }

    public function isStale(): bool
    {
        return $this->createdAt < new \DateTimeImmutable('-5 minutes');
    }
}
