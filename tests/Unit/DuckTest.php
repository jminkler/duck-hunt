<?php

namespace Tests\Unit;

use App\Models\Duck;
use Tests\TestCase;

class DuckTest extends TestCase
{
    public function test_that_we_can_create_a_duck(): void
    {
        $sut = new Duck();
        $this->assertInstanceOf(Duck::class, $sut);
    }

    public function test_that_we_can_create_a_duck_with_name(): void
    {
        $sut = new Duck(['name' => 'Donald']);
        $this->assertInstanceOf(Duck::class, $sut);
        $this->assertEquals('Donald', $sut->name);
    }

    public function test_that_we_can_create_a_duck_with_speed(): void
    {
        $sut = new Duck(['speed' => 10]);
        $this->assertInstanceOf(Duck::class, $sut);
        $this->assertEquals(10, $sut->speed);
    }

    public function test_that_we_can_create_a_duck_with_armor(): void
    {
        $sut = new Duck(['armor' => 20]);
        $this->assertInstanceOf(Duck::class, $sut);
        $this->assertEquals(20, $sut->armor);
    }

    public function test_that_we_can_create_a_duck_with_evasiveness(): void
    {
        $sut = new Duck(['evasiveness' => 30]);
        $this->assertInstanceOf(Duck::class, $sut);
        $this->assertEquals(30, $sut->evasiveness);
    }

    public function test_that_we_can_create_a_duck_with_health(): void
    {
        $sut = new Duck(['health' => 40]);
        $this->assertInstanceOf(Duck::class, $sut);
        $this->assertEquals(40, $sut->health);
    }

    public function test_that_we_can_create_a_duck_with_all_attributes(): void
    {
        $sut = new Duck([
            'name' => 'Donald',
            'speed' => 10,
            'armor' => 20,
            'evasiveness' => 30,
            'health' => 40,
        ]);
        $this->assertInstanceOf(Duck::class, $sut);
        $this->assertEquals('Donald', $sut->name);
        $this->assertEquals(10, $sut->speed);
        $this->assertEquals(20, $sut->armor);
        $this->assertEquals(30, $sut->evasiveness);
        $this->assertEquals(40, $sut->health);
    }

    public function test_that_we_can_save_a_duck(): void
    {
        $sut = new Duck([
            'name' => 'Donald',
            'speed' => 10,
            'armor' => 20,
            'evasiveness' => 30,
            'health' => 40,
        ]);
        $this->assertTrue($sut->save());

        $this->assertDatabaseHas('ducks', ['name' => 'Donald']);
    }

    public function test_armor_base_value(): void
    {
        $sut = new Duck([
            'name' => 'Donald',
            'speed' => 10,
            'armor' => 20,
            'evasiveness' => 30,
            'health' => 40,
        ]);
        $this->assertEquals(20, $sut->armor);
    }

    public function test_armor_with_equipment(): void
    {
        $sut = new Duck([
            'name' => 'Donald',
            'speed' => 10,
            'armor' => 20,
            'evasiveness' => 30,
            'health' => 40,
            'equipment' => [
                ['type' => 'armor', 'value' => 5],
                ['type' => 'armor', 'value' => 7],
                ['type' => 'speed', 'value' => 5], // not armor
            ],
        ]);

        $this->assertEquals(32, $sut->armor);
    }
}
