<?php

declare(strict_types=1);

namespace Doctor\Domain\Service;

use App\Doctor\Domain\Entity\Doctor;
use App\Doctor\Domain\Service\DoctorUpdater;
use App\Doctor\Domain\ValueObject\DoctorId;
use App\Doctor\Domain\ValueObject\DoctorName;
use PHPUnit\Framework\TestCase;

final class DoctorUpdaterTest extends TestCase
{
    private DoctorUpdater $updater;

    protected function setUp(): void
    {
        $this->updater = new DoctorUpdater();
    }

    public function testItUpdatesDoctorName(): void
    {
        $doctor = Doctor::create(DoctorId::from('123'), DoctorName::from('Old Name'));

        $newName = DoctorName::from('New Name');
        $this->updater->update($doctor, $newName);

        $this->assertEquals($newName->value(), $doctor->name()->value());
    }

    public function testItMarksDoctorAsError(): void
    {
        $doctor = Doctor::create(DoctorId::from('123'), DoctorName::from('Some Name'));

        $this->updater->markError($doctor);

        $this->assertTrue($doctor->hasError());
    }

    public function testItClearsDoctorError(): void
    {
        $doctor = Doctor::create(DoctorId::from('123'), DoctorName::from('Some Name'));
        $this->updater->markError($doctor);

        $this->assertTrue($doctor->hasError());

        $this->updater->clearError($doctor);
    }
}
