<?php
namespace Samius\DateTime\Doctrine\DBAL\Types;
use Doctrine\DBAL\Types;
use Doctrine\DBAL\Platforms\AbstractPlatform;
use Samius\DateTime;
use Samius\DateTime\Timezone;

class SamiusDateTimeType extends Types\DateTimeType
{
    protected static ?Timezone $timezone = null;

    public function getName():string
    {
        return 'datetime';
    }

    /**
     * @return mixed
     */
    public function convertToPHPValue($value, AbstractPlatform $platform)
    {
        if ($value === null) {
            return null;
        }

        $val = DateTime::createFromFormat($platform->getDateTimeFormatString(), $value);
        if (!$val) {
            throw \Doctrine\DBAL\Types\ConversionException::conversionFailed($value, $this->getName());
        }
        return $val;
    }

    public function requiresSQLCommentHint(AbstractPlatform $platform)
    {
        return false;
    }

    /**
     * Ready for UTC datetime. in default datetime no fixed timezone is set
     * @return Timezone|null
     */
    protected static function getTimezone(): ?Timezone
    {
        return null;
    }
}
