<?php
namespace Samius\DateTime\Traits;

trait FormatterTrait
{
    /**
     * Vraci pocet minut od pulnoci
     */
    public function getMinuteInDay(): int
    {
        $hours = (int)$this->format('H');
        $minutes = (int)$this->format('i');

        return $hours * 60 + $minutes;
    }


    /**
     * @return string cislo dne v tydnu (1=monday, 7=sunday)
     */
    public function getDayOfWeek(): string
    {
        return $this->format('N');
    }

    public function isWeekend(): bool
    {
        $dayOfWeek = $this->format('w');

        return $dayOfWeek == 0 || $dayOfWeek == 6;
    }

    /**
     * Vrati lidsky citelny nazev mesice
     *
     * @param ?int $monthNumber cislo mesice
     * @param int $inflect v kolikatem pade chci nazev (napr. 1 = cerven, 2=cervna)
     */
    public function getMonthHumanName(?int $monthNumber = null, int $inflect = 1): string
    {
        if (!$monthNumber) {
            $monthNumber = $this->format('n');
        }

        return match ($monthNumber) {
            1 => ($inflect == 1) ? 'leden' : 'ledna',
            2 => ($inflect == 1) ? 'únor' : 'února',
            3 => ($inflect == 1) ? 'březen' : 'března',
            4 => ($inflect == 1) ? 'duben' : 'dubna',
            5 => ($inflect == 1) ? 'květen' : 'května',
            6 => ($inflect == 1) ? 'červen' : 'června',
            7 => ($inflect == 1) ? 'červenec' : 'července',
            8 => ($inflect == 1) ? 'srpen' : 'srpna',
            9 => ($inflect == 1) ? 'září' : 'září',
            10 => ($inflect == 1) ? 'říjen' : 'října',
            11 => ($inflect == 1) ? 'listopad' : 'listopadu',
            12 => ($inflect == 1) ? 'prosinec' : 'prosince',
            default => throw new \InvalidArgumentException("neexistujici mesic $monthNumber"),
        };
    }

    public function __toString(): string
    {
        return $this->format(self::HUMAN_FULL);
    }

    public function getDbDate(): string
    {
        return $this->format(self::DB_DATE);
    }

    public function getDbDatetime(): string
    {
        return $this->format(self::DB_FULL);
    }

    public function getHumanDate(): string
    {
        return $this->format(self::HUMAN_DATE);
    }

    public function getHumanFull(): string
    {
        return $this->format(self::HUMAN_FULL);
    }

    public function getHumanTime(): string
    {
        return $this->format(self::HUMAN_TIME);
    }

    public function getYearmonth(): string
    {
        return $this->format(self::YEARMONTH);
    }

    /**
     * Vraci cislo predchoziho dne
     */
    public static function getPreviousDayNum(int $dayNum): int
    {
        if ($dayNum === 1) {
            return 7;
        } else {
            return $dayNum - 1;
        }
    }

    /**
     * Checks string whether it can be a yearmonth (e.g.201901)
     */
    public static function isYearmonth(string $yearmonth): bool
    {
        if (!preg_match('#\d{6}#', $yearmonth)) {
            return false;
        }
        $month = substr($yearmonth, 4);
        if (strlen($month) != 2 || (int)$month > 12 || (int)$month < 1) {
            return false;
        }

        return true;
    }

    public function getMilis(): int
    {
        return $this->getTimestamp() * 1000;
    }

    /**
     * If timestamp == 0, return true.
     * Else return false
     */
    public function isNullDate(): bool
    {
        return $this->getTimestamp() == 0;
    }

    public function isToday(): bool
    {
        $timezone = $this->getTimezone();
        $now = new static(null, $timezone);

        return $this->getDbDate() == $now->getDbDate();
    }


    public function isYesterday(): bool
    {
        $timezone = $this->getTimezone();
        $yesterday = new static(null, $timezone);
        $yesterday->subPart(1, self::PART_DAY);

        return $this->getDbDate() == $yesterday->getDbDate();
    }

    public function isDst(): bool
    {
        return $this->format('I') === "1";
    }

    public function getDayOfMonth(): int
    {
        return (int)$this->format('j');
    }

    public function getDaysOfCurrentMonth(): int
    {
        return (int)$this->format('t');
    }
}
