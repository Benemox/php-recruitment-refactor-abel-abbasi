<?php

declare(strict_types=1);

namespace App\Doctor\Infrastructure\Adapter;

use App\Doctor\Domain\Entity\Doctor;
use App\Doctor\Domain\ValueObject\DoctorId;
use App\Doctor\Domain\ValueObject\DoctorName;
use App\Doctor\Infrastructure\Persistence\Doctrine\DoctrineDoctor;


final class DoctorAdapter
{
    public static function fromDoctrine(DoctrineDoctor $orm): Doctor
    {
        $doctor = Doctor::create(
            DoctorId::from($orm->getId()),
            DoctorName::from($orm->getName())
        );

        if ($orm->hasError()) {
            $doctor->markError();
        }

        return $doctor;
    }

    public static function toDoctrine(Doctor $domain): DoctrineDoctor
    {
        $orm = new DoctrineDoctor(
            $domain->id()->value(),
            $domain->name()->value()
        );

        if ($domain->hasError()) {
            $orm->markError();
        }

        return $orm;
    }
}
