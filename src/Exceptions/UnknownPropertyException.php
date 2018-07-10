<?php

namespace GloBee\PaymentApi\Exceptions;

class UnknownPropertyException extends \Exception
{
    public function __construct($propertyName)
    {
        parent::__construct('Unknown Property: '.$propertyName);
    }
}
