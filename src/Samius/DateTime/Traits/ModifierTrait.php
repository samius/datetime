<?php

namespace Samius\DateTime\Traits;

use Samius\DateTime;
use Samius\DateTimeImmutable;
use Samius\DateTimeInterface;

trait ModifierTrait
{
    /**
     * @param int $number
     * @param string $part
     * @return DateTimeInterface|self
     */
    public function addPart(int $number, string $part):DateTimeInterface
    {
        if ($number < 0) {
            return $this->subPart(abs($number), $part);
        }
        $interval = new \DateInterval($this->getIntervalString($number, $part));

        return $this->add($interval);
    }

    /**
     * @param int $number
     * @param string $part
     * @return DateTimeInterface|self
     */
    public function subPart(int $number, string $part):DateTimeInterface
    {
        if ($number < 0) {
            return $this->addPart(abs($number), $part);
        }

        $interval = new \DateInterval($this->getIntervalString($number, $part));

        return $this->sub($interval);
    }

    /**
     * Pricte nebo odecte patricny pocet dnu
     * @param int $actualDay - den, ktery je nastaveny v soucasnosti. Muze predstavovat den v tydnu, mesici, roce...
     * @param int $targetDay - den, ktery chci nastavit (v tydnu, mesici, roce..)
     *
     * @return DateTimeInterface|self
     */
    private function addOrSubDays($actualDay, $targetDay):DateTimeInterface
    {
        if ($actualDay >= $targetDay) {
            $diff = $actualDay - $targetDay;
            return $this->sub(new \DateInterval("P{$diff}D"));
        } else {
            $diff = $targetDay - $actualDay;
            return $this->add(new \DateInterval("P{$diff}D"));
        }
    }

    /**
     * Nastavi patricny den v roce, CISLOVANY OD 0!!!
     * Pokud tedy chci nastavit 1.1., nastavim den 0.
     * V neprestupnem roce je 31.12. den 364,
     * v prestupnem roce je 31.12. den 365.
     *
     * @param int $targetDay
     * @return DateTimeInterface|self
     */
    public function setDayOfYear($targetDay):DateTimeInterface
    {
        $actualDay = (int)$this->format('z');
        return $this->addOrSubDays($actualDay, $targetDay);
    }

    /**
     * Nastavi patricny den v mesici
     * @param int $targetDay
     * @return DateTimeInterface|self
     */
    public function setDayOfMonth($targetDay):DateTimeInterface
    {
        $actualDay = (int)$this->format('j');
        return $this->addOrSubDays($actualDay, $targetDay);
    }

    /**
     * Nastavi patricny den v tydnu (1=monday, 7=sunday)
     * @param int $targetDay
     * @return DateTimeInterface|self
     */
    public function setDayOfWeek($targetDay):DateTimeInterface
    {
        $actualDay = (int)$this->format('N');
        return $this->addOrSubDays($actualDay, $targetDay);
    }

    /**
     * Nastavi cas na 00:00:00
     * @return DateTimeInterface|self
     */
    public function resetTime():DateTimeInterface
    {
        return $this->setTime(0, 0, 0);
    }


    /**
     * Vynuluje sekundy
     * @return DateTimeInterface|self
     */
    public function resetSeconds():DateTimeInterface
    {
        $seconds = $this->format('s');
        return $this->subPart($seconds, self::PART_SECOND);
    }

    /**
     * @param $minuteInDay
     * @return DateTimeInterface|self
     */
    public function setMinuteInDay($minuteInDay):DateTimeInterface
    {
        return $this->resetTime()->addPart($minuteInDay, self::PART_MINUTE);
    }

    /**
     * Nastavi cas na 23:59:59
     * @return DateTimeInterface|self
     */
    public function maxTime():DateTimeInterface
    {
        return $this->setTime(23, 59, 59);
    }

    /**
     * Prida k datu dany pocet hodin, ktere spadaji do pracovniho dne. Preskakuje tedy vikendy. Nebere v uvahu statni
     * svatky.
     *
     * @param int $hours
     * @return DateTimeInterface|self
     */
    public function addWorkHours($hours):DateTimeInterface
    {
        while ($hours > 0) {
            $this->addPart(1, self::PART_HOUR);
            if (!$this->isWeekend()) {
                $hours--;
            }
        }
    }

    /**
     * Shortcut function for addPart
     * @param $number
     * @return DateTimeInterface|self
     */
    public function addSeconds($number):DateTimeInterface
    {
        return $this->addPart($number, self::PART_SECOND);
    }

    /**
     * Shortcut function for addPart
     * @param $number
     * @return DateTimeInterface|self
     */
    public function addMins($number):DateTimeInterface
    {
        return $this->addPart($number, self::PART_MINUTE);
    }

    /**
     * Shortcut function for addPart
     * @param $number
     * @return DateTimeInterface|self
     */
    public function addHours($number):DateTimeInterface
    {
        return $this->addPart($number, self::PART_HOUR);
    }

    /**
     * Shortcut function for addPart
     * @param $number
     * @return DateTimeInterface|self
     */
    public function addDays($number):DateTimeInterface
    {
        return $this->addPart($number, self::PART_DAY);
    }

    /**
     * Shortcut function for addPart
     * @param $number
     * @return DateTimeInterface|self
     */
    public function addWeeks($number):DateTimeInterface
    {
        return $this->addPart($number, self::PART_WEEK);
    }

    /**
     * Shortcut function for addPart
     * @param $number
     * @return DateTimeInterface|self
     */
    public function addMonths($number):DateTimeInterface
    {
        return $this->addPart($number, self::PART_MONTH);
    }

    /**
     * Shortcut function for addPart
     * @param $number
     * @return DateTimeInterface|self
     */
    public function addYears($number):DateTimeInterface
    {
        return $this->addPart($number, self::PART_YEAR);
    }

    /**
     * Nastavi posledni den v aktualnim mesici
     *
     * @return DateTimeInterface|self
     */
    public function setLastDayInMonth():DateTimeInterface
    {
        return $this->setDayOfMonth($this->format('t'));
    }
    
    public function setDayOfMonthOrLast(int $dayOfMonth):DateTimeInterface
    {
        $dayOfMonth = min($dayOfMonth, $this->getDaysOfCurrentMonth());
        return $this->setDayOfMonth($dayOfMonth);
    }

    /**
     * @param int $number
     * @param string $part "second", "minute" ...
     * @return string
     * @throws \InvalidArgumentException
     */
    private function getIntervalString(int $number, string $part):string
    {
        switch ($part) {
            case self::PART_SECOND:
                $interval = "PT{$number}S";
                break;
            case self::PART_MINUTE:
                $interval = "PT{$number}M";
                break;
            case self::PART_HOUR:
                $interval = "PT{$number}H";
                break;
            case self::PART_DAY:
                $interval = "P{$number}D";
                break;
            case self::PART_WEEK:
                $interval = "P" . (string)($number * 7) . "D";
                break;
            case self::PART_MONTH:
                $interval = "P{$number}M";
                break;
            case self::PART_YEAR:
                $interval = "P{$number}Y";
                break;
            default:
                throw new \InvalidArgumentException('Chybna date part ' . $part);
        }

        return $interval;
    }

    /**
     * @return DateTime
     */
    public function toMutable():DateTime
    {
        return DateTime::fromDateTime($this)->setTimezone($this->getTimezone());
    }

    /**
     * @return DateTimeImmutable
     */
    public function toImmutable():DateTimeImmutable
    {
        if ($this instanceof DateTimeImmutable) {
            return $this;
        }
        return DateTimeImmutable::fromDateTime($this)->setTimezone($this->getTimezone());
    }
}
