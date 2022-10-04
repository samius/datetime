<?php
namespace Samius\DateTime\Doctrine\DBAL\Types;
use Doctrine\DBAL\Types;
use Doctrine\DBAL\Platforms\AbstractPlatform;
use Doctrine\DBAL\Types\ConversionException;
use Doctrine\DBAL\Types\DateTimeType;
use Samius\DateTime;
use Samius\DateTime\Timezone;

class SamiusDateTimeType extends DateTimeType
{
    protected static ?Timezone $timezone = null;

    public function getName():string
    {
        return 'datetime';
    }

    public function convertToPHPValue($value, AbstractPlatform $platform): ?DateTime
    {
        if ($value === null || $value instanceof DateTime) {
            return $value;
        }
        if ($value instanceof \DateTimeInterface) {
            return DateTime::fromDateTime($value);
        }

        $val = DateTime::createFromFormat($platform->getDateTimeFormatString(), $value);
        if (!$val) {
            throw ConversionException::conversionFailed($value, $this->getName());
        }
        return $val;
    }

    public function requiresSQLCommentHint(AbstractPlatform $platform): bool
    {
        return false;
    }

    /**
     * Ready for UTC datetime. in default datetime no fixed timezone is set
     */
    protected static function getTimezone(): ?Timezone
    {
        return null;
    }
}
