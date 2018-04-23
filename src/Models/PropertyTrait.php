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

        if (property_exists($this, $name)) {
            return $this->{$name};
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

        if (property_exists($this, $name)) {
            $this->{$name} = $value;

            return;
        }
    }

    /**
     * @param $name
     * @param $arguments
     *
     * @return mixed|void
     */
    public function __call($name, $arguments)
    {
        $mutator = substr($name, 0, 3);
        if ($mutator === 'get') {
            return $this->getProperty(lcfirst(substr($name, 3)));
        }

        if ($mutator === 'set') {
            $this->setProperty(lcfirst(substr($name, 3)), $arguments[0]);

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
