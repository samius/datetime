<?php
namespace Samius\DateTime\Traits;

use DateTimeZone;
use Samius\DateTime;
use Samius\DateTimeInterface;

Trait FactoryTrait
{
    /**
     * @static
     * @return DateTimeInterface|self
     */
    public static function now():DateTimeInterface
    {
        return new static();
    }

    /**
     * @static
     * @param string $dbString Datetime in db format
     * @return DateTimeInterface|self
     */
    public static function fromDb(string $dbString, ?DateTimeZone $tz = null): DateTimeInterface
    {
        if (!$tz) {
            return static::createFromFormat(self::getDbFormat($dbString), $dbString);
        }
        return static::createFromFormat(self::getDbFormat($dbString), $dbString, $tz);

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
     * @return DateTimeInterface|self
     */
    public static function fromDateTime(\DateTimeInterface $dateTime): DateTimeInterface
    {
        return new static($dateTime->format(self::DB_FULL_MICRO), $dateTime->getTimezone());
    }

    public static function fromDatetimeImmutable(\DateTimeImmutable $dateTimeImmutable): DateTimeInterface
    {
        return static::fromDateTime($dateTimeImmutable);
    }

    /**
     * @static
     * @param int $timestamp
     * @return DateTimeInterface|self
     */
    public static function fromTimestamp($timestamp): DateTimeInterface
    {
        return (new static)->setTimestamp($timestamp);
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
     * @param DateTimeZone|null $tz
     * @return DateTimeInterface|self|null
     */
    public static function createFromFormat($format, $time, DateTimeZone $tz = null):?DateTimeInterface
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
     * @return DateTimeInterface|self
     */
    public static function createFromYearmonth($yearmonth): DateTimeInterface
    {
        return static::createFromFormat('!' . self::YEARMONTH, $yearmonth);
    }

    /**
     * @return DateTimeInterface|self
     */
    public function getClone(): DateTimeInterface
    {
        return clone($this);
    }

}
