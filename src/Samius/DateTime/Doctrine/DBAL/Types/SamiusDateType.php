<?php
namespace Samius\Doctrine\DBAL\Types;
use Doctrine\DBAL\Types;
use Doctrine\DBAL\Platforms\AbstractPlatform;
use Samius\DateTime;

class SamiusDateType extends Types\DateType
{
    public function getName()
    {
        return 'dateext';
    }

    public function convertToPHPValue($value, AbstractPlatform $platform)
    {
        if ($value === null) {
            return null;
        }

        $val = DateTime::createFromFormat('!'.$platform->getDateFormatString(), $value);
        if (!$val) {
            throw Types\ConversionException::conversionFailed($value, $this->getName());
        }
        return $val;
    }
}