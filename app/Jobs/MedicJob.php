<?php

namespace App\Jobs;

use App\Actions\Medical\Medic;
use App\Models\Duck;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Support\Facades\Log;

class MedicJob implements ShouldQueue
{
    private const HEALS_FOR = 20;

    use Dispatchable, InteractsWithQueue, Queueable;

    public function __construct(private readonly string $duckId)
    {
    }

    public function handle(Medic $medic): void
    {
        try {
            $duck = Duck::findOrFail($this->duckId);
            if (! $duck->isInjured()) {
                return;
            }

            $medic->heal($duck);
            Log::info('Duck healed by medic', ['duck_id' => $duck->id]);

            if ($duck->isInjured()) {
                Log::info('Duck still injured', ['duck_id' => $duck->id]);
                $this->release(10); // Come back later to check on the duck
            }

        } catch (\Exception $e) {
            return;
        }
    }
}
