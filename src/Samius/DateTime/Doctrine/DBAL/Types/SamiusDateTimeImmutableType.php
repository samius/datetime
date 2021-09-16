<?php
namespace Samius\DateTime\Doctrine\DBAL\Types;
use Doctrine\DBAL\Types\DateTimeImmutableType;
use Samius\DateTime\Timezone;
use Samius\DateTimeImmutable;
use Doctrine\DBAL\Platforms\AbstractPlatform;

class SamiusDateTimeImmutableType extends DateTimeImmutableType
{
    protected static ?Timezone $timezone = null;

    /**
     * {@inheritdoc}
     */
    public function convertToPHPValue($value, AbstractPlatform $platform)
    {
        if ($value === null || $value instanceof DateTimeImmutable) {
            return $value;
        }

        $dateTime = new DateTimeImmutable($value, self::getTimezone());
//        $dateTime = DateTimeImmutable::createFromFormat($platform->getDateTimeFormatString(), $value, self::getTimezone());

        if (! $dateTime) {
            throw \Doctrine\DBAL\Types\ConversionException::conversionFailedFormat(
                $value,
                $this->getName(),
                $platform->getDateTimeFormatString()
            );
        }

        return $dateTime;
    }

    /**
     * Ready for UTC datetime. in default datetime no fixed timezone is set
     * @return Timezone|null
     */
    protected static function getTimezone():?Timezone
    {
        return null;
    }

}
