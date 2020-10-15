<?php
namespace Samius\DateTime\Traits;

use Samius\DateTime;
use Samius\DateTimeInterface;

Trait FactoryTrait
{
    /**
     * @static
     * @return DateTimeInterface
     */
    public static function now():DateTimeInterface
    {
        return new static();
    }

    /**
     * @static
     * @param string $dbString Datetime in db format
     * @return DateTimeInterface
     */
    public static function fromDb(string $dbString): DateTimeInterface
    {
        return static::createFromFormat(self::getDbFormat($dbString), $dbString);
    }

    /**
     * @static
     * @param string $timeFromDb
     * @return string Format data.
     */
    private static function getDbFormat(string $timeFromDb):string
    {
        if (strpos($timeFromDb, ':') === false) {
            // mame pouze datum
            return self::DB_DATE;
        }

        if (strpos($timeFromDb, '-') === false) {
            // mame pouze cas
            return 'H:i:s';
        }

        return self::DB_FULL;
    }

    /**
     * Used from both mutable and immutable
     * @param \DateTimeInterface $dateTime
     * @return DateTime
     */
    public static function fromDateTime(\DateTimeInterface $dateTime): DateTimeInterface
    {
        return new static($dateTime->format(self::DB_FULL_MICRO));
    }

    public static function fromDatetimeImmutable(\DateTimeImmutable $dateTimeImmutable): DateTimeInterface
    {
        return new static($dateTime->format(self::DB_FULL_MICRO));
    }

    /**
     * @static
     * @param int $timestamp
     * @return DateTime
     */
    public static function fromTimestamp($timestamp): DateTimeInterface
    {
        return new static("@$timestamp");
    }

    /**
     * Returns date 1970-01-01 00:00:00
     * @return static
     */
    public static function createNullDate(): DateTimeInterface
    {
        return static::fromTimestamp(0);
    }

    /**
     * @param string $format
     * @param string $time
     * @param \DateTimeZone $tz
     * @return DateTime
     */
    public static function createFromFormat($format, $time, \DateTimeZone $tz = null)
    {
        if ($tz !== null) {
            $datetime = parent::createFromFormat($format, $time, $tz);
        } else {
            $datetime = parent::createFromFormat($format, $time);
        }

        return $datetime ? self::fromDateTime($datetime) : null;
    }

    /**
     * At 2017-31-10, when we call createFromFormat('Ym','201709'), it will return '2017-10-01'.
     * We do not set day, so actual day is set. As september has only 30 days (and today is 31. day), one day is added, so
     * it goes into october.
     * @param $yearmonth
     * @return DateTime
     */
    public static function createFromYearmonth($yearmonth): DateTimeInterface
    {
        return static::createFromFormat('!' . self::YEARMONTH, $yearmonth);
    }

    /**
     * @return DateTime
     */
    public function getClone(): DateTimeInterface
    {
        return clone($this);
    }

}
