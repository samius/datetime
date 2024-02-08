<?php

namespace Samius\DateTime\Traits;

use DateInterval;
use Exception;
use InvalidArgumentException;
use Samius\DateTime;
use Samius\DateTimeImmutable;

trait ModifierTrait
{
    /**
     * @throws Exception
     */
    public function addPart(int $number, string $part): static
    {
        if ($number < 0) {
            return $this->subPart(abs($number), $part);
        }
        $interval = new DateInterval($this->getIntervalString($number, $part));

        return $this->add($interval);
    }

    /**
     * @throws Exception
     */
    public function subPart(int $number, string $part): static
    {
        if ($number < 0) {
            return $this->addPart(abs($number), $part);
        }

        $interval = new DateInterval($this->getIntervalString($number, $part));

        return $this->sub($interval);
    }

    /**
     * Pricte nebo odecte patricny pocet dnu
     * @param int $actualDay - den, ktery je nastaveny v soucasnosti. Muze predstavovat den v tydnu, mesici, roce...
     * @param int $targetDay - den, ktery chci nastavit (v tydnu, mesici, roce..)
     *
     */
    private function addOrSubDays(int $actualDay, int $targetDay): static
    {
        if ($actualDay >= $targetDay) {
            $diff = $actualDay - $targetDay;
            return $this->sub(new DateInterval("P{$diff}D"));
        } else {
            $diff = $targetDay - $actualDay;
            return $this->add(new DateInterval("P{$diff}D"));
        }
    }

    /**
     * Nastavi patricny den v roce, CISLOVANY OD 0!!!
     * Pokud tedy chci nastavit 1.1., nastavim den 0.
     * V neprestupnem roce je 31.12. den 364,
     * v prestupnem roce je 31.12. den 365.
     */
    public function setDayOfYear(int $targetDay): static
    {
        $actualDay = (int)$this->format('z');
        return $this->addOrSubDays($actualDay, $targetDay);
    }

    /**
     * Nastavi patricny den v mesici
     */
    public function setDayOfMonth(int $targetDay): static
    {
        $actualDay = (int)$this->format('j');
        return $this->addOrSubDays($actualDay, $targetDay);
    }

    /**
     * Nastavi patricny den v tydnu (1=monday, 7=sunday)
     */
    public function setDayOfWeek(int $targetDay): static
    {
        $actualDay = (int)$this->format('N');
        return $this->addOrSubDays($actualDay, $targetDay);
    }
    
    public function moveForwardToDayOfWeek(int $targetDay): static
    {
        $actualDay = (int) $this->getDayOfWeek();
        if ($actualDay > $targetDay) {
            return $this->addDays(7 - $actualDay + $targetDay);
        }
        return $this->addDays($targetDay - $actualDay);
    }

    /**
     * Nastavi cas na 00:00:00
     */
    public function resetTime(): static
    {
        return $this->setTime(0, 0, 0);
    }


    /**
     * Vynuluje sekundy
     */
    public function resetSeconds(): static
    {
        $seconds = $this->format('s');
        return $this->subPart($seconds, self::PART_SECOND);
    }

    public function resetMinutes(): static
    {
        $minutes = (int) $this->format('i');
        return $this->subMins($minutes);
    }


    public function setMinuteInDay(int $minuteInDay): static
    {
        return $this->resetTime()->addPart($minuteInDay, self::PART_MINUTE);
    }

    /**
     * Nastavi cas na 23:59:59
     */
    public function maxTime(): static
    {
        return $this->setTime(23, 59, 59);
    }

    /**
     * Prida k datu dany pocet hodin, ktere spadaji do pracovniho dne. Preskakuje tedy vikendy. Nebere v uvahu statni
     * svatky.
     */
    public function addWorkHours(int $hours): static
    {
        while ($hours > 0) {
            $this->addPart(1, self::PART_HOUR);
            if (!$this->isWeekend()) {
                $hours--;
            }
        }
    }

    public function addSeconds(int $number): static
    {
        return $this->addPart($number, self::PART_SECOND);
    }

    public function addMins(int $number): static
    {
        return $this->addPart($number, self::PART_MINUTE);
    }

    public function addHours(int $number): static
    {
        return $this->addPart($number, self::PART_HOUR);
    }

    public function addDays(int $number): static
    {
        return $this->addPart($number, self::PART_DAY);
    }

    public function addWeeks(int $number): static
    {
        return $this->addPart($number, self::PART_WEEK);
    }

    public function addMonths(int $number): static
    {
        return $this->addPart($number, self::PART_MONTH);
    }

    public function addYears(int $number): static
    {
        return $this->addPart($number, self::PART_YEAR);
    }

    public function subSeconds(int $number): static
    {
        return $this->subPart($number, self::PART_SECOND);
    }

    public function subMins(int $number): static
    {
        return $this->subPart($number, self::PART_MINUTE);
    }

    public function subHours(int $number): static
    {
        return $this->subPart($number, self::PART_HOUR);
    }

    public function subDays(int $number): static
    {
        return $this->subPart($number, self::PART_DAY);
    }

    public function subWeeks(int $number): static
    {
        return $this->subPart($number, self::PART_WEEK);
    }

    public function subMonths(int $number): static
    {
        return $this->subPart($number, self::PART_MONTH);
    }

    public function subYears(int $number): static
    {
        return $this->subPart($number, self::PART_YEAR);
    }

    /**
     * Nastavi posledni den v aktualnim mesici
     */
    public function setLastDayInMonth(): static
    {
        return $this->setDayOfMonth($this->format('t'));
    }

    public function setDayOfMonthOrLast(int $dayOfMonth): static
    {
        $dayOfMonth = min($dayOfMonth, $this->getDaysOfCurrentMonth());
        return $this->setDayOfMonth($dayOfMonth);
    }

    private function getIntervalString(int $number, string $part): string
    {
        return match ($part) {
            self::PART_SECOND => "PT{$number}S",
            self::PART_MINUTE => "PT{$number}M",
            self::PART_HOUR => "PT{$number}H",
            self::PART_DAY => "P{$number}D",
            self::PART_WEEK => "P" . (string)($number * 7) . "D",
            self::PART_MONTH => "P{$number}M",
            self::PART_YEAR => "P{$number}Y",
            default => throw new InvalidArgumentException('Invalid date part ' . $part),
        };
    }

    public function toMutable(): DateTime
    {
        return DateTime::fromDateTime($this)->setTimezone($this->getTimezone());
    }

    public function toImmutable(): DateTimeImmutable
    {
        if ($this instanceof DateTimeImmutable) {
            return $this;
        }
        return DateTimeImmutable::fromDateTime($this)->setTimezone($this->getTimezone());
    }

    /**
     * Set UTC timezone and return. If immutable, new instance is returned.
     */
    public function toUtc(): static
    {
        return $this->setTimezone(new DateTime\Timezone('UTC'));

    }
}
