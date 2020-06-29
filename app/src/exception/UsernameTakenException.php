<?php



namespace yh\a3\exception;

use phpDocumentor\Reflection\Exception;
use Throwable;

class UsernameTakenException extends Exception
{
    /**
     * Username been used Exception constructor.
     * @param string $message
     * @param int $code
     * @param Throwable|null $previous
     */
    public function __construct($message = "", $code = 0, Throwable $previous = null)
    {
        parent::__construct($message, $code, $previous);
    }

    /**
     * @return mixed When be treated as a string
     */
    public function __toString()
    {
        return $this->message;
    }
}
