<?php

namespace GloBee\PaymentApi\Models;

class Currency extends Model
{

    protected $id;
    protected $name;

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
            $self->id = $currency['id'];
            $self->name = $currency['name'];

            $currencies[] = $self;
        }

        return $currencies;
    }
}
