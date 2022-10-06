<?php
namespace Samius;

use DateTimeZone;
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

    private static ?DateTime $now = null;

    public function  __construct(string $time = 'now', DateTimeZone $tz = null)
    {
        if (!$time && self::$now) {
            $time = self::$now->format(self::DB_FULL);
            $tz = self::$now->getTimezone();
        }

        if ($tz) {
            parent::__construct($time, $tz);
        } else {
            parent::__construct($time);
        }
    }


    public static function setNow(self $now = null):void
    {
        if ($now) {
            self::$now = clone($now);
        } else {
            self::$now = $now;
        }
    }

    public static function hasTestNow(): bool
    {
        return self::$now !== null;
    }

}
