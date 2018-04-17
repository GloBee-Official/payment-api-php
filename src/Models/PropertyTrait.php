<?php

namespace GloBee\PaymentApi\Models;

trait PropertyTrait
{
    /**
     * @param $key
     * @param $value
     */
    protected function setProperty($key, $value)
    {
        $key = $this->strToStudlyCase($key);
        if ($value !== null && method_exists($this, 'set'.$key)) {
            $this->{'set'.$key}($value);

            return;
        }
        $key[0] = strtolower($key[0]);
        if (property_exists($this, $key)) {
            $this->{$key} = $value;

            return;
        }
    }

    /**
     * @param $key
     *
     * @return mixed
     */
    protected function strToStudlyCase($key)
    {
        return str_replace(' ', '', ucwords(str_replace('_', ' ', $key)));
    }
}
