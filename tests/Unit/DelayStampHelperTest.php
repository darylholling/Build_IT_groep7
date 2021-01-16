<?php

namespace App\Tests\Unit;

use App\Helper\DelayStampHelper;
use DateTime;
use Symfony\Bundle\FrameworkBundle\Test\KernelTestCase;
use Symfony\Component\Messenger\Stamp\DelayStamp;

/**
 * Class DelayStampHelperTest
 */
class DelayStampHelperTest extends KernelTestCase
{
    public function setUp(): void
    {
        self::bootKernel();
    }

    public function testIfDelayStampCalculatesCorrectMilliseconds()
    {
        $fifteenMinuteDelay = new DateTime('+15 minutes');

        $delayStampWithDelay = (new DelayStampHelper)($fifteenMinuteDelay);

        $delayStamp = new DelayStamp(900000);
        $this->assertEquals($delayStamp->getDelay(), $delayStampWithDelay->getDelay());
    }

    public function testIfDelayStampReturnsCorrectMinutes()
    {
        $fifteenMinuteDelay = new DateTime('+15 minutes');
        $delayStampWithDelay = (new DelayStampHelper)($fifteenMinuteDelay);
        $minutes = (($delayStampWithDelay->getDelay() / 1000) / 60);

        $this->assertEquals(15, $minutes);
    }

    public function testIfDelayStampReturnsDelayStamp()
    {
        $delayStampWithDelay = (new DelayStampHelper)(new DateTime());

        $this->assertTrue($delayStampWithDelay instanceof DelayStamp);
    }

    protected function tearDown(): void
    {
        parent::tearDown();
    }
}