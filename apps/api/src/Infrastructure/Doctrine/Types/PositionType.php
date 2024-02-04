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
}