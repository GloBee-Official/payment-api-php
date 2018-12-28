<?php

namespace GloBee\PaymentApi\Models;

use GloBee\PaymentApi\Exceptions\UnknownPropertyException;

abstract class Model
{
    /**
     * @param $name
     *
     * @return mixed
     * @throws UnknownPropertyException
     */
    public function __get($name)
    {
        if (property_exists($this, $name)) {
            return $this->{$name};
        }

        throw new UnknownPropertyException($name);
    }
}
