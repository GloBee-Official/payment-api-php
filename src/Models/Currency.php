<?php

namespace GloBee\PaymentApi\Models;

class Currency extends Model
{

    protected $id;
    protected $name;

    public function __construct($id, $name)
    {
        $this->id = $id;
        $this->name = $name;
    }

    /**
     * @param array $data
     *
     * @return Currency[]
     */
    public static function fromResponse(array $data)
    {
        $currencies = [];

        foreach ($data as $currency) {
            $self = new self($currency['id'], $currency['name']);

            $currencies[] = $self;
        }

        return $currencies;
    }
}
