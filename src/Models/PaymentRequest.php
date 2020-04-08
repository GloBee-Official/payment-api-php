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

    protected function setTotal($total)
    {
        Validator::validateNumberAboveMinimum('total', $total, 0);
        $this->total = $total;
    }

    protected function setCurrency($currency)
    {
        Validator::validateStringLength('currency', $currency, 3);
        $this->currency = strtoupper($currency);
    }

    protected function setCustomer($customerEmail, $customerName = null)
    {
        Validator::validateEmail('customer.email', $customerEmail);
        $this->customerEmail = $customerEmail;
        $this->customerName = $customerName;
    }

    public function setSuccessUrl($successUrl)
    {
        if ($successUrl !== null) {
            Validator::validateUrl('success_url', $successUrl);
        }
        $this->successUrl = $successUrl;
    }

    public function setCancelUrl($cancelUrl)
    {
        if ($cancelUrl !== null) {
            Validator::validateUrl('cancel_url', $cancelUrl);
        }
        $this->cancelUrl = $cancelUrl;
    }

    public function setCallbackData($callbackData)
    {
        $callbackDataString = $callbackData;
        if (!is_string($callbackData)) {
            $callbackDataString = json_encode($callbackData);
        }
        if (strlen($callbackDataString) > 150) {
            throw new ValidationException([], 'Callback Data must be less than 150 characters long.');
        }
        $this->callbackData = $callbackData;
    }

    public function setIpnUrl($ipnUrl)
    {
        if ($ipnUrl !== null) {
            Validator::validateUrl('ipn_url', $ipnUrl);
        }
        $this->ipnUrl = $ipnUrl;
    }

    public function setCustomPaymentId($customPaymentId)
    {
        $this->customPaymentId = $customPaymentId;
    }

    public function setNotificationEmail($notificationEmail)
    {
        if ($notificationEmail !== null) {
            Validator::validateEmail('notification_email', $notificationEmail);
        }
        $this->notificationEmail = $notificationEmail;
    }

    public function setLowRiskConfirmation()
    {
        $this->confirmationSpeed = 'low';
    }

    public function setBalancedConfirmation()
    {
        $this->confirmationSpeed = 'medium';
    }

    public function setQuickConfirmation()
    {
        $this->confirmationSpeed = 'high';
    }

    public function setConfirmationSpeed($confirmationSpeed)
    {
        Validator::validateOptions('confirmation_speed', $confirmationSpeed, ['low', 'medium', 'high']);
        $this->confirmationSpeed = $confirmationSpeed;
    }

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

    // The below methods are deprecated ----------------------------------------------------------------------------- //

    /**
     * @deprecated
     */
    public function withSuccessUrl($successUrl)
    {
        $this->setSuccessUrl($successUrl);
    }

    /**
     * @deprecated
     */
    public function withCancelUrl($cancelUrl)
    {
        $this->setCancelUrl($cancelUrl);
    }

    /**
     * @deprecated
     */
    public function withCallbackData($callbackData)
    {
        $this->setCallbackData($callbackData);
    }

    /**
     * @deprecated
     */
    public function withIpnUrl($ipnUrl)
    {
        $this->setIpnUrl($ipnUrl);
    }

    /**
     * @deprecated
     */
    public function withCustomPaymentId($customPaymentId)
    {
        $this->setCustomPaymentId($customPaymentId);
    }

    /**
     * @deprecated
     */
    public function withNotificationEmail($notificationEmail)
    {
        $this->setNotificationEmail($notificationEmail);
    }

    /**
     * @deprecated
     */
    public function lowRiskConfirmation()
    {
        $this->setLowRiskConfirmation();
    }

    /**
     * @deprecated
     */
    public function balancedConfirmation()
    {
        $this->setBalancedConfirmation();
    }

    /**
     * @deprecated
     */
    public function quickConfirmation()
    {
        $this->setQuickConfirmation();
    }

    /**
     * @deprecated
     */
    public function confirmationSpeed($confirmationSpeed)
    {
        $this->confirmationSpeed($confirmationSpeed);
    }
}
