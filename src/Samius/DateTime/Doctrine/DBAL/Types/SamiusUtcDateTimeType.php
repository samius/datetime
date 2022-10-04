<?php
namespace Samius\DateTime\Doctrine\DBAL\Types;
use Doctrine\DBAL\Types;
use Doctrine\DBAL\Platforms\AbstractPlatform;
use Doctrine\DBAL\Types\ConversionException;
use Samius\DateTime;
use Samius\DateTime\Timezone;

/**
 * @see https://www.doctrine-project.org/projects/doctrine-orm/en/2.9/cookbook/working-with-datetime.html
 */
class SamiusUtcDateTimeType extends SamiusDateTimeType
{
    protected static ?Timezone $timezone;

    public function getName(): string
    {
        return 'utc_datetime';
    }

    public function convertToDatabaseValue($value, AbstractPlatform $platform): ?string
    {
        if ($value instanceof \DateTimeInterface) {
            $value->setTimezone(static::getTimezone());
        }
        return parent::convertToDatabaseValue($value, $platform);
    }

    public function convertToPHPValue($value, AbstractPlatform $platform) :?DateTime
    {
        if (null === $value || $value instanceof DateTime) {
            return $value;
        }

        $converted = DateTime::createFromFormat(
            $platform->getDateTimeFormatString(),
            $value,
            static::getTimezone()
        );

        if (!$converted) {
            throw ConversionException::conversionFailedFormat(
                $value,
                $this->getName(),
                $platform->getDateTimeFormatString()
            );
        }

        return $converted;
    }

    protected static function getTimezone(): ?Timezone
    {
        return self::$timezone ?: self::$timezone = new Timezone('UTC');
    }
}
