<?php

class DateHelper
{
    public static function toStartOfDay(DateTime $dateTime): DateTime
    {
        $newDateTime = clone $dateTime;
        $newDateTime->setTime(0, 0);

        return $newDateTime;
    }

    public static function toEndOfDay(DateTime $dateTime): DateTime
    {
        $newDateTime = clone $dateTime;
        $newDateTime->setTime(23, 59, 59);

        return $newDateTime;
    }

    public static function toStartOfNextDay(DateTime $dateTime): DateTime
    {
        $newDateTime = clone $dateTime;
        $newDateTime->setTime(0, 0)->add(DateInterval::createFromDateString('1 day'));

        return $newDateTime;
    }

    public static function toEndOfPreviousDay(DateTime $dateTime): DateTime
    {
        $newDateTime = clone $dateTime;
        $newDateTime->setTime(23, 59, 59)->add(DateInterval::createFromDateString('- 1 day'));

        return $newDateTime;
    }
}
