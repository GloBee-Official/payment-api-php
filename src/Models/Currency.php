<?php

namespace GloBee\PaymentApi\Models;

class Currency extends Model
{

    protected $readonlyProperties = [
        'id' => null,
        'name' => null,
    ];

    /**
     * @param array $data
     *
     * @return Currency[]
     */
    public static function fromResponse(array $data)
    {
        $currencies = [];

        foreach ($data as $currency) {
            $self = new self();
            $self->properties['id'] = $currency['id'];
            $self->properties['name'] = $currency['name'];

            $currencies[] = $self;
        }

        return $currencies;
    }
}
