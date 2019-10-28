<?php

namespace GloBee\PaymentApi\Models;

class Account extends Model
{
    protected $name;
    protected $url;

    public function __construct($name, $url)
    {
        $this->name = $name;
        $this->url = $url;
    }

    /**
     * @param array $data
     *
     * @return Account
     */
    public static function fromResponse(array $data)
    {
        return new self($data['name'], $data['url']);
    }
}
