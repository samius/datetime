<?php


namespace Samius;

class DateTimeImmutable extends \DateTimeImmutable implements DateTimeInterface
{

    public static function fromDatetimeImmutable(\DateTimeImmutable $dateTimeImmutable)
    {
        return self::createFromMutable(\DateTime::createFromImmutable($dateTimeImmutable));
    }
    
    public function getMutable(): DateTime
    {
        return DateTime::createFromImmutable($this);
    }
}
