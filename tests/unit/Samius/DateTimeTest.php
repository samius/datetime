<?php

namespace Samius;


class DateTimeTest extends \Codeception\Test\Unit
{
    public function testNullDate()
    {
        $this->assertTrue(DateTime::createNullDate()->isNullDate());
    }
    
    public function testSetDayOfMonthOrLast()
    {
        $date = new DateTimeImmutable('2020-02-01');
        $this->assertEquals(29, $date->setDayOfMonthOrLast(29)->getDayOfMonth());
        $this->assertEquals(29, $date->setDayOfMonthOrLast(31)->getDayOfMonth());
        $this->assertEquals(29, $date->setDayOfMonthOrLast(29)->getDayOfMonth());
        $this->assertEquals(28, $date->setDayOfMonthOrLast(28)->getDayOfMonth());
    }
}
