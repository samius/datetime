<?php
namespace Samius\DateTime\Doctrine\DBAL\Types;
use Samius\DateTimeImmutable;
use Doctrine\DBAL\Platforms\AbstractPlatform;
use function date_create_immutable;

class SamiusDateTimeImmutableType extends Types\DateTimeImmutableType
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
