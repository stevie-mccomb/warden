<?php

namespace Stevie\Warden\Exceptions;

use Exception;

class InvalidRoleException extends Exception
{
    /**
     * The error message to display when this exception is thrown.
     */
    protected $message = "The given role is not one of the authenticatable's potential roles.";
}
