<?php

namespace Spacers\Framework\Exception;
use Spacers\Framework\Constant\HTTP;

class InternalServerErrorExcetion extends \Exception
{
    /**
     * Redefine the exception so message isn't optional
     * @param string $message
     * @param int $code
     * @param \Throwable|null $previous
     */
    public function __construct(string $message, int $code = HTTP::HTTP_INTERNAL_SERVER_ERROR, \Throwable|null $previous = null)
    {
        http_response_code($code); 
        // make sure everything is assigned properly
        parent::__construct("NotFoundExcetion:\n$message", $code, $previous);
    }
}