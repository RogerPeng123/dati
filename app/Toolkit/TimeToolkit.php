<?php


namespace App\Toolkit;


final class TimeToolkit
{
    public static function getDayStarAndEnd(): array
    {
        $beginToday = mktime(0, 0, 0, date('m'), date('d'), date('Y'));

        $endToday = mktime(0, 0, 0, date('m'),
                date('d') + 1, date('Y')) - 1;

        return [date('Y-m-d H:i:s', $beginToday), date('Y-m-d H:i:s', $endToday)];
    }

}