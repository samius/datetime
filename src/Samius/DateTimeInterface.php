<?php


namespace Samius;


interface DateTimeInterface extends \DateTimeInterface
{
    public const HUMAN_FULL = 'j. n. Y G:i:s',// 25. 2. 1983
        HUMAN_TIME = 'G:i',//12:45
        HUMAN_DATE = 'j. n. Y',// 25. 2. 1983
        DB_DATE = 'Y-m-d',// 1983-02-25
        DB_FULL = 'Y-m-d H:i:s', // 1983-02-25 12:45:42
        DB_FULL_MICRO = 'Y-m-d H:i:s.u',//1983-02-25 12:45:42.321
        YEARMONTH = 'Ym',//198302

        PART_SECOND = 'second',
        PART_MINUTE = 'minute',
        PART_HOUR = 'hour',
        PART_DAY = 'day',
        PART_WEEK = 'week',
        PART_MONTH = 'month',
        PART_YEAR = 'year';


    public static function now(): DateTimeInterface;

    public static function fromDb(string $dbString): DateTimeInterface;

    public static function fromDateTime(\DateTimeInterface $dateTime): DateTimeInterface;

    public static function fromDatetimeImmutable(\DateTimeImmutable $dateTimeImmutable): DateTimeInterface;

    public static function fromTimestamp($timestamp): DateTimeInterface;

    public static function createNullDate(): DateTimeInterface;

    public static function createFromYearmonth($yearmonth): DateTimeInterface;

    public function getClone(): DateTimeInterface;

    public function getMinuteInDay(): int;

    public function getDayOfWeek(): string;

    public function getDayOfMonth(): int;

    public function getDaysOfCurrentMonth():int;

    public function isWeekend(): bool;

    public function getMonthHumanName($monthNumber = null, $inflect = 1): string;

    public function __toString(): string;

    public function getDbDate(): string;

    public function getDbDatetime(): string;

    public function getHumanDate(): string;

    public function getYearmonth(): string;

    public static function getPreviousDayNum($dayNum): int;

    public static function isYearmonth($yearmonth): bool;

    public function getMilis(): int;

    public function isNullDate(): bool;

    public function isToday(): bool;

    public function isYesterday(): bool;

    public function addPart(int $number, string $part): DateTimeInterface;

    public function subPart(int $number, string $part): DateTimeInterface;

    public function setDayOfYear($targetDay): DateTimeInterface;

    public function setDayOfMonth($targetDay): DateTimeInterface;

    public function setDayOfWeek($targetDay): DateTimeInterface;

    public function resetTime(): DateTimeInterface;

    public function resetSeconds(): DateTimeInterface;

    public function setMinuteInDay($minuteInDay): DateTimeInterface;

    public function setTime($hour, $minute, $second = 0, $microseconds = 0);

    public function maxTime(): DateTimeInterface;

    public function addWorkHours($hours): DateTimeInterface;

    public function addSeconds($number): DateTimeInterface;

    public function addMins($number): DateTimeInterface;

    public function addHours($number): DateTimeInterface;

    public function addDays($number): DateTimeInterface;

    public function addWeeks($number): DateTimeInterface;

    public function addMonths($number): DateTimeInterface;

    public function addYears($number): DateTimeInterface;

    public function setLastDayInMonth(): DateTimeInterface;

    public function toMutable(): DateTime;

    public function toImmutable(): DateTimeImmutable;
}
