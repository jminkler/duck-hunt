<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use MongoDB\Laravel\Eloquent\Model;

class Duck extends Model
{
    use HasFactory;

    public const MAX_HEALTH = 100;
    public const INJURY_THRESHOLD = 100;
    public const SERIOUS_INJURY_THRESHOLD = 50;


    protected $connection = 'mongodb';
    protected $collection = 'ducks';
    protected $fillable = ['name', 'speed', 'armor', 'evasiveness', 'health', 'equipment', 'injured', 'seriouslyInjured'];
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

    public function scopeInjured($query)
    {
        return $query->where('health', '<', self::INJURY_THRESHOLD);
    }

    public function scopeSeriouslyInjured($query)
    {
        return $query->where('health', '<', self::SERIOUS_INJURY_THRESHOLD);
    }

    public function updateInjuryStatus()
    {
        $this->injured = $this->health < self::INJURY_THRESHOLD;
        $this->seriouslyInjured = $this->health < self::SERIOUS_INJURY_THRESHOLD;
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

}
