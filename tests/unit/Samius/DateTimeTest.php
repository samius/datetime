<?php

namespace Samius;


class DateTimeTest extends \Codeception\Test\Unit
{
    public function testNullDate()
    {
        $this->assertTrue(DateTime::createNullDate()->isNullDate());
    }
}
