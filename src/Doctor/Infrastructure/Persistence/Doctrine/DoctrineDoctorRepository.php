<?php

declare(strict_types=1);

namespace App\Doctor\Infrastructure\Persistence\Doctrine;

use App\Doctor\Domain\Entity\Doctor;
use App\Doctor\Domain\Repository\DoctorRepositoryInterface;
use App\Doctor\Domain\ValueObject\DoctorId;
use App\Doctor\Infrastructure\Adapter\DoctorAdapter;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\ORM\EntityRepository;

final class DoctrineDoctorRepository implements DoctorRepositoryInterface
{
    private EntityRepository $repository;

    public function __construct(
        private EntityManagerInterface $entityManager
    )
    {
        $this->repository = $this->entityManager->getRepository(DoctrineDoctor::class);
    }

    public function findById(DoctorId $id): ?Doctor
    {
        /** @var DoctrineDoctor|null $ormDoctor */
        $ormDoctor = $this->repository->find($id->value());

        return $ormDoctor ? DoctorAdapter::fromDoctrine($ormDoctor) : null;
    }

    public function save(Doctor $doctor): void
    {
        $ormDoctor = DoctorAdapter::toDoctrine($doctor);
        $this->entityManager->persist($ormDoctor);
        $this->entityManager->flush();
    }

    public function remove(Doctor $doctor): void
    {
        // TODO: Implement remove() method.
    }
}
