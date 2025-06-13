<?php

declare(strict_types=1);

namespace Doctor\Infrastructure\Adapter;

use App\Doctor\Domain\Entity\Doctor;
use App\Doctor\Domain\ValueObject\DoctorId;
use App\Doctor\Domain\ValueObject\DoctorName;
use App\Doctor\Infrastructure\Adapter\DoctorAdapter;
use App\Doctor\Infrastructure\Persistence\Doctrine\DoctrineDoctor;
use PHPUnit\Framework\TestCase;

final class DoctorAdapterTest extends TestCase
{
    public function testFromDoctrineCreatesDomainDoctor(): void
    {
        $ormDoctor = new DoctrineDoctor("42", 'Dr. House');
        $domainDoctor = DoctorAdapter::fromDoctrine($ormDoctor);

        $this->assertInstanceOf(Doctor::class, $domainDoctor);
        $this->assertEquals(DoctorId::from('42'), $domainDoctor->id());
        $this->assertEquals(DoctorName::from('Dr. House'), $domainDoctor->name());
        $this->assertFalse($domainDoctor->hasError());
    }

    public function testFromDoctrineWithErrorMarksDomainDoctor(): void
    {
        $ormDoctor = new DoctrineDoctor("43", 'Dr. Strange');
        $ormDoctor->markError();

        $domainDoctor = DoctorAdapter::fromDoctrine($ormDoctor);

        $this->assertTrue($domainDoctor->hasError());
    }

    public function testToDoctrineCreatesDoctrineDoctor(): void
    {
        $doctor = Doctor::create(DoctorId::from('99'), DoctorName::from('Dr. Doom'));
        $orm = DoctorAdapter::toDoctrine($doctor);

        $this->assertEquals(99, $orm->getId());
        $this->assertEquals('Dr. Doom', $orm->getName());
        $this->assertFalse($orm->hasError());
    }

    public function testToDoctrineWithErrorMarksOrm(): void
    {
        $doctor = Doctor::create(DoctorId::from('100'), DoctorName::from('Dr. Error'));
        $doctor->markError();

        $orm = DoctorAdapter::toDoctrine($doctor);

        $this->assertTrue($orm->hasError());
    }
}
