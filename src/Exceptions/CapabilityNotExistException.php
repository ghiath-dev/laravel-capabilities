<?php

namespace Holoultek\Capabilities\Exceptions;

use Exception;
use Throwable;

class CapabilityNotExistException extends Exception
{
    public function __construct($capabilityName, $code = 901, Throwable $previous = null)
    {
        parent::__construct("'$capabilityName' capability is not exist", $code, $previous);
    }
}
