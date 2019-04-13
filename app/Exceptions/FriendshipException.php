<?php

namespace App\Exceptions;

use Exception;
use Symfony\Component\HttpKernel\Exception\HttpExceptionInterface;

class FriendshipException extends Exception implements HttpExceptionInterface
{
    protected $statusCode;

    public function __construct($message = '', $statusCode = 403)
    {
        $this->statusCode = $statusCode;

        parent::__construct($message);
    }

    public function getStatusCode()
    {
        return $this->statusCode;
    }

    public function getHeaders()
    {
        return [
            'Content-Type' => 'application/json'
        ];
    }
}
