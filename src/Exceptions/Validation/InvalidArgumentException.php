<?php

namespace GloBee\PaymentApi\Exceptions\Validation;

class InvalidArgumentException extends ValidationException
{
    /**
     * @var string
     */
    protected $expectedType;

    /**
     * @var string
     */
    protected $actualType;

    /**
     * InvalidArgumentException constructor.
     *
     * @param        $field
     * @param string $expectedType
     * @param mixed  $actual
     */
    public function __construct($field, $expectedType, $actual)
    {
        $this->expectedType = $expectedType;
        $this->actualType = gettype($actual);
        $message = sprintf('Expected %s but found %s.', $this->expectedType, $this->actualType);
        $error = [
            'type' => 'invalid_argument',
            'extra' => [$this->expectedType, $this->actualType],
            'field' => $field,
            'message' => $message,
        ];
        parent::__construct([$error], $message);
    }

    /**
     * @return string
     */
    public function getExpectedType()
    {
        return $this->expectedType;
    }

    /**
     * @return string
     */
    public function getActualType()
    {
        return $this->actualType;
    }
}
