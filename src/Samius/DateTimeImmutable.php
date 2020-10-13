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

    public static function now()
    {
        return new static();
    }
}
