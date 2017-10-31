<?php
namespace Samius\Doctrine\DBAL\Types;
use Doctrine\DBAL\Types;
use Doctrine\DBAL\Platforms\AbstractPlatform;
use Samius\DateTime;

class SamiusDateTimeType extends Types\DateTimeType
{
    public function getName()
    {
        return 'datetimeext';
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
}
