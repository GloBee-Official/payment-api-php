<?php

namespace GloBee\PaymentApi\Exceptions\Validation;

class InvalidSelectionException extends ValidationException
{
    /**
     * @var array
     */
    private $options;

    /**
     * InvalidSelectionException constructor.
     *
     * @param string $field
     * @param mixed  $value
     * @param array $options
     */
    public function __construct($field, $value, array $options)
    {
        $this->options = $options;
        $message = sprintf('The selected %s is invalid.', $field);
        $error = [
            'type' => 'invalid_selection',
            'extra' => $options,
            'field' => $field,
            'message' => $message,
        ];
        parent::__construct([$error], $message);
    }

    /**
     * @return array
     */
    public function getValidOptions()
    {
        return $this->options;
    }
}
