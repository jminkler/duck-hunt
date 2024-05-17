<?php

namespace Tests\Feature\Actions\Medical;

use App\Actions\Medical\Doctor;
use App\Models\Duck;
use Tests\TestCase;

class DoctorTest extends TestCase
{
    public function test_heal_method_heals_duck_for_40()
    {
        # Arrange
        $duck = Duck::factory()->create([
            'health' => 10,
        ]);

        # Act
        $doctor = new Doctor();
        $doctor->heal($duck);

        # Assert
        $this->assertEquals(10 + Doctor::HEALS_FOR, $duck->fresh()->health);
    }

    public function test_heal_method_heals_duck_to_max_of_100()
    {
        # Arrange
        $duck = Duck::factory()->create([
            'health' => 90,
        ]);

        # Act
        $sut = new Doctor();
        $sut->heal($duck);

        # Assert
        $this->assertEquals(Duck::MAX_HEALTH, $duck->fresh()->health);
    }
}
