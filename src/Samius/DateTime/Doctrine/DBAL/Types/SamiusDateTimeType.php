<?php
namespace Samius\DateTime\Doctrine\DBAL\Types;
use Doctrine\DBAL\Types;
use Doctrine\DBAL\Platforms\AbstractPlatform;
use Samius\DateTime;

class SamiusDateTimeType extends Types\DateTimeType
{
    public function getName()
    {
        return 'datetime';
    }

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
}
