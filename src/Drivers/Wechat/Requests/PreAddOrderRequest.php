<?php

namespace CityService\Drivers\Wechat\Requests;

use CityService\AbstractRequest;

class PreAddOrderRequest extends AbstractRequest
{
    protected $rules = [];

    protected $message = [];

    public function build(): array
    {
        return $this->getPayload();
    }
}