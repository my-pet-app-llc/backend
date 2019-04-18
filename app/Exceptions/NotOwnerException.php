<?php

namespace App\Exceptions;

use Exception;
use Log;
use Symfony\Component\HttpKernel\Exception\HttpExceptionInterface;

class NotOwnerException extends Exception implements HttpExceptionInterface
{
    private $statusCode;

    public function __construct($message = 'You are not pet owner.', $statusCode = 401)
    {
        $this->statusCode = $statusCode;

        parent::__construct($message, $statusCode);
    }

    public function getStatusCode()
    {
        return $this->statusCode;
    }

    public function getHeaders()
    {
        return [];
    }
}
