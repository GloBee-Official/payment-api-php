<?php

namespace GloBee\PaymentApi\Exceptions;

class LockedPropertyException extends \Exception
{
    public function __construct($propertyName)
    {
        parent::__construct('Property "'.$propertyName.'" is locked and can\'t be modified.');
    }
}
