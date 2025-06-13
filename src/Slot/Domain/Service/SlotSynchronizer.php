<?php

declare(strict_types=1);

namespace App\Slot\Domain\Service;

use App\Slot\Domain\Entity\Slot;
use Doctrine\ORM\EntityManagerInterface;
use Psr\Log\LoggerInterface;

readonly class SlotSynchronizer
{
    public function __construct(
        private EntityManagerInterface $entityManager,
        private LoggerInterface $logger
    ) {}

    /**
     * @param Slot[] $slots
     */
    public function sync(array $slots): void
    {
        foreach ($slots as $slot) {
            $existing = $this->entityManager
                ->getRepository(Slot::class)
                ->find($slot->id()->value());

            if ($existing === null) {
                $this->logger->info(sprintf('Persisting new slot with ID %s', $slot->id()->value()));
                $this->entityManager->persist($slot);
            } else {
                $this->logger->debug(sprintf('Slot already exists with ID %s', $slot->id()->value()));
            }
        }

        $this->entityManager->flush();
    }
}
