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
    protected $hidden = ['_id', 'created_at', 'updated_at', 'evasiveness', 'armor'];

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

    // Really wanted to pull the scopes out of here but was not working with mongodb Builders and Eloquent

    public function scopeInjured($query)
    {
        return $query->where('health', '<', self::INJURY_THRESHOLD);
    }

    public function scopeWithHealthAt($query, float $health)
    {
        return $query->where('health', '>=', $health);
    }

    public function scopeWithSpeedAbove($query, float $speed)
    {
        return $query->where('speed', '>', $speed);
    }

    /**
     * Filter ducks by total speed (speed + equipment speed reduced) being above a certain value
     */
    public function scopeWithTotalSpeedAbove($query, $speed)
    {
        return $query->whereRaw([
            '$expr' => [
                // $speed + sum of equipment speed > $speed
                '$gt' => [
                    [
                        '$add' => [
                            '$speed',
                            [
                                // reduce equipment array to sum of speed equipment
                                '$reduce' => [
                                    'input' => '$equipment',
                                    'initialValue' => 0,
                                    'in' => [
                                        // if equipment is speed, add value to total, else carry value
                                        '$cond' => [
                                            ['$eq' => ['$$this.type', 'speed']],
                                            ['$add' => ['$$this.value', '$$value']],
                                            '$$value' // carry value
                                        ]
                                    ]
                                ]
                            ]
                        ]
                    ],
                    $speed
                ]
            ]
        ]);
    }

    public function scopeCreatedAfter($query, $date)
    {
        return $query->where('created_at', '>', $date);
    }

    public function scopeWithEquipmentType($query, $equipmentType)
    {
        return $query->where('equipment.type', $equipmentType);
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

    public function health(): Attribute
    {
        return Attribute::make(
            get: fn (string $value) => $value,
            // Ensure health is never more than MAX_HEALTH or less than 0
            set: fn (int $value) => min(max($value, 0), self::MAX_HEALTH),
        );
    }

    public function isInjured(): bool
    {
        return $this->health < self::INJURY_THRESHOLD;
    }

    public function isSeriouslyInjured(): bool
    {
        return $this->health < self::SERIOUS_INJURY_THRESHOLD;
    }

    /**
     * Take damage and update health
     * Returns the total damage taken after armor reduction, does not account for min/max health
     *
     * @param int $damage
     * @return int
     */
    public function takeDamage(int $damage): int
    {
        $damageTaken = max(0, $damage - $this->armor);
        $this->health -= $damageTaken;
        $this->save();

        return $damageTaken;
    }

}
