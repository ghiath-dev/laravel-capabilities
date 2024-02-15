<?php

namespace Holoultek\Capabilities\Exceptions;

use Exception;
use Throwable;

class RoleNotExistException extends Exception
{
    public function __construct($message = "Role is not exist", $code = 902, Throwable $previous = null)
    {
        parent::__construct($message, $code, $previous);
    }
}
