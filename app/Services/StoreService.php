<?php

namespace App\Services;

use App\Models\StoreHour;
use Carbon\Carbon;

class StoreService
{
    public function getStoreStatus(): array
    {
        $now = Carbon::now();
        $currentTime = $now->format('H:i:s');

        $lunchStart = '12:00:00';
        $lunchEnd = '12:45:00';
        $isLunchBreak = $currentTime >= $lunchStart && $currentTime < $lunchEnd;

        $storeHours = StoreHour::query()
            ->where('day_of_week', $now->dayOfWeek)
            ->get();

        $isOpen = false;

        foreach ($storeHours as $hour) {
            if ($currentTime >= $hour->open_time && $currentTime < $hour->close_time) {
                $isOpen = true;
                break;
            }
        }

        if ($isOpen && !$isLunchBreak) {
            return [
                'is_open' => true,
                'next_opening' => null,
            ];
        }

        return [
            'is_open' => false,
            'next_opening' => $this->getNextOpening(),
        ];
    }

    private function getNextOpening($date = null): ?string
    {
        $now = $date ? Carbon::parse($date) : Carbon::now();
        $currentDayOfWeek = $now->dayOfWeek;
        $currentTime = $now->format('H:i:s');

        $nextOpening = StoreHour::query()
            ->where(function ($query) use ($currentDayOfWeek, $currentTime) {
                $query->where('day_of_week', '>', $currentDayOfWeek) // Future days in the week
                ->orWhere(function ($query) use ($currentDayOfWeek, $currentTime) {
                    $query->where('day_of_week', $currentDayOfWeek)
                        ->where('open_time', '>', $currentTime); // Later today
                });
            })
            ->orderByRaw("CASE
                WHEN day_of_week >= $currentDayOfWeek THEN day_of_week
                ELSE day_of_week + 7
            END")
            ->orderBy('open_time')
            ->first();

        if (!$nextOpening) {
            return null;
        }

        $daysUntilNextOpening = ($nextOpening->day_of_week - $currentDayOfWeek + 7) % 7;

        // If it's today but later, don't add extra days
        if ($daysUntilNextOpening === 0 && $nextOpening->open_time > $currentTime) {
            $nextOpeningDateTime = $now->copy()
                ->setTimeFromTimeString($nextOpening->open_time);
        } else {
            $nextOpeningDateTime = $now->copy()
                ->addDays($daysUntilNextOpening)
                ->setTimeFromTimeString($nextOpening->open_time);
        }

        return $nextOpeningDateTime->diffForHumans($now);
    }

    public function checkIfOpen($date): array
    {
        $dayOfWeek = Carbon::parse($date)->dayOfWeek;
        $storeHours = StoreHour::where('day_of_week', $dayOfWeek)->exists();
        return [
            'is_open' => $storeHours,
            'next_opening' => $storeHours ? null : $this->getNextOpening($date),
        ];
    }
}
