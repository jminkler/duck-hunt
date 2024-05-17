<?php

namespace App\Jobs;

use App\Models\Duck;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;

class TriageDuck implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    public function __construct(private readonly string $duckId, private readonly int $dispatchIn = 0)
    {
    }

    public function handle(): void
    {
        try {
            $duck = Duck::findOrFail($this->duckId);

            if (! $duck->isSeriouslyInjured()) {

                MedicJob::dispatch($duck->_id)
                    ->delay(now()->addSeconds($this->dispatchIn));
                return;
            }

            DoctorJob::dispatch($duck->_id)
                ->delay(now()->addSeconds($this->dispatchIn));

        } catch (\Exception $e) {
            $this->fail($e);
        }

    }
}
