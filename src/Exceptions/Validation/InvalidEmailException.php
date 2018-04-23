<?php

namespace GloBee\PaymentApi\Exceptions\Validation;

class InvalidEmailException extends ValidationException
{
    /**
     * InvalidEmailException constructor.
     *
     * @param string $field
     * @param string $email
     */
    public function __construct($field, $email)
    {
        $message = sprintf('The %s must be a valid email address.', $field);
        $error = [
            'type' => 'invalid_email',
            'extra' => null,
            'field' => $field,
            'message' => $message,
        ];
        parent::__construct([$error], $message);
    }
}
