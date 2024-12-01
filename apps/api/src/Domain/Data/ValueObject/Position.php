<?php

declare(strict_types=1);

namespace App\Domain\Data\ValueObject;

final readonly class Position
{
    private float $latitude;

    private float $longitude;

    public function __construct(float $latitude, float $longitude)
    {
        if ($latitude < -90 || $latitude > 90) {
            throw new \Exception("A latitude must be between -90.0 and +90.0, {$latitude} given.");
        }

        if ($longitude < -180 || $longitude > 180) {
            throw new \Exception("A longitude must be between -180.0 and +180.0, {$longitude} given.");
        }

        $this->latitude  = $latitude;
        $this->longitude = $longitude;
    }

    public function getLatitude(): float
    {
        return $this->latitude;
    }

    public function getLongitude(): float
    {
        return $this->longitude;
    }
}