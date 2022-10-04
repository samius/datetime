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


    public static function now(): static;

    public static function fromDb(string $dbString): static;

    public static function fromDateTime(\DateTimeInterface $dateTime): static;

    public static function fromDatetimeImmutable(\DateTimeImmutable $dateTimeImmutable): static;

    public static function fromTimestamp(int $timestamp): static;

    public static function createNullDate(): static;

    public static function createFromYearmonth(string $yearmonth): static;

    public function getClone(): static;

    public function getMinuteInDay(): int;

    public function getDayOfWeek(): string;

    public function getDayOfMonth(): int;

    public function getDaysOfCurrentMonth():int;

    public function isWeekend(): bool;

    public function getMonthHumanName(?int $monthNumber = null, int $inflect = 1): string;

    public function __toString(): string;

    public function getDbDate(): string;

    public function getDbDatetime(): string;

    public function getHumanDate(): string;

    public function getHumanFull(): string;

    public function getHumanTime(): string;

    public function getYearmonth(): string;

    public static function getPreviousDayNum(int $dayNum): int;

    public static function isYearmonth(string $yearmonth): bool;

    public function getMilis(): int;

    public function isNullDate(): bool;

    public function isToday(): bool;

    public function isYesterday(): bool;

    public function addPart(int $number, string $part): static;

    public function subPart(int $number, string $part): static;

    public function setDayOfYear(int $targetDay): static;

    public function setDayOfMonth(int $targetDay): static;

    public function setDayOfWeek(int $targetDay): static;

    public function resetTime(): static;

    public function resetSeconds(): static;

    public function setMinuteInDay(int $minuteInDay): static;

    public function maxTime(): static;

    public function addWorkHours(int $hours): static;

    public function addSeconds(int $number): static;

    public function addMins(int $number): static;

    public function addHours(int $number): static;

    public function addDays(int $number): static;

    public function addWeeks(int $number): static;

    public function addMonths(int $number): static;

    public function addYears(int $number): static;

    public function subSeconds(int $number): static;

    public function subMins(int $number): static;

    public function subHours(int $number): static;

    public function subDays(int $number): static;

    public function subWeeks(int $number): static;

    public function subMonths(int $number): static;

    public function subYears(int $number): static;

    public function setLastDayInMonth(): static;

    public function toMutable(): DateTime;

    public function toImmutable(): DateTimeImmutable;
}
