<?php


namespace App\Toolkit;


final class MemberToolkit
{
    public static function conversionCover(string $cover = ""): string
    {
        if (empty($cover)) {
            return env('APP_URL') . env('DEFAULT_MEMNER_COVER');
        } else {
            return env('APP_URL') . $cover;
        }
    }

}