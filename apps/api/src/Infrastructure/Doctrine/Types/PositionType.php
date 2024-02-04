<?php

declare(strict_types=1);

namespace App\Infrastructure\Doctrine\Types;

use App\Domain\Data\ValueObject\Position;
use Doctrine\DBAL\Platforms\AbstractPlatform;
use Doctrine\DBAL\Types\JsonType;
use RuntimeException;

final class PositionType extends JsonType
{
    public const NAME = 'position';

    public function getName(): string
    {
        return self::NAME;
    }

    public function convertToPHPValue($value, AbstractPlatform $platform): Position
    {
        $position = json_decode($value, true);

        return new Position($position['latitude'], $position['longitude']);
    }

    public function convertToDatabaseValue($value, AbstractPlatform $platform): string
    {
        if (false === $value instanceof Position) {
            throw new RuntimeException('PositionType can only handle instance of Position');
        }

        return (string)json_encode(['latitude' => $value->latitude, 'longitude' => $value->longitude]);
    }
}