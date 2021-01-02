<?php

namespace App\Helper;

use DateTime;
use DateTimeInterface;
use Symfony\Component\Messenger\Stamp\DelayStamp;

/**
 * Class DelayStampHelper
 */
class DelayStampHelper
{
    /**
     * @param DateTimeInterface $dateTime
     * @return DelayStamp
     */
    public function __invoke(DateTimeInterface $dateTime): DelayStamp
    {
        $now = new DateTime();
        $delay = (($dateTime->getTimestamp() - $now->getTimestamp()) ?: 0) * 1000;

        return new DelayStamp($delay);
    }
}