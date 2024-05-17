<?php

namespace App\Jobs;

use App\Actions\Medical\Doctor;
use App\Models\Duck;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Log;

class DoctorJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    public function __construct(private readonly string $duckId)
    {
    }

    public function handle(Doctor $doctor): void
    {
        try {
            $duck = Duck::findOrFail($this->duckId);
            if (! $duck->isInjured()) {
                return;
            }

            $doctor->heal($duck);

            Log::info('Duck healed by Doctor', ['duck_id' => $duck->id]);

            if ($duck->isInjured()) {
                Log::info('Duck still injured', ['duck_id' => $duck->id]);
                $this->release(10); // Come back later to check on the duck
            }

        } catch (\Exception $e) {
            Log::error('Error healing duck', [
                'duck_id' => $this->duckId,
                'error' => $e->getMessage(),
            ]);
            $this->fail($e);
        }
    }
}
