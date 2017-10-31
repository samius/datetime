<?php
namespace Samius\Doctrine\DBAL\Types;
use Doctrine\DBAL\Types;
use Doctrine\DBAL\Platforms\AbstractPlatform;
use Samius\DateTime;

class SamiusDateTimeTzType extends Types\DateTimeTzType
{
    public function getName()
    {
        return 'datetimetzext';
    }

    public function convertToPHPValue($value, AbstractPlatform $platform)
    {
        if ($value === null) {
            return null;
        }

        $val = DateTime::createFromFormat($platform->getDateTimeTzFormatString(), $value);
        if (!$val) {
            throw Types\ConversionException::conversionFailed($value, $this->getName());
        }
        return $val;
    }
}
