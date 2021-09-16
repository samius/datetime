<?php
namespace Samius\DateTime\Doctrine\DBAL\Types;
use Doctrine\DBAL\Types;
use Doctrine\DBAL\Platforms\AbstractPlatform;
use Samius\DateTime;
use Samius\DateTime\Timezone;

/**
 * @see https://www.doctrine-project.org/projects/doctrine-orm/en/2.9/cookbook/working-with-datetime.html
 */
class SamiusUtcDateTimeType extends SamiusDateTimeType
{
    protected static ?Timezone $timezone;

    public function convertToDatabaseValue($value, AbstractPlatform $platform)
    {
        if ($value instanceof \DateTimeInterface) {
            $value->setTimezone(self::getTimezone());
        }
        return parent::convertToDatabaseValue($value, $platform);
    }

    public function convertToPHPValue($value, AbstractPlatform $platform)
    {
        if (null === $value || $value instanceof \DateTime) {
            return $value;
        }

        $converted = DateTime::createFromFormat(
            $platform->getDateTimeFormatString(),
            $value,
            self::getTimezone()
        );

        if (!$converted) {
            throw Types\ConversionException::conversionFailedFormat(
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
