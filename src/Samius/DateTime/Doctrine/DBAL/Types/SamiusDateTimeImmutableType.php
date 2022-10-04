<?php

namespace Samius\DateTime\Doctrine\DBAL\Types;

use Doctrine\DBAL\Platforms\AbstractPlatform;
use Doctrine\DBAL\Types\ConversionException;
use Doctrine\DBAL\Types\DateTimeImmutableType;
use Samius\DateTime\Timezone;
use Samius\DateTimeImmutable;

class SamiusDateTimeImmutableType extends DateTimeImmutableType
{
    protected static ?Timezone $timezone = null;

    public function convertToPHPValue($value, AbstractPlatform $platform): ?DateTimeImmutable
    {
        if ($value === null || $value instanceof DateTimeImmutable) {
            return $value;
        }
        if ($value instanceof \DateTimeInterface) {
            return DateTimeImmutable::fromDateTime($value);
        }


        try {
            $dateTime = new DateTimeImmutable($value, static::getTimezone());
        } catch (\Exception $e) {
            throw ConversionException::conversionFailedFormat(
                $value,
                $this->getName(),
                $platform->getDateTimeFormatString()
            );
        }

        return $dateTime;
    }

    /**
     * Ready for UTC datetime. in default datetime no fixed timezone is set
     */
    protected static function getTimezone(): ?Timezone
    {
        return null;
    }

}
