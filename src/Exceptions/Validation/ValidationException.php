<?php

namespace GloBee\PaymentApi\Exceptions\Validation;

class ValidationException extends \InvalidArgumentException
{
    /**
     * @var array
     */
    private $errors;

    /**
     * ValidationException constructor.
     *
     * @param array  $errors
     * @param string $message
     */
    public function __construct(array $errors, $message = 'Validation Failed')
    {
        parent::__construct($message);
        $this->errors = $errors;
    }

    /**
     * @return array
     */
    public function getErrors()
    {
        return $this->errors;
    }
}
