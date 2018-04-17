<?php

namespace GloBee\PaymentApi\Exceptions\Validation;

class BelowMinimumException extends ValidationException
{
    private $minimum;

    /**
     * BelowMinimumException constructor.
     *
     * @param string $field
     * @param mixed  $value
     * @param int    $minimum
     */
    public function __construct($field, $value, $minimum)
    {
        $this->minimum = $minimum;
        $message = sprintf('The %s must be at least %.2f', $field, $minimum);
        $error = [
            'type' => 'below_minimum',
            'extra' => [$minimum],
            'field' => $field,
            'message' => $message,
        ];
        parent::__construct([$error], $message);
    }

    /**
     * @return mixed
     */
    public function getMinimum()
    {
        return $this->minimum;
    }
}
