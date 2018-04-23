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
    use PropertyTrait;

    protected $total = 0.0;

    protected $currency = 'USD';

    protected $customPaymentId;

    protected $callbackData;

    protected $customerName;

    protected $customerEmail;

    protected $successUrl;

    protected $cancelUrl;

    protected $ipnUrl;

    protected $notificationEmail;

    protected $confirmationSpeed = 'medium';

    private $id;

    private $status;

    private $redirectUrl;

    private $expiresAt;

    private $createdAt;

    public static function fromResponse(array $data)
    {
        $self = new self();

        $self->id = $data['id'];
        $self->status = $data['status'];
        $self->total = $data['total'];
        $self->currency = $data['currency'];
        $self->customPaymentId = $data['custom_payment_id'];
        $self->callbackData = $data['callback_data'];
        $self->customerName = $data['customer']['name'];
        $self->customerEmail = $data['customer']['email'];
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
    public function setTotal($total)
    {
        $this->validateNumberAboveMinimum('total', $total, 0);
        $this->total = $total;
    }

    /**
     * @param mixed $currency
     *
     * @throws \GloBee\PaymentApi\Exceptions\Validation\ValidationException
     * @throws \GloBee\PaymentApi\Exceptions\Validation\InvalidArgumentException
     */
    public function setCurrency($currency)
    {
        $this->validateStringLength('currency', $currency, 3);
        $this->currency = strtoupper($currency);
    }

    /**
     * @param mixed $customerEmail
     *
     * @throws \GloBee\PaymentApi\Exceptions\Validation\InvalidArgumentException
     * @throws \GloBee\PaymentApi\Exceptions\Validation\InvalidEmailException
     */
    public function setCustomerEmail($customerEmail)
    {
        $this->validateEmail('customer.email', $customerEmail);
        $this->customerEmail = $customerEmail;
    }

    /**
     * @param mixed $successUrl
     *
     * @throws \GloBee\PaymentApi\Exceptions\Validation\InvalidArgumentException
     * @throws \GloBee\PaymentApi\Exceptions\Validation\InvalidUrlException
     */
    public function setSuccessUrl($successUrl)
    {
        if ($successUrl !== null) {
            $this->validateUrl('success_url', $successUrl);
        }
        $this->successUrl = $successUrl;
    }

    /**
     * @param mixed $cancelUrl
     */
    public function setCancelUrl($cancelUrl)
    {
        if ($cancelUrl !== null) {
            $this->validateUrl('cancel_url', $cancelUrl);
        }
        $this->cancelUrl = $cancelUrl;
    }

    /**
     * @param mixed $ipnUrl
     */
    public function setIpnUrl($ipnUrl)
    {
        if ($ipnUrl !== null) {
            $this->validateUrl('ipn_url', $ipnUrl);
        }
        $this->ipnUrl = $ipnUrl;
    }

    /**
     * @param mixed $notificationEmail
     */
    public function setNotificationEmail($notificationEmail)
    {
        if ($notificationEmail !== null) {
            $this->validateEmail('notification_email', $notificationEmail);
        }
        $this->notificationEmail = $notificationEmail;
    }

    /**
     * @param mixed $confirmationSpeed
     *
     * @throws \GloBee\PaymentApi\Exceptions\Validation\InvalidSelectionException
     */
    public function setConfirmationSpeed($confirmationSpeed)
    {
        $this->validateOptions('confirmation_speed', $confirmationSpeed, ['low', 'medium', 'high']);
        $this->confirmationSpeed = $confirmationSpeed;
    }

    public function toArray()
    {
        return [
            'id' => $this->id,
            'status' => $this->status,
            'total' => $this->total,
            'currency' => $this->currency,
            'custom_payment_id' => $this->customPaymentId,
            'callback_data' => $this->callbackData,
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

    public function isValid()
    {
        return $this->total > 0 && null !== $this->customerEmail;
    }

    public function exists()
    {
        return $this->id !== null;
    }
}
