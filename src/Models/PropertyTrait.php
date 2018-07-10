<?php

namespace GloBee\PaymentApi\Models;

use GloBee\PaymentApi\Exceptions\LockedPropertyException;
use GloBee\PaymentApi\Exceptions\UnknownPropertyException;

trait PropertyTrait
{
    /**
     * @param $name
     *
     * @return mixed
     * @throws UnknownPropertyException
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

        throw new UnknownPropertyException($name);
    }

    /**
     * @param $name
     * @param $value
     *
     * @throws LockedPropertyException
     * @throws UnknownPropertyException
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

        if (array_key_exists($name, $this->readonlyProperties)) {
            throw new LockedPropertyException($name);
        }

        throw new UnknownPropertyException($name);
    }

    /**
     * @param $name
     *
     * @return mixed
     * @throws UnknownPropertyException
     */
    public function __get($name)
    {
        return $this->getProperty($name);
    }

    /**
     * @param $name
     * @param $value
     *
     * @throws LockedPropertyException
     * @throws UnknownPropertyException
     */
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
