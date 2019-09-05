<?php
namespace Samius\DateTime\Doctrine\DBAL\Types;
use Doctrine\DBAL\Types;
use Doctrine\DBAL\Platforms\AbstractPlatform;
use Samius\DateTime;

class SamiusTimeType extends Types\TimeType
{
    public function getName()
    {
        return 'time';
    }

    public function convertToPHPValue($value, AbstractPlatform $platform)
    {
        if ($value === null) {
            return null;
        }

        $val = DateTime::createFromFormat($platform->getTimeFormatString(), $value);
        if (!$val) {
            throw Types\ConversionException::conversionFailed($value, $this->getName());
        }
        return $val;
    }
}
