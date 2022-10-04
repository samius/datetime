<?php
namespace Samius\DateTime\Doctrine\DBAL\Types;
use Doctrine\DBAL\Types;
use Doctrine\DBAL\Platforms\AbstractPlatform;
use Doctrine\DBAL\Types\ConversionException;
use Doctrine\DBAL\Types\DateType;
use Samius\DateTime;

class SamiusDateType extends DateType
{
    public function getName(): string
    {
        return 'date';
    }

    public function convertToPHPValue($value, AbstractPlatform $platform): ?DateTime
    {
        if ($value === null) {
            return null;
        }

        $val = DateTime::createFromFormat('!'.$platform->getDateFormatString(), $value);
        if (!$val) {
            throw ConversionException::conversionFailed($value, $this->getName());
        }
        return $val;
    }

    public function requiresSQLCommentHint(AbstractPlatform $platform): bool
    {
        return false;
    }
}
