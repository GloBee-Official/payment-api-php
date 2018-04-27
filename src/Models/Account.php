<?php

namespace GloBee\PaymentApi\Models;

class Account extends Model
{

    protected $readonlyProperties = [
        'name' => null,
        'url' => null,
    ];

    /**
     * @param array $data
     *
     * @return Account
     */
    public static function fromResponse(array $data)
    {
        $self = new self();

        $self->properties['name'] = $data['name'];
        $self->properties['url'] = $data['url'];

        return $self;
    }
}
