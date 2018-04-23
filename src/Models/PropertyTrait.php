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

    public function __get($name)
    {
        $methodName = 'get'.$this->strToStudlyCase($name);
        if (method_exists($this, $methodName)) {
            return $this->{$methodName}();
        }
    }

    public function __set($name, $value)
    {
        $methodName = 'set'.$this->strToStudlyCase($name);
        if (method_exists($this, $methodName)) {
            $this->{$methodName}($value);

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
