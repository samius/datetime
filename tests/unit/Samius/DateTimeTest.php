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
    
    public function testStatic()
    {
        $mutable = Datetime::now();
        $immutable = DateTimeImmutable::now();
        $this->assertInstanceOf(DateTimeImmutable::class, $immutable);
        $this->assertInstanceOf(DateTimeImmutable::class, $immutable->addSeconds(1));
        $this->assertInstanceOf(DateTime::class, $mutable);
        $this->assertInstanceOf(DateTime::class, $mutable->addSeconds(1));


    }
}
