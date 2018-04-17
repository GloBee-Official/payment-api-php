<?php

namespace GloBee\PaymentApi\Exceptions\Validation;

class InvalidUrlException extends ValidationException
{
    public function __construct($field, $url)
    {
        $message = sprintf('The %s format is invalid.', $field);
        $error = [
            'type' => 'invalid_url',
            'extra' => null,
            'field' => $field,
            'message' => $message,
        ];
        parent::__construct([$error], $message);
    }
}
