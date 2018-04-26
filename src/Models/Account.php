<?php

namespace GloBee\PaymentApi\Models;

class Account extends Model
{

    protected $name;
    protected $url;

    /**
     * @param array $data
     *
     * @return Account
     */
    public static function fromResponse(array $data)
    {
        $self = new self();

        $self->name = $data['name'];
        $self->url = $data['url'];

        return $self;
    }
}
