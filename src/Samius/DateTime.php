<?php
namespace Samius;

use Samius\DateTime\Traits\FactoryTrait;
use Samius\DateTime\Traits\FormatterTrait;
use Samius\DateTime\Traits\ModifierTrait;

/**
 * @author samius
 */
class DateTime extends \DateTime implements DateTimeInterface
{
    use ModifierTrait;
    use FormatterTrait;
    use FactoryTrait;

    /**
     * @var DateTime
     */
    private static $now;

    public function  __construct($time = null, \DateTimeZone $tz = null)
    {
        if (!$time && self::$now) {
            $time = self::now()->format(self::DB_FULL);
            $tz = self::now()->getTimezone();
        }

        if ($tz) {
            parent::__construct($time, $tz);
        } else {
            parent::__construct($time);
        }
    }

    /**
     * Zkratka, aby slo s datem pracovat v jedom prikazu a nemuselo se preukladat do promenne apod.
     * @static
     * @return DateTime
     */
    public static function now()
    {
        if (isset (self::$now)) {
            return clone(self::$now);
        }
        return new static();
    }

    /**
     * @param DateTime|null $now
     */
    public static function setNow(self $now = null)
    {
        if ($now) {
            self::$now = clone($now);
        } else {
            self::$now = $now;
        }
    }

}
