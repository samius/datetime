<?php

namespace Samius\DateTime\Traits;

use DateTimeImmutable;
use DateTimeZone;
use Samius\DateTimeInterface;

trait FactoryTrait
{
    public static function now(): static
    {
        return new static();
    }

    public static function fromDb(string $dbString, ?DateTimeZone $tz = null): static
    {
        if (!$tz) {
            return static::createFromFormat(self::getDbFormat($dbString), $dbString);
        }
        return static::createFromFormat(self::getDbFormat($dbString), $dbString, $tz);
    }

    private static function getDbFormat(string $timeFromDb): string
    {
        if (!str_contains($timeFromDb, ':')) {
            // mame pouze datum
            return self::DB_DATE;
        }

        if (!str_contains($timeFromDb, '-')) {
            // mame pouze cas
            return 'H:i:s';
        }

        return DateTimeInterface::DB_FULL;
    }

    public static function fromDateTime(\DateTimeInterface $dateTime): static
    {
        return new static($dateTime->format(DateTimeInterface::DB_FULL_MICRO), $dateTime->getTimezone());
    }

    public static function fromDatetimeImmutable(DateTimeImmutable $dateTimeImmutable): static
    {
        return self::fromDateTime($dateTimeImmutable);
    }


    public static function fromTimestamp(int $timestamp): static
    {
        return (new static())->setTimestamp($timestamp);
    }

    /**
     * Returns date 1970-01-01 00:00:00
     */
    public static function createNullDate(): static
    {
        return static::fromTimestamp(0);
    }

    public static function createFromFormat(string $format, string $time, ?DateTimeZone $tz = null): static|false
    {
        if ($tz !== null) {
            $datetime = \DateTime::createFromFormat($format, $time, $tz);
        } else {
            $datetime = \DateTime::createFromFormat($format, $time);
        }

        return $datetime ? static::fromDateTime($datetime) : false;
    }

    /**
     * At 2017-31-10, when we call createFromFormat('Ym','201709'), it will return '2017-10-01'.
     * We do not set day, so actual day is set. As september has only 30 days (and today is 31. day), one day is added, so
     * it goes into october.
     */
    public static function createFromYearmonth(string $yearmonth): static
    {
        return static::createFromFormat('!' . self::YEARMONTH, $yearmonth);
    }

    public function getClone(): static
    {
        return clone($this);
    }

}
