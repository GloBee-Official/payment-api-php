<?php

namespace GloBee\PaymentApi\Models;

abstract class Model
{
    use PropertyTrait;

    protected $properties = [];

    protected $readonlyProperties = [];
}
