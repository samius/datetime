<?php
namespace Samius\DateTime\Doctrine\DBAL\Types;
use Samius\DateTime\Timezone;
use Doctrine\DBAL\Platforms\AbstractPlatform;
use Samius\DateTimeImmutable;

/**
 * @see https://www.doctrine-project.org/projects/doctrine-orm/en/2.9/cookbook/working-with-datetime.html
 */
class SamiusUtcDateTimeImmutableType extends SamiusDateTimeImmutableType
{
    public function getName(): string
    {
        return 'utc_datetime_immutable';
    }

    public function convertToDatabaseValue($value, AbstractPlatform $platform)
    {
        if ($value instanceof DateTimeImmutable) {
            $value = $value->setTimezone(static::getTimezone());
        }
        return parent::convertToDatabaseValue($value, $platform);
    }

    protected static function getTimezone(): ?Timezone
    {
        return self::$timezone ?: self::$timezone = new Timezone('UTC');
    }
}
