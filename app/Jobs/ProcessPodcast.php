<?php

namespace App\Jobs;

use App\Models\Post;
use App\Models\WorkOrder;
use App\Models\WorkOrderLog;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;

class ProcessPodcast implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    public $latitude;
    public $longitude;

    /**
     * Create a new job instance.
     */
    public function __construct($latitude, $longitude)
    {
        $this->latitude = $latitude;
        $this->longitude = $longitude;
    }

    /**
     * Execute the job.
     */
    public function handle(): void
    {
        if ($this->isPointInsideRadius($this->latitude, $this->longitude, -6.967617882296342, 107.65909167298881, 100)) {
            $now = now();
            $wo = WorkOrder::create([
                'start_time' => $now
            ]);

            WorkOrderLog::insert([
                'work_order_id' => $wo->id,
                'latitude' => $this->latitude,
                'longitude' => $this->longitude,
                'created_at' => $now
            ]);
        }
    }

    /**
     * Checks if a given latitude and longitude point is inside a specified radius.
     *
     * @param float $latFrom Latitude of the point to check.
     * @param float $lonFrom Longitude of the point to check.
     * @param float $latTo Latitude of the center point.
     * @param float $lonTo Longitude of the center point.
     * @param float $radius Radius in meters (e.g., 1000 meters).
     * @return bool True if the point is inside the radius, false otherwise.
     */
    private function isPointInsideRadius($latFrom, $lonFrom, $latTo, $lonTo, $radius)
    {
        $earthRadius = 6371000; // Earth radius in meters

        // Convert degrees to radians
        $latFromRad = deg2rad($latFrom);
        $lonFromRad = deg2rad($lonFrom);
        $latToRad = deg2rad($latTo);
        $lonToRad = deg2rad($lonTo);

        // Calculate differences
        $latDelta = $latToRad - $latFromRad;
        $lonDelta = $lonToRad - $lonFromRad;

        // Haversine formula
        $angle = 2 * asin(sqrt(
            pow(sin($latDelta / 2), 2) +
                cos($latFromRad) * cos($latToRad) * pow(sin($lonDelta / 2), 2)
        ));

        // Calculate distance
        $distance = $angle * $earthRadius;

        // Check if distance is within the specified radius
        return $distance <= $radius;
    }
}
