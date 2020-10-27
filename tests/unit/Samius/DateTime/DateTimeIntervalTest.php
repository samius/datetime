<?php
namespace Samius\DateTime;
use Samius\DateTime\Interval;
use Samius\DateTimeImmutable;

class DateTimeIntervalTest extends \Codeception\Test\Unit
{
    /**
     * @var \UnitTester
     */
    protected $tester;
    
    protected function _before()
    {
    }

    protected function _after()
    {
    }

    // tests
    public function testLengthForDstTransition()
    {
        $date1 = new DateTimeImmutable('2020-03-29 00:00:00');
        $this->assertFalse($date1->isDst());
        $date2 = new DateTimeImmutable('2020-03-29 05:00:00');
        $this->assertTrue($date2->isDst());

        //shift from 2:00 to 3:00. Difference is not 5h, but only 4h
        $originalLength= Interval::lengthInSeconds($date1, $date2, false);
        $length = Interval::lengthInSeconds($date1, $date2, true);
        $this->assertEquals(4*3600, $originalLength);
        $this->assertEquals(4*3600+3600, $length);
        //when we add this diff to original time, we must get the second time (date2)
        $this->assertEquals($date2, $date1->addSeconds($length));


        $date1 = new DateTimeImmutable('2020-10-25 00:00:00');
        $this->assertTrue($date1->isDst());
        $date2 = new DateTimeImmutable('2020-10-25 04:00:00');
        $this->assertFalse($date2->isDst());
        $length = Interval::lengthInSeconds($date1, $date2);
        $this->assertEquals(5 * 3600, $length);
        //when we add this diff to original time, we must get the second time (date2)
        $this->assertEquals($date2, $date1->addSeconds($length));

    }
}
