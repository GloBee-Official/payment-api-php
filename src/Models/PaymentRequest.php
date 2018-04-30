<?php

namespace GloBee\PaymentApi\Models;

/**
 * @property string $id
 * @property string $status
 * @property float $total
 * @property string $currency
 * @property string $customPaymentId
 * @property mixed $callbackData
 * @property string $customerName
 * @property string $customerEmail
 * @property string $redirectUrl
 * @property string $successUrl
 * @property string $cancelUrl
 * @property string $ipnUrl
 * @property string $notificationEmail
 * @property string $confirmationSpeed
 * @property string $expiresAt
 * @property string $createdAt
 */
class PaymentRequest extends Model
{
    use ValidationTrait;

    protected $properties = [
        'total' => 0.0,
        'currency' => 'USD',
        'customPaymentId' => null,
        'callbackData' => null,
        'customerName' => null,
        'customerEmail' => null,
        'successUrl' => null,
        'cancelUrl' => null,
        'ipnUrl' => null,
        'notificationUrl' => null,
        'confirmationSpeed' => 'medium',
    ];

    protected $readonlyProperties = [
        'id' => null,
        'status' => null,
        'redirectUrl' => null,
        'expiresAt' => null,
        'createdAt' => null,
    ];

    /**
     * @param array $data
     *
     * @return PaymentRequest
     */
    public static function fromResponse(array $data)
    {
        $self = new self();

        $callbackData = json_decode($data['callback_data'], true);
        if (json_last_error() !== JSON_ERROR_NONE) {
            $callbackData = $data['callback_data'];
        }
        $self->readonlyProperties['id'] = $data['id'];
        $self->readonlyProperties['status'] = $data['status'];
        $self->properties['total'] = $data['total'];
        $self->properties['currency'] = $data['currency'];
        $self->properties['customPaymentId'] = $data['custom_payment_id'];
        $self->properties['callbackData'] = $callbackData;
        $self->properties['customerName'] = $data['customer']['name'];
        $self->properties['customerEmail'] = $data['customer']['email'];
        $self->readonlyProperties['redirectUrl'] = $data['redirect_url'];
        $self->properties['successUrl'] = $data['success_url'];
        $self->properties['cancelUrl'] = $data['cancel_url'];
        $self->properties['ipnUrl'] = $data['ipn_url'];
        $self->properties['notificationEmail'] = $data['notification_email'];
        $self->properties['confirmationSpeed'] = $data['confirmation_speed'];
        $self->readonlyProperties['expiresAt'] = $data['expires_at'];
        $self->readonlyProperties['createdAt'] = $data['created_at'];

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
        $this->validateNumberAboveMinimum('total', $total, 0);
        $this->properties['total'] = $total;
    }

    /**
     * @param mixed $currency
     *
     * @throws \GloBee\PaymentApi\Exceptions\Validation\ValidationException
     * @throws \GloBee\PaymentApi\Exceptions\Validation\InvalidArgumentException
     */
    protected function setCurrency($currency)
    {
        $this->validateStringLength('currency', $currency, 3);
        $this->properties['currency'] = strtoupper($currency);
    }

    /**
     * @param mixed $customerEmail
     *
     * @throws \GloBee\PaymentApi\Exceptions\Validation\InvalidArgumentException
     * @throws \GloBee\PaymentApi\Exceptions\Validation\InvalidEmailException
     */
    protected function setCustomerEmail($customerEmail)
    {
        $this->validateEmail('customer.email', $customerEmail);
        $this->properties['customerEmail'] = $customerEmail;
    }

    /**
     * @param mixed $successUrl
     *
     * @throws \GloBee\PaymentApi\Exceptions\Validation\InvalidArgumentException
     * @throws \GloBee\PaymentApi\Exceptions\Validation\InvalidUrlException
     */
    protected function setSuccessUrl($successUrl)
    {
        if ($successUrl !== null) {
            $this->validateUrl('success_url', $successUrl);
        }
        $this->properties['successUrl'] = $successUrl;
    }

    /**
     * @param mixed $cancelUrl
     */
    protected function setCancelUrl($cancelUrl)
    {
        if ($cancelUrl !== null) {
            $this->validateUrl('cancel_url', $cancelUrl);
        }
        $this->properties['cancelUrl'] = $cancelUrl;
    }

    /**
     * @param mixed $ipnUrl
     */
    protected function setIpnUrl($ipnUrl)
    {
        if ($ipnUrl !== null) {
            $this->validateUrl('ipn_url', $ipnUrl);
        }
        $this->properties['ipnUrl'] = $ipnUrl;
    }

    /**
     * @param mixed $notificationEmail
     */
    protected function setNotificationEmail($notificationEmail)
    {
        if ($notificationEmail !== null) {
            $this->validateEmail('notification_email', $notificationEmail);
        }
        $this->properties['notificationEmail'] = $notificationEmail;
    }

    /**
     * @param mixed $confirmationSpeed
     *
     * @throws \GloBee\PaymentApi\Exceptions\Validation\InvalidSelectionException
     */
    protected function setConfirmationSpeed($confirmationSpeed)
    {
        $this->validateOptions('confirmation_speed', $confirmationSpeed, ['low', 'medium', 'high']);
        $this->properties['confirmationSpeed'] = $confirmationSpeed;
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

    /**
     * @return bool
     */
    public function isValid()
    {
        return $this->total > 0 && null !== $this->customerEmail;
    }

    /**
     * @return bool
     */
    public function exists()
    {
        return $this->id !== null;
    }
}
