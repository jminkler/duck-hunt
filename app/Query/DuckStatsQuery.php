<?php

namespace App\Query;

use App\Models\Duck;
use Illuminate\Support\Facades\Cache;

class DuckStatsQuery
{
    public const STATS_CACHE_KEY = 'duck_stats';
    public const STATS_CACHE_TTL = 60;

    public function stats(): array
    {
        return Cache::remember(
            self::STATS_CACHE_KEY,
            self::STATS_CACHE_TTL,
            function () {
                return $this->calculateStats();
            }
        );
    }

    private function calculateStats(): array
    {
        $stats = Duck::raw(function ($collection) {
            return $collection->aggregate([
                [
                    '$facet' => [
                        'totalDucks' => [
                            [
                                '$count' => 'count'
                            ]
                        ],
                        'injuredDucks' => [
                            [
                                '$match' => ['health' => ['$lt' => Duck::INJURY_THRESHOLD]]
                            ],
                            [
                                '$count' => 'count'
                            ]
                        ],
                        'seriouslyInjuredDucks' => [
                            [
                                '$match' => ['health' => ['$lt' => Duck::SERIOUS_INJURY_THRESHOLD]]
                            ],
                            [
                                '$count' => 'count'
                            ]
                        ],
                        'averageSpeed' => [
                            [
                                '$group' => [
                                    '_id' => null,
                                    'averageSpeed' => ['$avg' => '$speed']
                                ]
                            ]
                        ],
                        'evasivenessEquipment' => [
                            [
                                '$unwind' => '$equipment'
                            ],
                            [
                                '$match' => ['equipment.type' => 'evasiveness']
                            ],
                            [
                                '$count' => 'count'
                            ]
                        ]
                    ]
                ]
            ]);
        })->toArray();

        // @todo Could do StatsResponse DTO here
        return [
            'totalDucks'           => $stats[0]['totalDucks'][0]['count'] ?? 0,
            'injuredDucks'         => $stats[0]['injuredDucks'][0]['count'] ?? 0,
            'seriouslyInjuredDucks' => $stats[0]['seriouslyInjuredDucks'][0]['count'] ?? 0,
            'averageSpeed'         => $stats[0]['averageSpeed'][0]['averageSpeed'] ?? 0,
            'evasivenessEquipment' => $stats[0]['evasivenessEquipment'][0]['count'] ?? 0,
        ];
    }
}
