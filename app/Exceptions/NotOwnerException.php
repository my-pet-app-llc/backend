<?php

namespace App\Exceptions;

use Exception;
use Log;
use Symfony\Component\HttpKernel\Exception\HttpExceptionInterface;

class NotOwnerException extends Exception implements HttpExceptionInterface
{
    public function __construct()
    {
        $message = 'You are not pet owner.';
        parent::__construct($message, 401);
    }

    public function getStatusCode()
    {
        return 401;
    }

    public function getHeaders()
    {
        return [];
    }
}
