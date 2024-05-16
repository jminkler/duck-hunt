<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Casts\Attribute;
use MongoDB\Laravel\Eloquent\Model;

class Duck extends Model
{
    # use HasFactory;

    protected $connection = 'mongodb';
    protected $collection = 'ducks';
    protected $fillable = ['name', 'speed', 'armor', 'evasiveness', 'health', 'equipment'];
    protected $attributes = [
        'injured' => false,
        'seriouslyInjured' => false,
    ];

    protected static function boot()
    {
        parent::boot();

        static::saving(function ($duck) {
            $duck->updateInjuryStatus();
        });

        static::updating(function ($duck) {
            $duck->updateInjuryStatus();
        });
    }

    public function updateInjuryStatus()
    {
        $this->injured = $this->health < 100;
        $this->seriouslyInjured = $this->health < 50;
    }

    public function equipment()
    {
        return $this->embedsMany(Equipment::class);
    }

    public function armor(): Attribute
    {
        return Attribute::make(
            get: fn (string $value) => $this->equipment->reduce(
                fn ($carry, $equipment) => $carry + ($equipment->type === 'armor' ? $equipment->value : 0),
                $value,
            ),
        );
    }

    public function takeDamege($damage)
    {
        $this->health -= ($damage - $this->armor);
        $this->save();
    }
}
