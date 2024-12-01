<?php

declare(strict_types=1);

namespace App\Infrastructure\Doctrine\Types;

use App\Domain;
use Doctrine\DBAL\Types\StringType;
use Doctrine\DBAL\Platforms\AbstractPlatform;

final class StateType extends StringType
{
    public const NAME = 'State';

    public function getSQLDeclaration(array $fieldDeclaration, AbstractPlatform $platform)
    {
        $enum = Domain\Data\Enum\State::class;

        $cases = array_map(
            static fn ($case) => "'{$case->name}'",
            $enum::cases()
        );

        sort($cases);

        return sprintf('ENUM(%s)', implode(', ', $cases));
    }

    public function convertToPHPValue($value, AbstractPlatform $platform)
    {
        return $value;
    }

    public function convertToDatabaseValue($value, AbstractPlatform $platform)
    {
        if (!in_array($value, Domain\Data\Enum\State::cases())) {
            throw new \InvalidArgumentException("Invalid state");
        }
        return $value;
    }

    public function getName()
    {
        return self::NAME;
    }

    public function requiresSQLCommentHint(AbstractPlatform $platform)
    {
        return true;
    }
}