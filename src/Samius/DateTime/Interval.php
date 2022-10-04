<?php

namespace Samius\DateTime;

use DateTimeInterface;
use InvalidArgumentException;
use Samius\DateTime;
use Samius\DateTimeInterface as SamiusTimeInterfaceAlias;
use Stringable;

/**
 * Interval vyjadreny pomoci dvou objektu DateTime. Pokud je $start nebo $end rovno NULL, je v tomto smeru interval otevreny.
 * @author samius
 */
class Interval implements Stringable
{
    public function __construct(private readonly ?DateTimeInterface $start = null, private readonly ?DateTimeInterface $end = null)
    {
        $this->assertInputDate();
    }

    /**
     * podle toho, zda je interval uzavreny
     * 5.1.2005 - 10.5.2005
     * 5.1.2005 - ?
     * ? - 10.5.2005
     * ? - ?
     */
    public function __toString(): string
    {
        if ($this->start && $this->end) {
            return $this->start->format(SamiusTimeInterfaceAlias::HUMAN_DATE) . ' - ' . $this->end->format(SamiusTimeInterfaceAlias::HUMAN_DATE);
        } elseif ($this->start) {
            return $this->start->format(SamiusTimeInterfaceAlias::HUMAN_DATE) . ' - ?';
        } elseif ($this->end) {
            return '? - ' . $this->end->format(SamiusTimeInterfaceAlias::HUMAN_DATE);
        } else {
            return '? - ?';
        }
    }

    /**
     * @throws InvalidArgumentException Pokud je konec driv, nez zacatek.
     */
    private function assertInputDate(): void
    {
        if ($this->start > $this->end && $this->start !== null && $this->end !== null) {
            throw new InvalidArgumentException('Pocatecni datum je vetsi nez koncove');
        }
    }

    /**
     * @param ?string $startString format 2010-10-15 23:00:12
     * @param ?string $endString format 2010-10-15 23:00:12
     */
    public static function fromString(?string $startString = null, ?string $endString = null): self
    {
        $start = ($startString === null) ? null : new DateTime($startString);
        $end = ($endString === null) ? null : new DateTime($endString);

        return new self($start, $end);
    }

    public function isIntersecting(Interval $interval): bool
    {
        $i2Start = $interval->getStart();
        $i2End = $interval->getEnd();

        // i1      |
        // i2      |
        //
        // i1      _______________
        // i2         _____________________
        if ($this->start == $i2Start && $i2Start == $i2End && $this->end == $i2Start) {
            return true;
        }

        // i1      |_______|
        // 12  |_______________________________
        if (($i2End == null && $this->end > $i2Start) || ($this->end == null && $i2End > $this->start)) {
            return true;
        }

        // i1      |_______________
        // 12  |___________________
        if ($i2End === null && $this->end === null) {
            return true;
        }

        return ($i2Start < $this->end && $i2Start >= $this->start || $i2End > $this->start && $i2End < $this->end) ||
            ($this->start < $i2End && $this->start >= $i2Start || $this->end > $i2Start && $this->end < $i2End);
    }

    public function intersect(Interval $interval = null): ?self
    {
        //null je v tomto pripade brano jako prazdna mnozina - prunik s prazdnou mnozinou je vzdy prazdna mn.
        if ($interval === null) {
            return null;
        }

        if (!$this->isIntersecting($interval)) {
            return null;
        }

        $newStart = \max($this->start, $interval->getStart()); // max(null, 30) =  30

        if ($this->end === null || $interval->getEnd() === null) {
            $newEnd = \max($this->end, $interval->getEnd()); // vezmu nenullovou hodnotu - pokud jsou obe nullove, vezmu jednu z nich
        } else {
            $newEnd = \min($this->end, $interval->getEnd());
        }

        return new self($newStart, $newEnd);
    }

    public function getStart(): ?DateTimeInterface
    {
        return $this->start;
    }

    public function getEnd(): ?DateTimeInterface
    {
        return $this->end;
    }

    public function getLengthInDays(): ?int
    {
        if ($this->start === null || $this->end === null) {
            return null;
        }
        return $this->getStart()->diff($this->getEnd())->days;
    }

    /**
     * @param bool $forAdd if interval is used for adding, we have to consider winter to summer time transition (in opossite direction it is ok)
     * Interval::lengthInSeconds(new DateTime('2020-03-29 01:00:00'), new DateTime('2020-03-29 04:00:00')) = 7200
     * ((new DateTime('2020-03-29 01:00:00'))->addSeconds(7200)) = 3:00:00
     * If we use seconds length for adding while transiting from winter to summer time, we need to add 3600s.
     */
    public function getLenghtInSeconds(bool $forAdd = false): ?int
    {
        if ($this->start === null || $this->end === null) {
            return null;
        }
        return self::lengthInSeconds($this->start, $this->end, $forAdd);
    }

    public static function lengthInDays(DateTimeInterface $start, DateTimeInterface $end): int
    {
        return $start->diff($end)->days;
    }

    /**
     * @param bool $forAdd if interval is used for adding, we have to consider winter to summer time transition (in opossite direction it is ok)
     * Interval::lengthInSeconds(new DateTime('2020-03-29 01:00:00'), new DateTime('2020-03-29 04:00:00')) = 7200
     * ((new DateTime('2020-03-29 01:00:00'))->addSeconds(7200)) = 3:00:00
     * If we use seconds length for adding while transiting from winter to summer time, we need to add 3600s.
     * @return int
     */
    public static function lengthInSeconds(DateTimeInterface $start, DateTimeInterface $end, bool $forAdd = false): int
    {
        $diffSeconds = $end->getTimestamp() - $start->getTimestamp();
        if (!$forAdd) {
            return $diffSeconds;
        }
        if ($start->format('I') === "0" && $end->format('I') === "1") {
            $diffSeconds += 3600;
        }

        return $diffSeconds;
    }

    /**
     * @param bool $forAdd if interval is used for adding, we have to consider winter to summer time transition (in opossite direction it is ok)
     * Interval::lengthInSeconds(new DateTime('2020-03-29 01:00:00'), new DateTime('2020-03-29 04:00:00')) = 7200
     * ((new DateTime('2020-03-29 01:00:00'))->addSeconds(7200)) = 3:00:00
     * If we use seconds length for adding while transiting from winter to summer time, we need to add 3600s.
     */
    public static function lengthInMilis(DateTimeInterface $start, DateTimeInterface $end, bool $forAdd = false): int
    {
        return (int)(1000 * self::lengthInSeconds($start, $end, $forAdd));
    }

    public function isOpen(): bool
    {
        return !isset($this->start) || !isset($this->end);
    }
}
