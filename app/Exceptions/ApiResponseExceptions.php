<?php


namespace App\Exceptions;


class ApiResponseExceptions extends \Exception
{
    public function __construct(string $message)
    {
        parent::__construct($message);
    }
}