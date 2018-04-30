<?php

namespace GloBee\PaymentApi\Models;

trait PropertyTrait
{
    /**
     * @param $name
     *
     * @return mixed
     */
    public function getProperty($name)
    {
        $methodName = 'get'.$this->strToStudlyCase($name);
        if (method_exists($this, $methodName)) {
            return $this->{$methodName}();
        }

        if (array_key_exists($name, $this->properties)) {
            return $this->properties[$name];
        }

        if (array_key_exists($name, $this->readonlyProperties)) {
            return $this->readonlyProperties[$name];
        }
    }

    /**
     * @param $name
     * @param $value
     */
    protected function setProperty($name, $value)
    {
        $methodName = 'set'.$this->strToStudlyCase($name);
        if (method_exists($this, $methodName)) {
            $this->{$methodName}($value);

            return;
        }

        if (array_key_exists($name, $this->properties)) {
            $this->properties[$name] = $value;

            return;
        }
    }

    public function __get($name)
    {
        return $this->getProperty($name);
    }

    public function __set($name, $value)
    {
        $this->setProperty($name, $value);
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
