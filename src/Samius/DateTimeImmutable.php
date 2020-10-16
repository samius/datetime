<?php


namespace Samius;

use Samius\DateTime\Traits\FactoryTrait;
use Samius\DateTime\Traits\FormatterTrait;
use Samius\DateTime\Traits\ModifierTrait;

class DateTimeImmutable extends \DateTimeImmutable implements DateTimeInterface
{
    use FormatterTrait;
    use ModifierTrait;
    use FactoryTrait;

    public function __construct($time = null, $timezone = NULL)
       {
           if (!$time && DateTime::hasTestNow()) {
               $mutable = DateTime::now();
               parent::__construct($mutable->format(self::DB_FULL_MICRO), $mutable->getTimezone());
           } else {
               if (!$time) {
                   $time = 'now';
               }
               if ($timezone) {
                   parent::__construct($time, $timezone);
               } else {
                   parent::__construct($time);
               }
           }
       }
}
