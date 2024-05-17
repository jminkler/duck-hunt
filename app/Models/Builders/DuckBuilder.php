<?php

namespace App\Models\Builders;

use App\Models\Duck;
use MongoDB\Laravel\Eloquent\Builder;

class DuckBuilder extends Builder
{

    public function scopeInjured($query)
    {
        return $query->where('health', '<', Duck::INJURY_THRESHOLD);
    }

    public function scopeWithHealthAt($query, float $health)
    {
        return $query->where('health', '>=', $health);
    }

    public function scopeWithSpeedAbove($query, float $speed)
    {
        return $query->where('speed', '>', $speed);
    }

    public function scopeWithTotalSpeedAbove($query, $speed)
    {
        return $query->whereRaw([
            '$expr' => [
                '$gt' => [
                    [
                        '$add' => [
                            '$speed',
                            [
                                '$reduce' => [
                                    'input' => '$equipment',
                                    'initialValue' => 0,
                                    'in' => [
                                        '$cond' => [
                                            ['$eq' => ['$$this.type', 'speed']],
                                            ['$add' => ['$$this.value', '$$value']],
                                            '$$value'
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
        return $query->where('health', '<', Duck::SERIOUS_INJURY_THRESHOLD);
    }

}
