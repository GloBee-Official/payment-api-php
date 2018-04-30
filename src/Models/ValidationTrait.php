<?php

namespace GloBee\PaymentApi\Models;

use GloBee\PaymentApi\Exceptions\Validation\BelowMinimumException;
use GloBee\PaymentApi\Exceptions\Validation\InvalidArgumentException;
use GloBee\PaymentApi\Exceptions\Validation\InvalidEmailException;
use GloBee\PaymentApi\Exceptions\Validation\InvalidSelectionException;
use GloBee\PaymentApi\Exceptions\Validation\InvalidUrlException;
use GloBee\PaymentApi\Exceptions\Validation\ValidationException;

trait ValidationTrait
{
    /**
     * @param $string
     *
     * @throws \GloBee\PaymentApi\Exceptions\Validation\InvalidArgumentException
     */
    protected function validateString($field, $string)
    {
        if (!is_string($string)) {
            throw new InvalidArgumentException($field, 'string', $string);
        }
    }

    /**
     * @param string $customerEmail
     *
     * @throws \GloBee\PaymentApi\Exceptions\Validation\InvalidArgumentException
     * @throws \GloBee\PaymentApi\Exceptions\Validation\InvalidEmailException
     */
    protected function validateEmail($field, $customerEmail)
    {
        $this->validateString($field, $customerEmail);
        if (filter_var($customerEmail, FILTER_VALIDATE_EMAIL) === false) {
            throw new InvalidEmailException($field, $customerEmail);
        }
    }

    /**
     * @param string $string
     * @param int    $length
     *
     * @throws \GloBee\PaymentApi\Exceptions\Validation\InvalidArgumentException
     * @throws \GloBee\PaymentApi\Exceptions\Validation\ValidationException
     */
    protected function validateStringLength($field, $string, $length)
    {
        $this->validateString($field, $string);
        if (strlen($string) !== $length) {
            throw new ValidationException([], 'Currency must be a 3 character string.');
        }
    }

    /**
     * @param $value
     *
     * @throws \GloBee\PaymentApi\Exceptions\Validation\InvalidArgumentException
     */
    protected function validateNumeric($field, $value)
    {
        if (!is_numeric($value)) {
            throw new InvalidArgumentException($field, 'number', gettype($value));
        }
    }

    /**
     * @param string $field
     * @param        $value
     * @param        $minimum
     *
     * @throws \GloBee\PaymentApi\Exceptions\Validation\BelowMinimumException
     * @throws \GloBee\PaymentApi\Exceptions\Validation\InvalidArgumentException
     */
    protected function validateNumberAboveMinimum($field, $value, $minimum)
    {
        $this->validateNumeric($field, $value);
        if ($value <= $minimum) {
            throw new BelowMinimumException($field, $value, $minimum);
        }
    }

    /**
     * @param string $field
     * @param string $url
     *
     * @throws \GloBee\PaymentApi\Exceptions\Validation\InvalidUrlException
     * @throws \GloBee\PaymentApi\Exceptions\Validation\InvalidArgumentException
     */
    protected function validateUrl($field, $url)
    {
        $this->validateString($field, $url);
        if (filter_var($url, FILTER_VALIDATE_URL) === false) {
            throw new InvalidUrlException($field, $url);
        }
    }

    /**
     * @param $value
     * @param $validOptions
     *
     * @throws \GloBee\PaymentApi\Exceptions\Validation\InvalidSelectionException
     */
    protected function validateOptions($field, $value, $validOptions)
    {
        if (!in_array($value, $validOptions, true)) {
            throw new InvalidSelectionException($field, $value, $validOptions);
        }
    }
}
