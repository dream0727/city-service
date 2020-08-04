<?php

namespace CityService\Drivers\Wechat\Responses;

use CityService\AbstractResponse;
use CityService\ResponseInterface;

class AllImmeDeliveryResponse extends AbstractResponse implements ResponseInterface
{
    public function getCode()
    {
        return $this->data['resultcode'] ?? null;
    }

    public function getData() {
        return $this->data['list'] ?? null;
    }

    public function isSuccessful():bool {
        return ! is_null($this->getCode()) && $this->getCode() === 0;
    }

    public function getMessage():?string {
        return $this->data['resultmsg'] ?? null;
    }
}