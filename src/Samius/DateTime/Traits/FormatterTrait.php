<?php


namespace Samius\DateTime\Traits;


use Samius\DateTimeInterface;

trait FormatterTrait
{
    /**
     * Vraci pocet minut od pulnoci
     * @return int
     */
    public function getMinuteInDay():int
    {
        $hours = (int)$this->format('H');
        $minutes = (int)$this->format('i');

        return $hours * 60 + $minutes;
    }


    /**
     * @return int cislo dne v tydnu (1=monday, 7=sunday)
     */
    public function getDayOfWeek():string
    {
        return $this->format('N');
    }

    /**
     * @return bool
     */
    public function isWeekend():bool
    {
        $dayOfWeek = $this->format('w');

        return $dayOfWeek == 0 || $dayOfWeek == 6;
    }

    /**
     * Vrati lidsky citelny nazev mesice
     *
     * @param int $monthNumber cislo mesice
     * @param int $inflect v kolikatem pade chci nazev (napr. 1 = cerven, 2=cervna)
     *
     * @return string
     */
    public function getMonthHumanName($monthNumber = null, $inflect = 1):string
    {
        if (!$monthNumber) {
            $monthNumber = $this->format('n');
        }

        switch ($monthNumber) {
            case 1:
                $month = ($inflect == 1) ? 'leden' : 'ledna';
                break;
            case 2:
                $month = ($inflect == 1) ? 'únor' : 'února';
                break;
            case 3:
                $month = ($inflect == 1) ? 'březen' : 'března';
                break;
            case 4:
                $month = ($inflect == 1) ? 'duben' : 'dubna';
                break;
            case 5:
                $month = ($inflect == 1) ? 'květen' : 'května';
                break;
            case 6:
                $month = ($inflect == 1) ? 'červen' : 'června';
                break;
            case 7:
                $month = ($inflect == 1) ? 'červenec' : 'července';
                break;
            case 8:
                $month = ($inflect == 1) ? 'srpen' : 'srpna';
                break;
            case 9:
                $month = ($inflect == 1) ? 'září' : 'září';
                break;
            case 10:
                $month = ($inflect == 1) ? 'říjen' : 'října';
                break;
            case 11:
                $month = ($inflect == 1) ? 'listopad' : 'listopadu';
                break;
            case 12:
                $month = ($inflect == 1) ? 'prosinec' : 'prosince';
                break;
            default:
                throw new \InvalidArgumentException("neexistujici mesic $monthNumber");
        }

        return $month;
    }

    /**
     * @return string
     */
    public function __toString():string
    {
        return $this->format(self::HUMAN_FULL);
    }

    /**
     * @return string
     */
    public function getDbDate():string
    {
        return $this->format(self::DB_DATE);
    }

    /**
     * @return string
     */
    public function getDbDatetime():string
    {
        return $this->format(self::DB_FULL);
    }

    /**
     * @return string
     */
    public function getHumanDate():string
    {
        return $this->format(self::HUMAN_DATE);
    }

    /**
     * @return string
     */
    public function getYearmonth():string
    {
        return $this->format(self::YEARMONTH);
    }

    /**
     * Vraci cislo predchoziho dne
     * @param $dayNum
     * @return int
     */
    public static function getPreviousDayNum($dayNum):int
    {
        if ($dayNum == 1) {
            return 7;
        } else {
            return $dayNum - 1;
        }
    }

    /**
     * Checks string whether it can be a yearmonth (e.g.201901)
     * @param $yearmonth
     * @return bool
     */
    public static function isYearmonth($yearmonth):bool
    {
        $yearmonthString = (string)$yearmonth;
        if (!preg_match('#\d{6}#', $yearmonthString)) {
            return false;
        }
        $month = substr($yearmonthString, 4);
        if (strlen($month) != 2 || (int)$month > 12 || (int)$month < 1) {
            return false;
        }

        return true;
    }

    /**
     * Vraci timestamp v milisekundach
     * @return int
     */
    public function getMilis():int
    {
        return $this->getTimestamp() * 1000;
    }

    /**
     * If timestamp == 0, return true.
     * Else return false
     * @return bool
     */
    public function isNullDate():bool
    {
        return $this->getTimestamp() == 0;
    }

    /**
     * return bool
     */
    public function isToday():bool
    {
        $timezone = $this->getTimezone();
        $now = new static(null, $timezone);

        return $this->getDbDate() == $now->getDbDate();
    }


    /**
     * @return bool
     */
    public function isYesterday():bool
    {
        $timezone = $this->getTimezone();
        $yesterday = new static(null, $timezone);
        $yesterday->subPart(1, self::PART_DAY);

        return $this->getDbDate() == $yesterday->getDbDate();
    }

    public function isDst():bool
    {
        return $this->format('I') === "1";
    }
}
