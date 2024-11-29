<?php

namespace DTApi\Units;

use DTApi\Helpers\TeHelper;

class WillExpireAtTest extends TestCase {
    public function testExpireFor90Min(){

        $dueTime = Carbon::now()->addMinutes(90)->toDateTimeString();
        $createdAt = Carbon::now()->toDateTimeString();

        $result = YourHelperClass::willExpireAt($dueTime, $createdAt);

        $this->assertEquals(Carbon::parse($dueTime)->format('Y-m-d H:i:s'), $result);
    }

    public function testExpireLessThan24Hours()
    {
        $due_time = Carbon::now()->addHours(5)->toDateTimeString();
        $created_at = Carbon::now()->toDateTimeString();

        $result = YourHelperClass::willExpireAt($due_time, $created_at);

        $expected = Carbon::parse($created_at)->addMinutes(90)->format('Y-m-d H:i:s');
        $this->assertEquals($expected, $result);
    }

    public function testExpireBetween24And72Hours()
    {
        $dueTime = Carbon::now()->addHours(5)->toDateTimeString();
        $createdAt = Carbon::now()->toDateTimeString();

        $result = YourHelperClass::willExpireAt($dueTime, $createdAt);

        $expected = Carbon::parse($createdAt)->addMinutes(90)->format('Y-m-d H:i:s');
        $this->assertEquals($expected, $result);
    }

    public function testExpireMoreThan72Hours()
    {
        
        $dueTime = Carbon::now()->addDays(4)->toDateTimeString();
        $createdAt = Carbon::now()->toDateTimeString();

        $result = YourHelperClass::willExpireAt($dueTime, $createdAt);

        $expected = Carbon::parse($dueTime)->subHours(48)->format('Y-m-d H:i:s');
        $this->assertEquals($expected, $result);
    }

}