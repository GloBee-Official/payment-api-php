<?php

namespace GloBee\PaymentApi\Exceptions\Http;

class ServerErrorException extends HttpException
{
    public function __construct()
    {
        parent::__construct('Server Error');
    }
}
