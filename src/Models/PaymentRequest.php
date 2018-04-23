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

        foreach ($data as $key => $value) {
            if (is_array($value)) {
                foreach ($value as $subKey => $subValue) {
                    if ($key === 'customer') {
                        $self->setProperty('customer_'.$subKey, $subValue);
                    }
                }
                continue;
            }
            $self->setProperty($key, $value);
        }

        return $self;
    }

    /**
     * @return mixed
     */
    public function getTotal()
    {
        return $this->total;
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
     * @return mixed
     */
    public function getCurrency()
    {
        return $this->currency;
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
     * @return mixed
     */
    public function getCustomPaymentId()
    {
        return $this->customPaymentId;
    }

    /**
     * @param mixed $customPaymentId
     */
    public function setCustomPaymentId($customPaymentId)
    {
        $this->customPaymentId = $customPaymentId;
    }

    /**
     * @return mixed
     */
    public function getCallbackData()
    {
        return $this->callbackData;
    }

    /**
     * @param mixed $callbackData
     */
    public function setCallbackData($callbackData)
    {
        $this->callbackData = $callbackData;
    }

    /**
     * @return mixed
     */
    public function getCustomerName()
    {
        return $this->customerName;
    }

    /**
     * @param mixed $customerName
     */
    public function setCustomerName($customerName)
    {
        $this->customerName = $customerName;
    }

    /**
     * @return mixed
     */
    public function getCustomerEmail()
    {
        return $this->customerEmail;
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
     * @return mixed
     */
    public function getSuccessUrl()
    {
        return $this->successUrl;
    }

    /**
     * @param mixed $successUrl
     *
     * @throws \GloBee\PaymentApi\Exceptions\Validation\InvalidArgumentException
     * @throws \GloBee\PaymentApi\Exceptions\Validation\InvalidUrlException
     */
    public function setSuccessUrl($successUrl)
    {
        $this->validateUrl('success_url', $successUrl);
        $this->successUrl = $successUrl;
    }

    /**
     * @return mixed
     */
    public function getCancelUrl()
    {
        return $this->cancelUrl;
    }

    /**
     * @param mixed $cancelUrl
     */
    public function setCancelUrl($cancelUrl)
    {
        $this->validateUrl('cancel_url', $cancelUrl);
        $this->cancelUrl = $cancelUrl;
    }

    /**
     * @return mixed
     */
    public function getIpnUrl()
    {
        return $this->ipnUrl;
    }

    /**
     * @param mixed $ipnUrl
     */
    public function setIpnUrl($ipnUrl)
    {
        $this->validateUrl('ipn_url', $ipnUrl);
        $this->ipnUrl = $ipnUrl;
    }

    /**
     * @return mixed
     */
    public function getNotificationEmail()
    {
        return $this->notificationEmail;
    }

    /**
     * @param mixed $notificationEmail
     */
    public function setNotificationEmail($notificationEmail)
    {
        $this->validateEmail('notification_email', $notificationEmail);
        $this->notificationEmail = $notificationEmail;
    }

    /**
     * @return mixed
     */
    public function getConfirmationSpeed()
    {
        return $this->confirmationSpeed;
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

    /**
     * @return mixed
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * @return mixed
     */
    public function getStatus()
    {
        return $this->status;
    }

    /**
     * @return mixed
     */
    public function getRedirectUrl()
    {
        return $this->redirectUrl;
    }

    /**
     * @return mixed
     */
    public function getExpiresAt()
    {
        return $this->expiresAt;
    }

    /**
     * @return mixed
     */
    public function getCreatedAt()
    {
        return $this->createdAt;
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
