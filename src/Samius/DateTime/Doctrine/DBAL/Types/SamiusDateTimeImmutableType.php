<?php
namespace Samius\DateTime\Doctrine\DBAL\Types;
use Doctrine\DBAL\Types\DateTimeImmutableType;
use Samius\DateTimeImmutable;
use Doctrine\DBAL\Platforms\AbstractPlatform;

class SamiusDateTimeImmutableType extends DateTimeImmutableType
{
    /**
     * {@inheritdoc}
     */
    public function convertToPHPValue($value, AbstractPlatform $platform)
    {
        if ($value === null || $value instanceof DateTimeImmutable) {
            return $value;
        }

        $dateTime = DateTimeImmutable::createFromFormat($platform->getDateTimeFormatString(), $value);

        if (! $dateTime) {
            throw \Doctrine\DBAL\Types\ConversionException::conversionFailedFormat(
                $value,
                $this->getName(),
                $platform->getDateTimeFormatString()
            );
        }

        return $dateTime;
    }
}
