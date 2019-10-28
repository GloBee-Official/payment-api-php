<?php

namespace GloBee\PaymentApi\Models;

use GloBee\PaymentApi\Exceptions\Validation\ValidationException;

class PaymentRequest extends Model
{
    protected $total;
    protected $currency;
    protected $customPaymentId;
    protected $callbackData;
    protected $customerName;
    protected $customerEmail;
    protected $successUrl;
    protected $cancelUrl;
    protected $ipnUrl;
    protected $notificationEmail;
    protected $confirmationSpeed = 'medium';
    protected $id;
    protected $status;
    protected $redirectUrl;
    protected $expiresAt;
    protected $createdAt;

    public function __construct($customerEmail, $total, $currency = 'USD', $customerName = null)
    {
        $this->setTotal($total);
        $this->setCurrency($currency);
        $this->setCustomer($customerEmail, $customerName);
    }

    /**
     * @param array $data
     *
     * @return PaymentRequest
     */
    public static function fromResponse(array $data)
    {
        $self = new self($data['customer']['email'], $data['total']);

        $callbackData = json_decode($data['callback_data'], true);
        if (json_last_error() !== JSON_ERROR_NONE) {
            $callbackData = $data['callback_data'];
        }
        $self->id = $data['id'];
        $self->status = $data['status'];
        $self->currency = $data['currency'];
        $self->customPaymentId = $data['custom_payment_id'];
        $self->callbackData = $callbackData;
        $self->customerName = $data['customer']['name'];
        $self->redirectUrl = $data['redirect_url'];
        $self->successUrl = $data['success_url'];
        $self->cancelUrl = $data['cancel_url'];
        $self->ipnUrl = $data['ipn_url'];
        $self->notificationEmail = $data['notification_email'];
        $self->confirmationSpeed = $data['confirmation_speed'];
        $self->expiresAt = $data['expires_at'];
        $self->createdAt = $data['created_at'];

        return $self;
    }

    /**
     * @param float $total
     *
     * @throws \GloBee\PaymentApi\Exceptions\Validation\InvalidArgumentException
     * @throws \GloBee\PaymentApi\Exceptions\Validation\BelowMinimumException
     */
    protected function setTotal($total)
    {
        Validator::validateNumberAboveMinimum('total', $total, 0);
        $this->total = $total;
    }

    /**
     * @param string $currency
     *
     * @throws \GloBee\PaymentApi\Exceptions\Validation\ValidationException
     * @throws \GloBee\PaymentApi\Exceptions\Validation\InvalidArgumentException
     */
    protected function setCurrency($currency)
    {
        Validator::validateStringLength('currency', $currency, 3);
        $this->currency = strtoupper($currency);
    }

    /**
     * @param string $customerEmail
     * @param string|null $customerName
     *
     * @throws \GloBee\PaymentApi\Exceptions\Validation\InvalidArgumentException
     * @throws \GloBee\PaymentApi\Exceptions\Validation\InvalidEmailException
     */
    protected function setCustomer($customerEmail, $customerName = null)
    {
        Validator::validateEmail('customer.email', $customerEmail);
        $this->customerEmail = $customerEmail;
        $this->customerName = $customerName;
    }

    /**
     * @param string $successUrl
     *
     * @return self
     * @throws \GloBee\PaymentApi\Exceptions\Validation\InvalidArgumentException
     * @throws \GloBee\PaymentApi\Exceptions\Validation\InvalidUrlException
     */
    public function withSuccessUrl($successUrl)
    {
        if ($successUrl !== null) {
            Validator::validateUrl('success_url', $successUrl);
        }
        $self = clone $this;
        $self->successUrl = $successUrl;

        return $self;
    }

    /**
     * @param string $cancelUrl
     *
     * @return self
     */
    public function withCancelUrl($cancelUrl)
    {
        if ($cancelUrl !== null) {
            Validator::validateUrl('cancel_url', $cancelUrl);
        }
        $self = clone $this;
        $self->cancelUrl = $cancelUrl;

        return $self;
    }

    public function withCallbackData($callbackData)
    {
        $callbackDataString = $callbackData;

        if (!is_string($callbackData)) {
            $callbackDataString = json_encode($callbackData);
        }

        if (strlen($callbackDataString) > 150) {
            throw new ValidationException([], 'Callback Data must be less than 150 characters long.');
        }

        $self = clone $this;
        $self->callbackData = $callbackData;

        return $self;
    }

    /**
     * @param $ipnUrl
     *
     * @return self
     */
    public function withIpnUrl($ipnUrl)
    {
        if ($ipnUrl !== null) {
            Validator::validateUrl('ipn_url', $ipnUrl);
        }
        $self = clone $this;
        $self->ipnUrl = $ipnUrl;

        return $self;
    }

    /**
     * @param string $customPaymentId
     *
     * @return self
     */
    public function withCustomPaymentId($customPaymentId)
    {
        $self = clone $this;
        $self->customPaymentId = $customPaymentId;

        return $self;
    }

    /**
     * @param string $notificationEmail
     *
     * @return self
     */
    public function withNotificationEmail($notificationEmail)
    {
        if ($notificationEmail !== null) {
            Validator::validateEmail('notification_email', $notificationEmail);
        }
        $self = clone $this;
        $self->notificationEmail = $notificationEmail;

        return $self;
    }

    /**
     * @return self
     */
    public function lowRiskConfirmation()
    {
        $self = clone $this;
        $self->confirmationSpeed = 'low';

        return $self;
    }

    /**
     * @return self
     */
    public function balancedConfirmation()
    {
        $self = clone $this;
        $self->confirmationSpeed = 'medium';

        return $self;
    }

    /**
     * @return self
     */
    public function quickConfirmation()
    {
        $self = clone $this;
        $self->confirmationSpeed = 'high';

        return $self;
    }

    public function confirmationSpeed($confirmationSpeed)
    {
        Validator::validateOptions('confirmation_speed', $confirmationSpeed, ['low', 'medium', 'high']);

        $self = clone $this;
        $self->confirmationSpeed = $confirmationSpeed;

        return $self;
    }

    /**
     * @return array
     */
    public function toArray()
    {
        $callbackData = $this->callbackData;
        if (is_array($callbackData)) {
            $callbackData = json_encode($callbackData);
        }

        return [
            'id' => $this->id,
            'status' => $this->status,
            'total' => $this->total,
            'currency' => $this->currency,
            'custom_payment_id' => $this->customPaymentId,
            'callback_data' => $callbackData,
            'customer' => [
                'name' => $this->customerName,
                'email' => $this->customerEmail,
            ],
            'redirect_url' => $this->redirectUrl,
            'success_url' => $this->successUrl,
            'cancel_url' => $this->cancelUrl,
            'ipn_url' => $this->ipnUrl,
            'notification_email' => $this->notificationEmail,
            'confirmation_speed' => $this->confirmationSpeed,
            'expires_at' => $this->expiresAt,
            'created_at' => $this->createdAt,
        ];
    }
}
