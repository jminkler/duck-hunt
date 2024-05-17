<?php

namespace App\Jobs;

use App\Models\Duck;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;

class MedicJob implements ShouldQueue
{
    private const HEALS_FOR = 20;

    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    public function __construct(private readonly int $duckId)
    {
        //
    }

    public function handle(): void
    {
        try {
            $duck = Duck::findOrFail($this->duckId);
            if ($duck->health >= Duck::INJURY_THRESHOLD) {
                return;
            }

            $duck->health = $this->health + self::HEALS_FOR; // bandages
            $duck->save();

            if ($duck->health < Duck::INJURY_THRESHOLD) {
                $this->release(10);
            }

        } catch (\Exception $e) {
            return;
        }
    }
}
